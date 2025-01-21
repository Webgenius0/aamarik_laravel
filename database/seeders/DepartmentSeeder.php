<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example using Eloquent model
        $departments = [
            'Anesthesiology',
            'Cardiology',
            'Dermatology',
            'Emergency Medicine',
            'Endocrinology',
            'Family Medicine',
            'Gastroenterology',
            'General Surgery',
            'Gynecology and Obstetrics',
            'Hematology',
            'Infectious Disease',
            'Internal Medicine',
            'Nephrology',
            'Neurology',
            'Oncology',
            'Ophthalmology',
            'Orthopedics',
            'Otolaryngology (ENT)',
            'Pediatrics',
            'Plastic Surgery',
            'Psychiatry',
            'Pulmonology',
            'Radiology',
            'Rheumatology',
            'Urology'
        ];

        foreach ($departments as $department) {
            Department::create([
                'name' => $department
            ]);
        }
    }
}
