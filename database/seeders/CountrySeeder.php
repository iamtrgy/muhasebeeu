<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['code' => 'EE', 'name' => 'Estonia'],
            ['code' => 'DE', 'name' => 'Germany'],
            ['code' => 'FR', 'name' => 'France'],
            ['code' => 'IT', 'name' => 'Italy'],
            ['code' => 'ES', 'name' => 'Spain'],
            ['code' => 'PT', 'name' => 'Portugal'],
            ['code' => 'GR', 'name' => 'Greece'],
            ['code' => 'AT', 'name' => 'Austria'],
            ['code' => 'IE', 'name' => 'Ireland'],
            ['code' => 'NL', 'name' => 'Netherlands'],
            ['code' => 'BE', 'name' => 'Belgium'],
            ['code' => 'LU', 'name' => 'Luxembourg'],
            ['code' => 'FI', 'name' => 'Finland'],
            ['code' => 'SE', 'name' => 'Sweden'],
            ['code' => 'DK', 'name' => 'Denmark'],
            ['code' => 'PL', 'name' => 'Poland'],
            ['code' => 'CZ', 'name' => 'Czech Republic'],
            ['code' => 'SK', 'name' => 'Slovakia'],
            ['code' => 'HU', 'name' => 'Hungary'],
            ['code' => 'RO', 'name' => 'Romania'],
            ['code' => 'BG', 'name' => 'Bulgaria'],
            ['code' => 'HR', 'name' => 'Croatia'],
            ['code' => 'SI', 'name' => 'Slovenia'],
            ['code' => 'LV', 'name' => 'Latvia'],
            ['code' => 'LT', 'name' => 'Lithuania'],
            ['code' => 'MT', 'name' => 'Malta'],
            ['code' => 'CY', 'name' => 'Cyprus'],
            ['code' => 'GB', 'name' => 'United Kingdom'],
            ['code' => 'CH', 'name' => 'Switzerland'],
            ['code' => 'NO', 'name' => 'Norway'],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->insertOrIgnore($country);
        }
    }
}
