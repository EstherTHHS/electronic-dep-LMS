<?php

namespace Database\Seeders;

use App\Models\Year;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [
            ['name' => 'First Year', 'is_active' => true],
            ['name' => 'Second Year', 'is_active' => true],
            ['name' => 'Third Year', 'is_active' => true],
            ['name' => 'Fourth Year', 'is_active' => true],
            ['name' => 'Fifth Year', 'is_active' => true],
            ['name' => 'Sixth Year', 'is_active' => true],
        ];

        foreach ($years as $year) {
            Year::updateOrCreate(['name' => $year['name']], $year);
        }
    }
}
