<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LsaLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = ['Level 1', 'Level 2', 'Level 3'];
        foreach ($levels as $level) {
            \App\Models\LsaLevel::create(['name' => $level]);
        }
    }
}
