<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Employee;
use App\Models\Product;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderStatusUpdateMail;
use App\Mail\PointsAwardedMail;
use App\Mail\LowStockAlertMail;
use App\Mail\OrderNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class EmailService
{
    public function __construct(
        private ProductRecommendationService $recommendationService
    ) {}

    public function sendOrderConfirmation(Order $order): bool
    {
        try {
            if (!$order->employee->email || !$order->product->envia_email) {
                return false;
            }

            Mail::to($order->employee->email)
                ->send(new OrderConfirmationMail($order, $order->employee));

            Log::info('Order confirmation email sent', [
                'order_id' => $order->id,
                'employee_id' => $order->employee->id_empleado,
                'email' => $order->employee->email
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'employee_id' => $order->employee->id_empleado,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendOrderStatusUpdate(Order $order): bool
    {
        try {
            if (!$order->employee->email || !$order->product->envia_email) {
                return false;
            }

            Mail::to($order->employee->email)
                ->send(new OrderStatusUpdateMail($order, $order->employee));

            Log::info('Order status update email sent', [
                'order_id' => $order->id,
                'new_status' => $order->estado,
                'employee_id' => $order->employee->id_empleado,
                'email' => $order->employee->email
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order status update email', [
                'order_id' => $order->id,
                'new_status' => $order->estado,
                'employee_id' => $order->employee->id_empleado,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendPointsAwardedNotification(
        Employee $employee,
        int $points,
        string $description,
        ?string $awardedBy = null
    ): bool {
        try {
            if (!$employee->email) {
                return false;
            }


            $recommendations = $this->recommendationService
                ->getRecommendationsForEmployee($employee->id_empleado, 3);

            Mail::to($employee->email)
                ->send(new PointsAwardedMail(
                    $employee,
                    $points,
                    $description,
                    $awardedBy,
                    $recommendations
                ));

            Log::info('Points awarded email sent', [
                'employee_id' => $employee->id_empleado,
                'points' => $points,
                'email' => $employee->email,
                'awarded_by' => $awardedBy
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send points awarded email', [
                'employee_id' => $employee->id_empleado,
                'points' => $points,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendLowStockAlert(
        Collection $lowStockProducts,
        Collection $outOfStockProducts
    ): bool {
        try {
            $adminEmails = $this->getAdminEmails();
            
            if ($adminEmails->isEmpty()) {
                Log::warning('No admin emails configured for low stock alerts');
                return false;
            }

            $totalActiveProducts = Product::active()->count();
            $lowStockCount = $lowStockProducts->count();
            $outOfStockCount = $outOfStockProducts->count();

            foreach ($adminEmails as $email) {
                Mail::to($email)
                    ->send(new LowStockAlertMail(
                        $lowStockProducts,
                        $outOfStockProducts,
                        $totalActiveProducts,
                        $lowStockCount,
                        $outOfStockCount
                    ));
            }

            Log::info('Low stock alert emails sent', [
                'admin_count' => $adminEmails->count(),
                'low_stock_products' => $lowStockCount,
                'out_of_stock_products' => $outOfStockCount
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send low stock alert emails', [
                'low_stock_products' => $lowStockProducts->count(),
                'out_of_stock_products' => $outOfStockProducts->count(),
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendOrderNotificationToAdmins(Order $order): bool
    {
        try {
            $adminEmails = $this->getAdminEmails();
            
            if ($adminEmails->isEmpty()) {
                Log::warning('No admin emails configured for order notifications');
                return false;
            }

            foreach ($adminEmails as $email) {
                Mail::to($email)
                    ->send(new OrderNotificationMail($order));
            }

            Log::info('Order notification emails sent to admins', [
                'order_id' => $order->id,
                'admin_count' => $adminEmails->count(),
                'product' => $order->product->nombre
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order notification emails to admins', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendBulkPointsNotification(
        Collection $employeePointsData
    ): array {
        $results = [];
        
        foreach ($employeePointsData as $data) {
            $employee = Employee::find($data['employee_id']);
            if (!$employee) {
                $results[$data['employee_id']] = false;
                continue;
            }

            $results[$data['employee_id']] = $this->sendPointsAwardedNotification(
                $employee,
                $data['points'],
                $data['description'],
                $data['awarded_by'] ?? null
            );
        }

        return $results;
    }

    public function testEmailConfiguration(): array
    {
        try {
            $testEmail = config('mail.test_email') ?? 'test@example.com';
            

            Mail::raw('Test email from UGo Rewards System', function ($message) use ($testEmail) {
                $message->to($testEmail)
                       ->subject('UGo Rewards - Test Email Configuration');
            });

            return [
                'success' => true,
                'message' => 'Test email sent successfully',
                'configuration' => [
                    'driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
                'configuration' => [
                    'driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                ]
            ];
        }
    }

    private function getAdminEmails(): Collection
    {

        $configEmails = collect(explode(',', config('mail.admin_emails', '')))
            ->map(fn($email) => trim($email))
            ->filter(fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL));


        $adminEmployeeEmails = Employee::where('rol_usuario', 'Administrador')
            ->whereNotNull('email')
            ->pluck('email')
            ->filter(fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL));

        return $configEmails->merge($adminEmployeeEmails)->unique();
    }

    public function getEmailMetrics(): array
    {

        return [
            'total_sent_today' => 0,
            'total_failed_today' => 0,
            'admin_emails_configured' => $this->getAdminEmails()->count(),
            'email_driver' => config('mail.default'),
            'queue_enabled' => config('queue.default') !== 'sync',
        ];
    }
}