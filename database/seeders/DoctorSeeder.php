<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Willimas Jonshon',
                'email' => 'willimasjonshon@doctor.com',
                'depertment' => 'Customer service',
                'avatar' => 'uploads/defult-image/Team_1.png',
                'password' => Hash::make('12345678'),
                'role' => 'doctor',
                'created_at' => now(),
            ],
            [
                'name' => 'Tomas Murphy',
                'email' => 'tomasmurphy@doctor.com',
                'depertment' => 'Customer service',
                'avatar' => 'uploads/defult-image/Team_2.png',
                'password' => Hash::make('12345678'),
                'role' => 'doctor',
                'created_at' => now(),
            ],
            [
                'name' => 'Robert Fox',
                'email' => 'robertfox@doctor.com',
                'depertment' => 'Contract Tracer',
                'avatar' => 'uploads/defult-image/Team_3.png',
                'password' => Hash::make('12345678'),
                'role' => 'doctor',
                'created_at' => now(),
            ],
            [
                'name' => 'Amalia nichole',
                'email' => 'amalianichole@doctor.com',
                'depertment' => 'Nurse Aide',
                'avatar' => 'uploads/defult-image/Team_4.png',
                'password' => Hash::make('12345678'),
                'role' => 'doctor',
                'created_at' => now(),
            ],
        ]);
    }
}
