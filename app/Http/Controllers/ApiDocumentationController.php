<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiDocumentationController extends Controller
{
    /**
     * Display the API documentation
     */
    public function index()
    {
        $apiSpec = $this->getApiSpecification();
        return view('api.documentation', compact('apiSpec'));
    }

    /**
     * Get the API specification in OpenAPI format
     */
    public function openapi()
    {
        return response()->json($this->getApiSpecification());
    }

    /**
     * Get the complete API specification
     */
    private function getApiSpecification(): array
    {
        return [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'UGo Points System API',
                'description' => 'API para el sistema de puntos y recompensas UGo',
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'UGo Development Team',
                    'email' => 'dev@ugo-sistema.com'
                ]
            ],
            'servers' => [
                [
                    'url' => config('app.url'),
                    'description' => 'Development server'
                ]
            ],
            'components' => [
                'securitySchemes' => [
                    'FirebaseAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                        'description' => 'Firebase ID Token'
                    ]
                ],
                'schemas' => [
                    'Employee' => [
                        'type' => 'object',
                        'properties' => [
                            'id_empleado' => ['type' => 'string', 'description' => 'Firebase UID del empleado'],
                            'nombre' => ['type' => 'string', 'description' => 'Nombre completo del empleado'],
                            'email' => ['type' => 'string', 'format' => 'email', 'description' => 'Email corporativo'],
                            'puntos_totales' => ['type' => 'integer', 'description' => 'Puntos disponibles para canje'],
                            'puntos_canjeados' => ['type' => 'integer', 'description' => 'Total de puntos canjeados'],
                            'rol_usuario' => ['type' => 'string', 'enum' => ['Empleado', 'Administrador']],
                            'is_admin' => ['type' => 'boolean', 'description' => 'Si el usuario es administrador'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                            'updated_at' => ['type' => 'string', 'format' => 'date-time']
                        ]
                    ],
                    'Product' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'description' => 'ID único del producto'],
                            'nombre' => ['type' => 'string', 'description' => 'Nombre del producto'],
                            'descripcion' => ['type' => 'string', 'description' => 'Descripción detallada'],
                            'categoria' => ['type' => 'string', 'description' => 'Categoría del producto'],
                            'costo_puntos' => ['type' => 'integer', 'description' => 'Puntos necesarios para canje'],
                            'stock' => ['type' => 'integer', 'description' => 'Stock disponible (-1 = ilimitado)'],
                            'activo' => ['type' => 'boolean', 'description' => 'Si el producto está activo'],
                            'is_available' => ['type' => 'boolean', 'description' => 'Si está disponible para canje'],
                            'integra_jira' => ['type' => 'boolean', 'description' => 'Si crea ticket en Jira'],
                            'envia_email' => ['type' => 'boolean', 'description' => 'Si envía notificación por email'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                            'updated_at' => ['type' => 'string', 'format' => 'date-time']
                        ]
                    ],
                    'Order' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'description' => 'ID único del pedido'],
                            'empleado_id' => ['type' => 'string', 'description' => 'Firebase UID del empleado'],
                            'producto_id' => ['type' => 'integer', 'description' => 'ID del producto canjeado'],
                            'fecha' => ['type' => 'string', 'format' => 'date-time', 'description' => 'Fecha del canje'],
                            'estado' => ['type' => 'string', 'enum' => ['Pendiente', 'En curso', 'Realizado', 'Cancelado']],
                            'puntos_utilizados' => ['type' => 'integer', 'description' => 'Puntos utilizados en el canje'],
                            'producto_nombre' => ['type' => 'string', 'description' => 'Nombre del producto al momento del canje'],
                            'empleado_nombre' => ['type' => 'string', 'description' => 'Nombre del empleado'],
                            'observaciones' => ['type' => 'string', 'nullable' => true, 'description' => 'Observaciones del canje'],
                            'is_pending' => ['type' => 'boolean', 'description' => 'Si está pendiente'],
                            'is_completed' => ['type' => 'boolean', 'description' => 'Si está completado'],
                            'can_be_cancelled' => ['type' => 'boolean', 'description' => 'Si puede ser cancelado'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                            'updated_at' => ['type' => 'string', 'format' => 'date-time']
                        ]
                    ],
                    'ApiResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'success' => ['type' => 'boolean', 'description' => 'Si la operación fue exitosa'],
                            'message' => ['type' => 'string', 'description' => 'Mensaje descriptivo'],
                            'data' => ['type' => 'object', 'description' => 'Datos de respuesta'],
                            'errors' => ['type' => 'object', 'description' => 'Errores de validación (si aplica)'],
                            'error_code' => ['type' => 'string', 'description' => 'Código de error (si aplica)']
                        ]
                    ],
                    'ValidationError' => [
                        'type' => 'object',
                        'properties' => [
                            'success' => ['type' => 'boolean', 'example' => false],
                            'message' => ['type' => 'string', 'example' => 'Los datos proporcionados no son válidos.'],
                            'errors' => [
                                'type' => 'object',
                                'example' => [
                                    'campo' => ['El campo es requerido.']
                                ]
                            ],
                            'error_code' => ['type' => 'string', 'example' => 'VALIDATION_ERROR']
                        ]
                    ]
                ]
            ],
            'security' => [
                ['FirebaseAuth' => []]
            ],
            'paths' => [
                '/productos' => [
                    'get' => [
                        'summary' => 'Listar productos disponibles',
                        'description' => 'Obtiene una lista paginada de productos con filtros opcionales',
                        'tags' => ['Productos'],
                        'parameters' => [
                            ['name' => 'search', 'in' => 'query', 'schema' => ['type' => 'string'], 'description' => 'Buscar por nombre o descripción'],
                            ['name' => 'category', 'in' => 'query', 'schema' => ['type' => 'string'], 'description' => 'Filtrar por categoría'],
                            ['name' => 'available_only', 'in' => 'query', 'schema' => ['type' => 'boolean', 'default' => true], 'description' => 'Solo productos disponibles'],
                            ['name' => 'max_points', 'in' => 'query', 'schema' => ['type' => 'integer'], 'description' => 'Máximo de puntos'],
                            ['name' => 'sort_by', 'in' => 'query', 'schema' => ['type' => 'string', 'enum' => ['costo_puntos', 'nombre', 'categoria', 'created_at'], 'default' => 'costo_puntos']],
                            ['name' => 'sort_direction', 'in' => 'query', 'schema' => ['type' => 'string', 'enum' => ['asc', 'desc'], 'default' => 'asc']],
                            ['name' => 'per_page', 'in' => 'query', 'schema' => ['type' => 'integer', 'minimum' => 6, 'maximum' => 48, 'default' => 12]]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Lista de productos',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => ['$ref' => '#/components/schemas/Product']
                                                ],
                                                'meta' => ['type' => 'object', 'description' => 'Información de paginación']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '429' => ['description' => 'Demasiadas solicitudes - límite de velocidad excedido']
                        ]
                    ]
                ],
                '/productos/{id}/canjear' => [
                    'post' => [
                        'summary' => 'Canjear producto por puntos',
                        'description' => 'Realiza el canje de un producto utilizando los puntos del empleado',
                        'tags' => ['Productos'],
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer'], 'description' => 'ID del producto']
                        ],
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'observaciones' => ['type' => 'string', 'maxLength' => 500, 'description' => 'Observaciones opcionales']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Canje exitoso',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'allOf' => [
                                                ['$ref' => '#/components/schemas/ApiResponse'],
                                                ['type' => 'object', 'properties' => ['data' => ['$ref' => '#/components/schemas/Order']]]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '400' => ['description' => 'Error en el canje (puntos insuficientes, producto no disponible, etc.)'],
                            '422' => ['$ref' => '#/components/schemas/ValidationError'],
                            '429' => ['description' => 'Límite de canjes por minuto excedido']
                        ]
                    ]
                ],
                '/pedidos' => [
                    'get' => [
                        'summary' => 'Historial de pedidos del empleado',
                        'description' => 'Obtiene el historial de pedidos del empleado autenticado',
                        'tags' => ['Pedidos'],
                        'parameters' => [
                            ['name' => 'page', 'in' => 'query', 'schema' => ['type' => 'integer', 'minimum' => 1], 'description' => 'Página'],
                            ['name' => 'per_page', 'in' => 'query', 'schema' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 50], 'description' => 'Elementos por página'],
                            ['name' => 'estado', 'in' => 'query', 'schema' => ['type' => 'string', 'enum' => ['Pendiente', 'En curso', 'Realizado', 'Cancelado']], 'description' => 'Filtrar por estado']
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Historial de pedidos',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => ['$ref' => '#/components/schemas/Order']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                '/favoritos' => [
                    'get' => [
                        'summary' => 'Lista de productos favoritos',
                        'description' => 'Obtiene los productos marcados como favoritos por el empleado',
                        'tags' => ['Favoritos'],
                        'responses' => [
                            '200' => [
                                'description' => 'Lista de favoritos',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => ['$ref' => '#/components/schemas/Product']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'post' => [
                        'summary' => 'Agregar producto a favoritos',
                        'description' => 'Marca un producto como favorito',
                        'tags' => ['Favoritos'],
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['producto_id'],
                                        'properties' => [
                                            'producto_id' => ['type' => 'integer', 'description' => 'ID del producto']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '201' => ['$ref' => '#/components/schemas/ApiResponse'],
                            '429' => ['description' => 'Límite de cambios en favoritos por minuto excedido']
                        ]
                    ]
                ],
                '/admin/pedidos' => [
                    'get' => [
                        'summary' => 'Lista todos los pedidos (Solo administradores)',
                        'description' => 'Obtiene una lista paginada de todos los pedidos del sistema',
                        'tags' => ['Administración'],
                        'security' => [['FirebaseAuth' => []]],
                        'parameters' => [
                            ['name' => 'estado', 'in' => 'query', 'schema' => ['type' => 'string']],
                            ['name' => 'empleado_id', 'in' => 'query', 'schema' => ['type' => 'string']],
                            ['name' => 'fecha_desde', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']],
                            ['name' => 'fecha_hasta', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Lista de pedidos',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => ['$ref' => '#/components/schemas/Order']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '403' => ['description' => 'No autorizado - requiere rol de administrador'],
                            '429' => ['description' => 'Límite de solicitudes de administración excedido']
                        ]
                    ]
                ]
            ],
            'tags' => [
                ['name' => 'Productos', 'description' => 'Gestión de productos y canjes'],
                ['name' => 'Pedidos', 'description' => 'Historial y gestión de pedidos'],
                ['name' => 'Favoritos', 'description' => 'Gestión de productos favoritos'],
                ['name' => 'Administración', 'description' => 'Endpoints exclusivos para administradores']
            ]
        ];
    }
}