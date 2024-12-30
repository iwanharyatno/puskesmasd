<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@puskesmas.com',
            'password' => bcrypt('admin123'),
            'role' => 'Admin'
        ]);

        User::create([
            'name' => 'Lorem Ipsum',
            'email' => 'lorem@lorem.com',
            'nik' => '1234567890123456',
            'password' => bcrypt('lorem123'),
            'phone' => '081234567890',
            'birth_date' => '2000-01-01',
            'address' => 'Jl. Lorem Ipsum Dolor Sit Amet',
        ]);

        User::create([
            'name' => 'Adispicing Elit',
            'email' => 'elit@lorem.com',
            'nik' => '1234567890123457',
            'password' => bcrypt('elit123'),
            'phone' => '081234567891',
            'birth_date' => '2000-01-02',
            'address' => 'Jl. Adispicing Elit Sed Do Eiusmod',
        ]);
    }
}
