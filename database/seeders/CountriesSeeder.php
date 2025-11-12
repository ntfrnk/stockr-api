<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            'Argentina',
        ];

        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'name' => $country
            ]);
        }
    }
}
