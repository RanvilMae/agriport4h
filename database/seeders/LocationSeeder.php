<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\Province;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'I' => ['Ilocos Norte', 'Ilocos Sur', 'La Union', 'Pangasinan'],
            'II' => ['Batanes', 'Cagayan', 'Isabela', 'Nueva Vizcaya', 'Quirino'],
            'III' => ['Aurora', 'Bataan', 'Bulacan', 'Nueva Ecija', 'Pampanga', 'Tarlac', 'Zambales'],
            'IV-A' => ['Batangas', 'Cavite', 'Laguna', 'Quezon', 'Rizal'],
            'IV-B' => ['Marinduque', 'Occidental Mindoro', 'Oriental Mindoro', 'Palawan', 'Romblon'],
            'V' => ['Albay', 'Camarines Norte', 'Camarines Sur', 'Catanduanes', 'Masbate', 'Sorsogon'],
            'VI' => ['Aklan', 'Antique', 'Capiz', 'Guimaras', 'Iloilo', 'Negros Occidental'],
            'VII' => ['Bohol', 'Cebu', 'Negros Oriental', 'Siquijor'],
            'VIII' => ['Biliran', 'Eastern Samar', 'Leyte', 'Northern Samar', 'Samar', 'Southern Leyte'],
            'IX' => ['Zamboanga del Norte', 'Zamboanga del Sur', 'Zamboanga Sibugay'],
            'X' => ['Bukidnon', 'Camiguin', 'Lanao del Norte', 'Misamis Occidental', 'Misamis Oriental'],
            'XI' => ['Davao de Oro', 'Davao del Norte', 'Davao del Sur', 'Davao Occidental', 'Davao Oriental'],
            'XII' => ['Cotabato', 'Sarangani', 'South Cotabato', 'Sultan Kudarat'],
            'XIII' => ['Agusan del Norte', 'Agusan del Sur', 'Dinagat Islands', 'Surigao del Norte', 'Surigao del Sur'],
        ];

        foreach ($data as $regionCode => $provinces) {
            // Create or Find the Region
            $region = Region::firstOrCreate([
                'region_code' => $regionCode
            ], [
                'name' => $this->getRegionName($regionCode)
            ]);

            foreach ($provinces as $provinceName) {
                Province::create([
                    'region_id' => $region->id,
                    'name' => $provinceName
                ]);
            }
        }
    }

    private function getRegionName($code)
    {
        $names = [
            'I' => 'Ilocos Region',
            'II' => 'Cagayan Valley',
            'III' => 'Central Luzon',
            'IV-A' => 'CALABARZON',
            'IV-B' => 'MIMAROPA',
            'V' => 'Bicol Region',
            'VI' => 'Western Visayas',
            'VII' => 'Central Visayas',
            'VIII' => 'Eastern Visayas',
            'IX' => 'Zamboanga Peninsula',
            'X' => 'Northern Mindanao',
            'XI' => 'Davao Region',
            'XII' => 'SOCCSKSARGEN',
            'XIII' => 'Caraga',
        ];
        return $names[$code] ?? "Region $code";
    }
}