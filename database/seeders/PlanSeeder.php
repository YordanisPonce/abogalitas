<?php

namespace Database\Seeders;

use App\Services\PlanService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{

    public function __construct(private readonly PlanService $planService)
    {

    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Básico',
                'description' => "Ideal para pequeños restaurantes que están comenzando a gestionar su cadena de suministro a través de EXUM.",
                'price' => 0,
                'features' => [
                    [
                        'description' => 'Acceso a proveedores verificados',
                    ],
                    [
                        'description' => 'Gestión de hasta 10 pedidos mensuales',
                    ],
                    [
                        'description' => 'Alertas básicas de stock',
                    ],
                    [
                        'description' => 'Soporte por email',
                    ],
                ]
            ],
            [
                'name' => 'Plan Pro',
                'description' => "Perfecto para restaurantes en crecimiento que necesitan una gestión más avanzada de sus pedidos y stock.",
                'price' => 49,
                'plan_id' => 0,
                'features' => [
                    [
                        'description' => 'Pedidos ilimitados',
                    ],
                    [
                        'description' => 'Gestión avanzada de inventario',
                    ],
                    [
                        'description' => 'Recomendaciones personalizadas basadas en consumo',
                    ],
                    [
                        'description' => 'Chat directo con proveedores',
                    ],
                    [
                        'description' => 'Soporte prioritario por email y chat',
                    ],
                ]
            ],
            [
                'name' => 'Plan Premium',
                'description' => "Para grandes restaurantes o cadenas que buscan una optimización total de su cadena de suministro y el apoyo de herramientas avanzadas.",
                'price' => 99,
                'plan_id' => 1,
                'features' => [
                    [
                        'description' => 'Integraciones con sistemas de gestión (ERP, POS)',
                    ],
                    [
                        'description' => 'Módulo avanzado de análisis y reportes',
                    ],
                    [
                        'description' => 'Pedidos automáticos configurables',
                    ],
                    [
                        'description' => 'Asistencia 24/7 personalizada',
                    ],
                    [
                        'description' => 'Acceso exclusivo a proveedores premium',
                    ],
                ]
            ]


        ];

        $p = [];
        foreach ($plans as $key => $value) {
            if ($key == 0) {
                $p[] = $this->planService->save($value)['data'];
            } else {
                $p[] = $this->planService->save(array_merge($value, ['plan_id' => $p[$key - 1]->id]))['data'];
            }


        }

    }
}
