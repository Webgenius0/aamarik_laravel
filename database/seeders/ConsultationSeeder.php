<?php

namespace Database\Seeders;

use App\Models\Consultation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert multiple sample consultations into the database
        Consultation::updateOrCreate(
            ['id' => 1],[
            'title' => 'Start Your Quick Consultation',
            'sub_description' => 'it’s 100% online and takes 2 minutes',
            'image' => null,
            'feature1' => 'Take our online questionnaire to see suitable treatments and prices',
            'feature2' => 'Choose your preferred ED treatment',
            'feature3' => "We'll deliver your order in discreet packaging as quickly as tomorrow",
            'button_name' => 'Get Started',
            'button_url' => 'https://example.com/consultation1',
        ]);
    }
}
