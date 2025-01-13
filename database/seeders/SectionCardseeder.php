<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionCardseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, get the section IDs (assuming the section table is already seeded)
        $healthcareSectionId = DB::table('sections')->where('type', 'healthcare')->first()->id;
        $processSectionId = DB::table('sections')->where('type', 'process')->first()->id;

        DB::table('section_cards')->insert([
            [
                'section_id' => $healthcareSectionId,
                'title' => 'Online Consultation',
                'sub_title' => "Complete a quick health assessment online in just 2 minutes to provide us with the necessary information about your condition.",
                'avatar' => 'uploads/defult-image/meeting.png',
            ],
            [
                'section_id' => $healthcareSectionId,
                'title' => 'Genuine Prescription',
                'sub_title' => "Our licensed UK medical team will review your information and, if eligible, issue a prescription tailored to your needs.",
                'avatar' => 'uploads/defult-image/precipitation.png',
            ],
            [
                'section_id' => $healthcareSectionId,
                'title' => 'Discreet Delivery',
                'sub_title' => "We prioritize your privacy with discreet packaging, ensuring your treatment arrives securely within 24 hours.",
                'avatar' => 'uploads/defult-image/delivery.png',
            ],
            // Add  cards for process section
            [
                'section_id' => $processSectionId,
                'title' => 'Answer quick question',
                'sub_title' => 'No GP or pharmacy visits, just a quick online consultation',
                'avatar' => 'uploads/defult-image/answer_quick.png',
            ],
            [
                'section_id' => $processSectionId,
                'title' => 'Choose your treatment',
                'sub_title' => "Select-from our recommended uk licensed medicctions.",
                'avatar' => 'uploads/defult-image/treatment.png',
            ],
            [
                'section_id' => $processSectionId,
                'title' => 'Get it delivered fast',
                'sub_title' => 'We’ll direct delivered to you as quickly as tomorrow',
                'avatar' => 'uploads/defult-image/first_delivery.png',
            ],
        ]);
    }
}
