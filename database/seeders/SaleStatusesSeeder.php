<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sale_statuses')->insert([
            ['name' => 'Pendiente', 'slug' => 'pending'],
            ['name' => 'Recibida', 'slug' => 'received'],
            ['name' => 'Cancelada', 'slug' => 'cancelled'],
            ['name' => 'En tránsito', 'slug' => 'in_transit'],
        ]);
    }
}
