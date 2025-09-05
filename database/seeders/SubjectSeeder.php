<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Myanmar', 'code' => 'M-11011', 'description' => 'Myanmar 101', 'is_active' => true],
            ['name' => 'English I', 'code' => 'E-11011', 'description' => 'English 101', 'is_active' => true],
            ['name' => 'Engineering Mathematics', 'code' => 'EM-11011', 'description' => 'Eng Mathematics 101', 'is_active' => true],
            ['name' => 'Engineering Physics', 'code' => 'EPh-11011', 'description' => 'Eng Physics 101', 'is_active' => true],
            ['name' => 'Engineering Chemistry', 'code' => 'ECh -11011', 'description' => 'Eng Chemistry 101', 'is_active' => true],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(['name' => $subject['name']], $subject);
        }
    }
}
