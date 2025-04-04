<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'United States', 'code' => 'US'],
            ['name' => 'United Kingdom', 'code' => 'GB'],
            ['name' => 'Canada', 'code' => 'CA'],
            ['name' => 'Australia', 'code' => 'AU'],
            ['name' => 'Germany', 'code' => 'DE'],
            ['name' => 'France', 'code' => 'FR'],
            ['name' => 'Italy', 'code' => 'IT'],
            ['name' => 'Spain', 'code' => 'ES'],
            ['name' => 'Netherlands', 'code' => 'NL'],
            ['name' => 'Belgium', 'code' => 'BE'],
            ['name' => 'Switzerland', 'code' => 'CH'],
            ['name' => 'Austria', 'code' => 'AT'],
            ['name' => 'Sweden', 'code' => 'SE'],
            ['name' => 'Norway', 'code' => 'NO'],
            ['name' => 'Denmark', 'code' => 'DK'],
            ['name' => 'Finland', 'code' => 'FI'],
            ['name' => 'Ireland', 'code' => 'IE'],
            ['name' => 'Portugal', 'code' => 'PT'],
            ['name' => 'Greece', 'code' => 'GR'],
            ['name' => 'Poland', 'code' => 'PL'],
            ['name' => 'Czech Republic', 'code' => 'CZ'],
            ['name' => 'Hungary', 'code' => 'HU'],
            ['name' => 'Estonia', 'code' => 'EE'],
            ['name' => 'Turkey', 'code' => 'TR'],
            ['name' => 'Japan', 'code' => 'JP'],
            ['name' => 'China', 'code' => 'CN'],
            ['name' => 'India', 'code' => 'IN'],
            ['name' => 'Brazil', 'code' => 'BR'],
            ['name' => 'Mexico', 'code' => 'MX'],
            ['name' => 'South Africa', 'code' => 'ZA'],
            ['name' => 'Russia', 'code' => 'RU'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
