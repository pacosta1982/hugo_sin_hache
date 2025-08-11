<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\Validator;

class PointsController extends Controller
{
    public function awardPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id_empleado',
            'points' => 'required|integer|min:1|max:10000',
            'description' => 'required|string|max:255',
            'metadata' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $employee = Employee::find($request->employee_id);
            
            $employee->awardPoints(
                (int) $request->points,
                $request->description,
                null,
                $request->metadata ?? null
            );

            return response()->json([
                'success' => true,
                'message' => "Successfully awarded {$request->points} points to {$employee->nombre}",
                'data' => [
                    'employee' => [
                        'id' => $employee->id_empleado,
                        'name' => $employee->nombre,
                        'total_points' => $employee->puntos_totales,
                        'redeemed_points' => $employee->puntos_canjeados,
                    ],
                    'transaction' => [
                        'points' => $request->points,
                        'description' => $request->description,
                        'timestamp' => now()->toISOString(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to award points: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getEmployeePoints(Request $request, $employeeId)
    {
        $employee = Employee::find($employeeId);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'employee' => [
                    'id' => $employee->id_empleado,
                    'name' => $employee->nombre,
                    'email' => $employee->email,
                    'total_points' => $employee->puntos_totales,
                    'redeemed_points' => $employee->puntos_canjeados,
                    'available_points' => $employee->puntos_totales,
                ]
            ]
        ]);
    }

    public function getTransactionHistory(Request $request, $employeeId)
    {
        $employee = Employee::find($employeeId);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $limit = min($request->input('limit', 50), 100);
        $transactions = $employee->pointTransactions()
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'employee' => [
                    'id' => $employee->id_empleado,
                    'name' => $employee->nombre,
                    'total_points' => $employee->puntos_totales,
                ],
                'transactions' => $transactions->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'type' => $transaction->type,
                        'points' => $transaction->points,
                        'description' => $transaction->description,
                        'admin' => $transaction->admin ? $transaction->admin->nombre : 'External System',
                        'metadata' => $transaction->metadata,
                        'created_at' => $transaction->created_at->toISOString(),
                    ];
                })
            ]
        ]);
    }

    public function bulkAwardPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'awards' => 'required|array|min:1|max:100',
            'awards.*.employee_id' => 'required|exists:employees,id_empleado',
            'awards.*.points' => 'required|integer|min:1|max:10000',
            'awards.*.description' => 'required|string|max:255',
            'awards.*.metadata' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $results = [];
        $totalPointsAwarded = 0;
        $successfulAwards = 0;

        foreach ($request->awards as $award) {
            try {
                $employee = Employee::find($award['employee_id']);
                
                $employee->awardPoints(
                    (int) $award['points'],
                    $award['description'],
                    null,
                    $award['metadata'] ?? null
                );

                $results[] = [
                    'employee_id' => $employee->id_empleado,
                    'success' => true,
                    'points_awarded' => $award['points'],
                    'message' => "Successfully awarded {$award['points']} points to {$employee->nombre}"
                ];

                $totalPointsAwarded += $award['points'];
                $successfulAwards++;

            } catch (\Exception $e) {
                $results[] = [
                    'employee_id' => $award['employee_id'],
                    'success' => false,
                    'message' => 'Failed to award points: ' . $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Bulk point award completed. {$successfulAwards} successful awards out of " . count($request->awards),
            'summary' => [
                'total_awards_processed' => count($request->awards),
                'successful_awards' => $successfulAwards,
                'failed_awards' => count($request->awards) - $successfulAwards,
                'total_points_awarded' => $totalPointsAwarded,
            ],
            'results' => $results
        ]);
    }
}
