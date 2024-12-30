<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name' => 'Poli Umum',
            'est_duration_mins' => 30,
        ]);
        Service::create([
            'name' => 'Poli Gigi',
            'est_duration_mins' => 20,
        ]);
        Service::create([
            'name' => 'Poli Kandungan',
            'est_duration_mins' => 40,
        ]);
    }
}
