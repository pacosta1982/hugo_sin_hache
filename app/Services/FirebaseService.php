<?php

namespace App\Services;

use App\Models\Employee;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Auth\UserRecord;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Exception\AuthException;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected ?Auth $auth;

    public function __construct()
    {
        try {
            $this->auth = app(Auth::class);
        } catch (\Exception $e) {
            $this->auth = null;
            Log::warning('Firebase Auth not available: ' . $e->getMessage());
        }
    }

    public function isConfigured(): bool
    {
        return $this->auth !== null;
    }

    public function createUser(string $email, string $password, array $additionalClaims = []): ?UserRecord
    {
        if (!$this->auth) {
            throw new \Exception('Firebase not configured');
        }

        try {
            $userProperties = [
                'email' => $email,
                'password' => $password,
                'emailVerified' => false,
            ];

            if (isset($additionalClaims['displayName'])) {
                $userProperties['displayName'] = $additionalClaims['displayName'];
            }

            $user = $this->auth->createUser($userProperties);


            if (!empty($additionalClaims)) {
                $this->auth->setCustomUserClaims($user->uid, $additionalClaims);
            }

            Log::info('Firebase user created', ['uid' => $user->uid, 'email' => $email]);

            return $user;

        } catch (AuthException $e) {
            Log::error('Failed to create Firebase user', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getUser(string $uid): ?UserRecord
    {
        if (!$this->auth) {
            return null;
        }

        try {
            return $this->auth->getUser($uid);
        } catch (UserNotFound $e) {
            return null;
        } catch (AuthException $e) {
            Log::error('Failed to get Firebase user', ['uid' => $uid, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function getUserByEmail(string $email): ?UserRecord
    {
        if (!$this->auth) {
            return null;
        }

        try {
            return $this->auth->getUserByEmail($email);
        } catch (UserNotFound $e) {
            return null;
        } catch (AuthException $e) {
            Log::error('Failed to get Firebase user by email', ['email' => $email, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function updateUserPassword(string $uid, string $newPassword): bool
    {
        if (!$this->auth) {
            return false;
        }

        try {
            $this->auth->updateUser($uid, ['password' => $newPassword]);
            Log::info('Firebase user password updated', ['uid' => $uid]);
            return true;
        } catch (AuthException $e) {
            Log::error('Failed to update user password', ['uid' => $uid, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function deleteUser(string $uid): bool
    {
        if (!$this->auth) {
            return false;
        }

        try {
            $this->auth->deleteUser($uid);
            Log::info('Firebase user deleted', ['uid' => $uid]);
            return true;
        } catch (AuthException $e) {
            Log::error('Failed to delete Firebase user', ['uid' => $uid, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function createCustomToken(string $uid, array $additionalClaims = []): ?string
    {
        if (!$this->auth) {
            return null;
        }

        try {
            $customToken = $this->auth->createCustomToken($uid, $additionalClaims);
            return $customToken->toString();
        } catch (AuthException $e) {
            Log::error('Failed to create custom token', ['uid' => $uid, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function verifyIdToken(string $idToken): ?array
    {
        if (!$this->auth) {
            return null;
        }

        try {
            $verifiedToken = $this->auth->verifyIdToken($idToken);
            return $verifiedToken->claims()->all();
        } catch (AuthException $e) {
            Log::error('Failed to verify ID token', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function revokeRefreshTokens(string $uid): bool
    {
        if (!$this->auth) {
            return false;
        }

        try {
            $this->auth->revokeRefreshTokens($uid);
            Log::info('Refresh tokens revoked', ['uid' => $uid]);
            return true;
        } catch (AuthException $e) {
            Log::error('Failed to revoke refresh tokens', ['uid' => $uid, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function syncEmployeeWithFirebase(Employee $employee, string $email, string $password = null): ?UserRecord
    {
        if (!$this->auth) {
            throw new \Exception('Firebase not configured');
        }


        $existingUser = $this->getUserByEmail($email);
        
        if ($existingUser) {

            if ($employee->id_empleado !== $existingUser->uid) {
                $employee->update(['id_empleado' => $existingUser->uid]);
            }
            
            return $existingUser;
        }


        if (!$password) {
            $password = \Str::random(12);
        }

        $additionalClaims = [
            'displayName' => $employee->nombre,
            'role' => $employee->rol_usuario,
            'isAdmin' => $employee->is_admin,
        ];

        $firebaseUser = $this->createUser($email, $password, $additionalClaims);

        if ($firebaseUser) {

            $employee->update(['id_empleado' => $firebaseUser->uid]);
        }

        return $firebaseUser;
    }

    public function listUsers(int $maxResults = 1000, ?string $pageToken = null): array
    {
        if (!$this->auth) {
            return [];
        }

        try {
            $listUsersResult = $this->auth->listUsers($maxResults, $maxResults);
            
            $users = [];
            foreach ($listUsersResult as $user) {
                $users[] = [
                    'uid' => $user->uid,
                    'email' => $user->email,
                    'displayName' => $user->displayName,
                    'disabled' => $user->disabled,
                    'emailVerified' => $user->emailVerified,
                    'createdAt' => $user->metadata->createdAt->format('Y-m-d H:i:s'),
                    'lastLoginAt' => $user->metadata->lastLoginAt ? $user->metadata->lastLoginAt->format('Y-m-d H:i:s') : null,
                ];
            }

            return [
                'users' => $users,
                'pageToken' => $listUsersResult->hasNextPage() ? $listUsersResult->getNextPageToken() : null,
            ];

        } catch (AuthException $e) {
            Log::error('Failed to list Firebase users', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getConfigurationStatus(): array
    {
        return [
            'configured' => $this->isConfigured(),
            'project_id' => env('FIREBASE_PROJECT_ID'),
            'credentials_file' => env('FIREBASE_CREDENTIALS'),
            'credentials_exists' => file_exists(env('FIREBASE_CREDENTIALS', '')),
            'frontend_config' => [
                'api_key' => !empty(env('VITE_FIREBASE_API_KEY')),
                'auth_domain' => !empty(env('VITE_FIREBASE_AUTH_DOMAIN')),
                'project_id' => !empty(env('VITE_FIREBASE_PROJECT_ID')),
                'storage_bucket' => !empty(env('VITE_FIREBASE_STORAGE_BUCKET')),
                'messaging_sender_id' => !empty(env('VITE_FIREBASE_MESSAGING_SENDER_ID')),
                'app_id' => !empty(env('VITE_FIREBASE_APP_ID')),
            ],
        ];
    }
}