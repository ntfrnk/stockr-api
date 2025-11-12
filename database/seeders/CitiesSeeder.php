<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'San Miguel de Tucumán',
            'Yerba Buena',
            'Tafí Viejo',
            'Las Talitas',
            'Alderetes',
            'Banda del Río Salí',
            'Bella Vista',
            'Concepción',
            'Monteros',
            'Famaillá',
            'Aguilares',
            'Lules',
            'Tafí del Valle',
            'Trancas',
            'Simoca',
            'Burruyacú',
            'Graneros',
            'La Cocha',
            'Leales',
            'Ranchillos',
            'Los Ralos',
            'Villa Quinteros',
            'Santa Ana',
            'Arcadia',
            'Amaicha del Valle',
            'Colalao del Valle',
            'El Mollar',
            'San Pedro de Colalao',
            'La Florida',
            'Los Nogales',
            'El Manantial',
            'Cevil Redondo',
            'Villa Carmela',
            'San Andrés',
            'Villa Mariano Moreno',
            'San Pablo',
            'Los Bulacios',
            'El Timbó',
            'Río Seco',
            'San Isidro de Lules',
            'Villa de Leales',
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'name' => $city,
                'state_id' => 23,
            ]);
        }
    }
}
