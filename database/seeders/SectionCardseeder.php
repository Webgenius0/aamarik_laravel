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
                'title' => 'Card 1 - Healthcare',
                'sub_title' => 'Subtitle for healthcare card 1',
                'avatar' => 'uploads/defult-image/meeting.png',
            ],
            [
                'section_id' => $healthcareSectionId,
                'title' => 'Card 2 - Healthcare',
                'sub_title' => 'Subtitle for healthcare card 2',
                'avatar' => 'uploads/defult-image/precipitation.png',
            ],
            [
                'section_id' => $healthcareSectionId,
                'title' => 'Card 2 - Healthcare',
                'sub_title' => 'Subtitle for healthcare card 2',
                'avatar' => 'uploads/defult-image/delivery.png',
            ],
            // Add  cards for process section
            [
                'section_id' => $processSectionId,
                'title' => 'Card 1 - Process',
                'sub_title' => 'Subtitle for process card 1',
                'avatar' => 'uploads/defult-image/answer_quick.png',
            ],
            [
                'section_id' => $processSectionId,
                'title' => 'Card 2 - Process',
                'sub_title' => 'Subtitle for process card 2',
                'avatar' => 'uploads/defult-image/treatment.png',
            ],
            [
                'section_id' => $processSectionId,
                'title' => 'Card 2 - Process',
                'sub_title' => 'Subtitle for process card 2',
                'avatar' => 'uploads/defult-image/first_delivery.png',
            ],
        ]);
    }
}
