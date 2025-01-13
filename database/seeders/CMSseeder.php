<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CMSseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example CMS data to seed
        $cmsPages = [
            [
                'title' => 'Pharmacy',
                'sub_title' => 'Operational bottlenecks, frequent billing errors, and mismanaged schedules can lead to revenue loss and frustrated patients.',
                'description' => null,
                'avatar' => 'uploads/defult-image/home_banner.png', // You might need to upload this file to the public directory
                'button_name' => 'Start Consultation',
                'button_url' => 'https://example.com/start',
                'type' => 'banner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Personalized Healthcare, Just a Click Away',
                'sub_title' => null,
                'description' => "Experience healthcare that's designed around your needs. Access safe, approved treatments without the hassle of waiting rooms. Get genuine prescriptions delivered discreetly to your door, so you can focus on what matters most — your health.",
                'avatar' => 'uploads/defult-image/personalized.png', // You might need to upload this file to the public directory
                'button_name' => null,
                'button_url' => null,
                'type' => 'personalized',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Insert data into the CMS pages table
        DB::table('c_m_s')->insert($cmsPages);
    }
}
