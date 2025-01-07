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
                'title' => 'Welcome to Our Website',
                'sub_title' => 'Your journey begins here.',
                'description' => null,
                'avatar' => 'uploads/defult-image/home_banner.png', // You might need to upload this file to the public directory
                'button_name' => 'Get Started',
                'button_url' => 'https://example.com/start',
                'type' => 'banner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'About Us',
                'sub_title' => null,
                'description' => 'We are dedicated to providing quality content and services to our users.',
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
