<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentEntitiesSeeder extends Seeder
{
    public function run(): void
    {
        $entities = [

            // --- Bancos principales ---
            [
                'name' => 'Banco de la Nación Argentina',
                'slug' => 'banco-nacion',
                'type' => 'bank',
                'short_name' => 'BNA'
            ],
            [
                'name' => 'Banco Santander Argentina',
                'slug' => 'santander',
                'type' => 'bank',
                'short_name' => 'SANTANDER'
            ],
            [
                'name' => 'BBVA Argentina',
                'slug' => 'bbva',
                'type' => 'bank',
                'short_name' => 'BBVA'
            ],
            [
                'name' => 'Banco Galicia',
                'slug' => 'galicia',
                'type' => 'bank',
                'short_name' => 'GALICIA'
            ],
            [
                'name' => 'Banco Macro',
                'slug' => 'macro',
                'type' => 'bank',
                'short_name' => 'MACRO'
            ],
            [
                'name' => 'Banco Supervielle',
                'slug' => 'supervielle',
                'type' => 'bank',
                'short_name' => 'SUPERVIELLE'
            ],
            [
                'name' => 'Banco Itaú Argentina',
                'slug' => 'itau',
                'type' => 'bank',
                'short_name' => 'ITAU'
            ],
            [
                'name' => 'HSBC Argentina',
                'slug' => 'hsbc',
                'type' => 'bank',
                'short_name' => 'HSBC'
            ],
            [
                'name' => 'Banco Credicoop Cooperativo Limitado',
                'slug' => 'credicoop',
                'type' => 'bank',
                'short_name' => 'CREDICOOP'
            ],
            [
                'name' => 'Banco Patagonia',
                'slug' => 'patagonia',
                'type' => 'bank',
                'short_name' => 'PATAGONIA'
            ],
            [
                'name' => 'Banco Hipotecario',
                'slug' => 'hipotecario',
                'type' => 'bank',
                'short_name' => 'HIPOTECARIO'
            ],

            [
                'name' => 'Mercado Pago',
                'slug' => 'mercado-pago',
                'type' => 'wallet',
                'short_name' => 'MP'
            ],
            [
                'name' => 'Ualá',
                'slug' => 'uala',
                'type' => 'wallet',
                'short_name' => 'UALA'
            ],
            [
                'name' => 'Modo',
                'slug' => 'modo',
                'type' => 'wallet',
                'short_name' => 'MODO'
            ],
            [
                'name' => 'Naranja X',
                'slug' => 'naranja-x',
                'type' => 'wallet',
                'short_name' => 'NX'
            ],
            [
                'name' => 'Personal Pay',
                'slug' => 'personal-pay',
                'type' => 'wallet',
                'short_name' => 'PPAY'
            ],
            
            [
                'name' => 'Otros',
                'slug' => 'wilobank',
                'type' => 'fintech',
                'short_name' => 'OTROS'
            ],
        ];

        DB::table('payment_entities')->insert($entities);
    }
}
