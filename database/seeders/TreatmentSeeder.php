<?php

namespace Database\Seeders;

use App\Models\AboutTreatment;
use App\Models\Assessment;
use App\Models\DetailsItems;
use App\Models\Medicine;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\TreatmentDetails;
use App\Models\TreatmentFaq;
use App\Models\TreatmentMedicines;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $treatments = [
            [
                'name' => 'Hair Loss',
                'avatar' => null ,
                'categories' => [
                    ['icon' => null, 'title' => 'Hair Transplant'],
                    ['icon' => null, 'title' => 'Scalp Treatment'],
                    ['icon' => null, 'title' => 'Scalp Treatment'],
                ],
                'details' => [
                    ['title' => 'Hair Loss Prevention Treatment', 'avatar' => null],
                ],
                'detail_items' => [
                    ['title' => 'Clinically proven treatments for effective hair regrowth'],
                    ['title' => 'Discreet, next-day delivery to your door'],
                    ['title' => 'Answer a few quick questions to find the best hair loss solution for your needs'],
                ],
                'about' => [
                    'title' => 'About Hair Loss',
                    'avatar' => null,
                    'short_description' => 'Our medical team is UK-based and registered with the General Medical Council and General Pharmaceutical Council.',
                ],
                'faqs' => [
                    ['question' => 'What is hair loss?', 'answer' => 'Hair loss, or alopecia, is the partial or complete loss of hair, often caused by genetics, aging, hormonal changes, stress, or medical conditions.'],
                    ['question' => 'Does PRP therapy hurt?', 'answer' => 'No, it is a minimally invasive procedure.'],
                ],
                'assessments' => [
                    [
                        'question' => 'What is your biological sex?',
                        'option1'   => 'Male',
                        'option2'   => 'Female',
                        'option3'   => 'Others',
                        'option4'   => null,
                        'note'     => null,
                        'answer'   => null,
                    ],
                    [
                        'question' => 'Do you believe you have the ability to make healthcare decisions for yourself?',
                        'option1'   => 'Yes',
                        'option2'   => 'No',
                        'option3'   => null,
                        'option4'   => null,
                        'note'     => "Give us additional information please",
                        'answer'   => null,
                    ],
                    [
                        'question' => 'Are you taking any medications currently? This includes both prescription-only and over-the-counter medications, as well as homoeopathic remedies.',
                        'option1'   => 'Yes',
                        'option2'   => 'No',
                        'option3'   => null,
                        'option4'   => null,
                        'note'     => null,
                        'answer'   => null,
                    ],
                    [
                        'question' => 'How much do you weight?',
                        'option1'   => null,
                        'option2'   => null,
                        'option3'   => null,
                        'option4'   => null,
                        'note'     => "Kilograms",
                        'answer'   => null,
                    ],[
                        'question' => 'What is your Blood Pressure?',
                        'option1' => 'Low (below 90/60)',
                        'option2' => 'Normal (between 90/60 ad 140/90)',
                        'option3' => 'Others',
                        'option4'   => null,
                        'note' => "High (above 140/90)",
                        'answer'   => null,
                    ],
                ],
            ],
        ];

        foreach ($treatments as $treatmentData) {
            $treatment = Treatment::create([
                'name' => $treatmentData['name'],
                'avatar' => $treatmentData['avatar'],
            ]);

            // Categories
            foreach ($treatmentData['categories'] as $category) {
                TreatmentCategory::create([
                    'treatment_id' => $treatment->id,
                    'icon' => $category['icon'],
                    'title' => $category['title'],
                ]);
            }

            // Details
            foreach ($treatmentData['details'] as $detail) {
                TreatmentDetails::create([
                    'treatment_id' => $treatment->id,
                    'title' => $detail['title'],
                    'avatar' => $detail['avatar'],
                ]);
            }

            // Detail Items
            foreach ($treatmentData['detail_items'] as $item) {
                DetailsItems::create([
                    'treatment_id' => $treatment->id,
                    'title' => $item['title'],
                ]);
            }

            // About
            AboutTreatment::create(array_merge($treatmentData['about'], ['treatment_id' => $treatment->id]));

            // FAQs
            foreach ($treatmentData['faqs'] as $faq) {
                TreatmentFaq::create(array_merge($faq, ['treatment_id' => $treatment->id]));
            }

            // Medicines
            $medicines = Medicine::where('status','active')->inRandomOrder()->take(4)->get();
            foreach ($medicines as $medicine) {
                TreatmentMedicines::create([
                    'treatment_id' => $treatment->id,
                    'medicine_id' => $medicine->id,
                ]);
            }

            // Assessments
            foreach ($treatmentData['assessments'] as $assessment) {
                Assessment::create(array_merge($assessment, ['treatment_id' => $treatment->id]));
            }
        }
    }
}
