<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Employee;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function __construct(
        private EmailService $emailService
    ) {}

    public function sendOrderNotifications(Order $order): array
    {
        $results = [
            'jira' => null,
            'email' => null,
            'admin_email' => null,
        ];

        $product = $order->product;
        $employee = $order->employee;

        if ($product && $product->integra_jira) {
            $results['jira'] = $this->createJiraTicket($order);
        }

        if ($product && $product->envia_email) {
            $results['email'] = $this->emailService->sendOrderConfirmation($order);
            $results['admin_email'] = $this->emailService->sendOrderNotificationToAdmins($order);
        }

        return $results;
    }

    public function sendOrderStatusUpdate(Order $order): array
    {
        $results = [
            'email' => null,
        ];

        if ($order->product && $order->product->envia_email) {
            $results['email'] = $this->emailService->sendOrderStatusUpdate($order);
        }

        return $results;
    }

    public function createJiraTicket(Order $order): array
    {
        try {
            $jiraConfig = $this->getJiraConfig();
            
            if (!$jiraConfig['enabled']) {
                return [
                    'success' => false,
                    'message' => 'Jira integration not configured',
                ];
            }

            $ticketData = $this->buildJiraTicketData($order);
            
            $response = Http::withBasicAuth(
                $jiraConfig['user_email'], 
                $jiraConfig['api_token']
            )->post($jiraConfig['base_url'] . '/rest/api/3/issue', $ticketData);

            if ($response->successful()) {
                $ticketInfo = $response->json();
                
                Log::info('Jira ticket created successfully', [
                    'order_id' => $order->id,
                    'ticket_key' => $ticketInfo['key'] ?? null,
                    'ticket_id' => $ticketInfo['id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'ticket_key' => $ticketInfo['key'] ?? null,
                    'ticket_id' => $ticketInfo['id'] ?? null,
                    'message' => 'Ticket de Jira creado exitosamente',
                ];
            } else {
                Log::error('Failed to create Jira ticket', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Error al crear ticket de Jira: ' . $response->body(),
                ];
            }

        } catch (\Exception $e) {
            Log::error('Jira ticket creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error al crear ticket de Jira: ' . $e->getMessage(),
            ];
        }
    }

    public function sendEmailNotification(Order $order): array
    {
        try {
            $emailConfig = $this->getEmailConfig();
            
            if (!$emailConfig['enabled']) {
                return [
                    'success' => false,
                    'message' => 'Email notifications not configured',
                ];
            }


            Mail::send('emails.order-confirmation', [
                'order' => $order,
                'employee' => $order->employee,
                'product' => $order->product,
            ], function ($message) use ($order, $emailConfig) {
                $message->to($order->employee->email, $order->employee->nombre)
                        ->from($emailConfig['from_address'], $emailConfig['from_name'])
                        ->subject('Confirmación de Canje - Pedido #' . $order->id);
            });


            if ($emailConfig['admin_email']) {
                Mail::send('emails.admin-order-notification', [
                    'order' => $order,
                    'employee' => $order->employee,
                    'product' => $order->product,
                ], function ($message) use ($order, $emailConfig) {
                    $message->to($emailConfig['admin_email'])
                            ->from($emailConfig['from_address'], $emailConfig['from_name'])
                            ->subject('Nuevo Pedido - #' . $order->id);
                });
            }

            Log::info('Email notifications sent successfully', [
                'order_id' => $order->id,
                'employee_email' => $order->employee->email,
            ]);

            return [
                'success' => true,
                'message' => 'Notificaciones por email enviadas exitosamente',
            ];

        } catch (\Exception $e) {
            Log::error('Email notification failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error al enviar notificación por email: ' . $e->getMessage(),
            ];
        }
    }

    private function buildJiraTicketData(Order $order): array
    {
        $summary = "Canje de producto: {$order->producto_nombre} - {$order->empleado_nombre}";
        
        $description = [
            "type" => "doc",
            "version" => 1,
            "content" => [
                [
                    "type" => "paragraph",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => "Se ha realizado un nuevo canje de producto en el sistema UGo."
                        ]
                    ]
                ],
                [
                    "type" => "paragraph",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => "Detalles del pedido:",
                            "marks" => [["type" => "strong"]]
                        ]
                    ]
                ],
                [
                    "type" => "bulletList",
                    "content" => [
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "ID del pedido: {$order->id}"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "Empleado: {$order->empleado_nombre} ({$order->empleado_id})"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "Producto: {$order->producto_nombre}"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "Puntos utilizados: {$order->puntos_utilizados}"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "Fecha: {$order->fecha->format('d/m/Y H:i')}"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            'fields' => [
                'project' => [
                    'key' => config('services.jira.project_key', env('JIRA_DEFAULT_PROJECT_KEY'))
                ],
                'summary' => $summary,
                'description' => $description,
                'issuetype' => [
                    'id' => config('services.jira.request_type_id', env('JIRA_DEFAULT_REQUEST_TYPE_ID'))
                ],
                'priority' => [
                    'name' => 'Medium'
                ]
            ]
        ];
    }

    private function getJiraConfig(): array
    {
        return [
            'enabled' => !empty(env('JIRA_BASE_URL')) && !empty(env('JIRA_API_TOKEN')),
            'base_url' => env('JIRA_BASE_URL'),
            'user_email' => env('JIRA_USER_EMAIL'),
            'api_token' => env('JIRA_API_TOKEN'),
            'project_key' => env('JIRA_DEFAULT_PROJECT_KEY'),
            'request_type_id' => env('JIRA_DEFAULT_REQUEST_TYPE_ID'),
        ];
    }

    private function getEmailConfig(): array
    {
        return [
            'enabled' => !empty(env('EMAIL_HOST')) && !empty(env('EMAIL_FROM')),
            'from_address' => env('EMAIL_FROM', config('mail.from.address')),
            'from_name' => env('APP_NAME', 'UGo Sistema de Puntos'),
            'admin_email' => env('EMAIL_ADMIN', null),
        ];
    }
}