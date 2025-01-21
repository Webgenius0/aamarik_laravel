<?php

namespace Database\Seeders;

use App\Models\Department;
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
        $departments = Department::where('status', 'active')->get();

        DB::table('users')->insert([
            [
                'name' => 'Willimas Jonshon',
                'email' => 'willimasjonshon@doctor.com',
                'department' => $departments->random()->department_name,
                'avatar' => 'uploads/defult-image/Team_1.png',
                'password' => Hash::make('12345678'),
                'role' => 'doctor',
                'created_at' => now(),
            ],
            [
                'name' => 'Tomas Murphy',
                'email' => 'tomasmurphy@doctor.com',
                'department' => $departments->random()->department_name,
                'avatar' => 'uploads/defult-image/Team_2.png',
                'password' => Hash::make('12345678'),
                'role' => 'doctor',
                'created_at' => now(),
            ],
            [
                'name' => 'Robert Fox',
                'email' => 'robertfox@doctor.com',
                'department' => $departments->random()->department_name,
                'avatar' => 'uploads/defult-image/Team_3.png',
                'password' => Hash::make('12345678'),
                'role' => 'doctor',
                'created_at' => now(),
            ],
            [
                'name' => 'Amalia nichole',
                'email' => 'amalianichole@doctor.com',
                'department' => $departments->random()->department_name,
                'avatar' => 'uploads/defult-image/Team_4.png',
                'password' => Hash::make('12345678'),
                'role' => 'doctor',
                'created_at' => now(),
            ],
        ]);
    }
}
