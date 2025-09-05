<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'Admin',
            'email' => 'john@example.com',
            'phone' => '0911',
            'password' => 'password',
            'is_active' => 1,
        ]);

        $user1->assignRole('admin');
        $user2 = User::create([
            'name' => 'teacher',
            'email' => 'teacher@example.com',
            'phone' => '0922',
            'password' => '2222',
            'is_active' => 1,
        ]);
        $user2->assignRole('teacher');

        $user3 = User::create([
            'name' => 'student',
            'email' => 'studnet@example.com',
            'phone' => '0933',
            'password' => Hash::make('password'),
            'is_active' => 1,
            ]);
        $user3->assignRole('student');
    }
}
