<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuffixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suffixes = [
            ['name' => 'N/A'],
            ['name' => 'Jr.'],
            ['name' => 'Sr.'],
            ['name' => 'II'],
            ['name' => 'III'],
            ['name' => 'IV'],
            ['name' => 'V'],
            ['name' => 'VI'],
        ];

        foreach ($suffixes as $suffix) {
            DB::table('suffixes')->updateOrInsert(
                ['name' => $suffix['name']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}