<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovementTypesSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Venta', 
                'slug' => 'sale'
            ],
            [
                'name' => 'Compra', 
                'slug' => 'purchase'
            ],
        ];

        DB::table('movement_types')->insert($methods);
    }
}
