<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['code' => 'I', 'name' => 'Ilocos Region'],
            ['code' => 'II', 'name' => 'Cagayan Valley'],
            ['code' => 'III', 'name' => 'Central Luzon'],
            ['code' => 'IV-A', 'name' => 'CALABARZON'],
            ['code' => 'IV-B', 'name' => 'MIMAROPA'],
            ['code' => 'V', 'name' => 'Bicol Region'],
            ['code' => 'VI', 'name' => 'Western Visayas'],
            ['code' => 'VII', 'name' => 'Central Visayas'],
            ['code' => 'VIII', 'name' => 'Eastern Visayas'],
            ['code' => 'IX', 'name' => 'Zamboanga Peninsula'],
            ['code' => 'X', 'name' => 'Northern Mindanao'],
            ['code' => 'XI', 'name' => 'Davao Region'],
            ['code' => 'XII', 'name' => 'SOCCSKSARGEN'],
            ['code' => 'XIII', 'name' => 'Caraga'],
            ['code' => 'NCR', 'name' => 'National Capital Region'],
            ['code' => 'CAR', 'name' => 'Cordillera Administrative Region'],
            ['code' => 'BARMM', 'name' => 'Bangsamoro Autonomous Region in Muslim Mindanao'],
        ];

        foreach ($regions as $region) {
            Region::updateOrCreate(
                ['region_code' => $region['code']], // Unique check
                ['name' => $region['name']]
            );
        }
    }
}