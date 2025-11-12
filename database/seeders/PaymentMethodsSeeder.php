<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Efectivo', 
                'slug' => 'cash'
            ],
            [
                'name' => 'Transferencia', 
                'slug' => 'bank_transfer'
            ],
            [
                'name' => 'Tarjeta de débito', 
                'slug' => 'debit_card'
            ],
            [
                'name' => 'Tarjeta de crédito', 
                'slug' => 'credit_card'
            ],
            [
                'name' => 'Otro', 
                'slug' => 'other'
            ],
        ];

        DB::table('payment_methods')->insert($methods);
    }
}
