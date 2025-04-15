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
            ['code' => 'EE', 'name' => 'Estonia', 'is_eu' => true],
            ['code' => 'DE', 'name' => 'Germany', 'is_eu' => true],
            ['code' => 'FR', 'name' => 'France', 'is_eu' => true],
            ['code' => 'IT', 'name' => 'Italy', 'is_eu' => true],
            ['code' => 'ES', 'name' => 'Spain', 'is_eu' => true],
            ['code' => 'PT', 'name' => 'Portugal', 'is_eu' => true],
            ['code' => 'GR', 'name' => 'Greece', 'is_eu' => true],
            ['code' => 'AT', 'name' => 'Austria', 'is_eu' => true],
            ['code' => 'IE', 'name' => 'Ireland', 'is_eu' => true],
            ['code' => 'NL', 'name' => 'Netherlands', 'is_eu' => true],
            ['code' => 'BE', 'name' => 'Belgium', 'is_eu' => true],
            ['code' => 'LU', 'name' => 'Luxembourg', 'is_eu' => true],
            ['code' => 'FI', 'name' => 'Finland', 'is_eu' => true],
            ['code' => 'SE', 'name' => 'Sweden', 'is_eu' => true],
            ['code' => 'DK', 'name' => 'Denmark', 'is_eu' => true],
            ['code' => 'PL', 'name' => 'Poland', 'is_eu' => true],
            ['code' => 'CZ', 'name' => 'Czech Republic', 'is_eu' => true],
            ['code' => 'SK', 'name' => 'Slovakia', 'is_eu' => true],
            ['code' => 'HU', 'name' => 'Hungary', 'is_eu' => true],
            ['code' => 'RO', 'name' => 'Romania', 'is_eu' => true],
            ['code' => 'BG', 'name' => 'Bulgaria', 'is_eu' => true],
            ['code' => 'HR', 'name' => 'Croatia', 'is_eu' => true],
            ['code' => 'SI', 'name' => 'Slovenia', 'is_eu' => true],
            ['code' => 'LV', 'name' => 'Latvia', 'is_eu' => true],
            ['code' => 'LT', 'name' => 'Lithuania', 'is_eu' => true],
            ['code' => 'MT', 'name' => 'Malta', 'is_eu' => true],
            ['code' => 'CY', 'name' => 'Cyprus', 'is_eu' => true],
            // Non-EU countries
            ['code' => 'GB', 'name' => 'United Kingdom', 'is_eu' => false],
            ['code' => 'CH', 'name' => 'Switzerland', 'is_eu' => false],
            ['code' => 'NO', 'name' => 'Norway', 'is_eu' => false],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->insertOrIgnore($country);
        }
    }
}
