<?php

namespace Database\Seeders;

use App\Models\AboutTreatment;
use App\Models\DetailsItems;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\TreatmentDetails;
use App\Models\TreatmentFaq;
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
                'avatar' => 'uploads/defult-image/treatment1.png',
                'categories' => [
                    ['icon' => 'uploads/defult-image/treatment1.png', 'title' => 'Hair Transplant'],
                    ['icon' => 'uploads/defult-image/treatment2.png', 'title' => 'Scalp Treatment'],
                    ['icon' => 'uploads/defult-image/treatment3.png', 'title' => 'Scalp Treatment'],
                ],
                'details' => [
                    ['title' => 'PRP Therapy', 'avatar' => 'prp.jpg'],
                ],
                'detail_items' => [
                    ['title' => 'Hair Growth Techniques'],
                    ['title' => 'Dandruff Control'],
                ],
                'about' => [
                    'title' => 'Understanding Hair Loss',
                    'avatar' => 'hair_about.jpg',
                    'short_description' => 'Hair loss can be treated with advanced techniques like PRP and hair transplant.',
                ],
                'faqs' => [
                    ['question' => 'Is hair transplant permanent?', 'answer' => 'Yes, it is a long-term solution.'],
                    ['question' => 'Does PRP therapy hurt?', 'answer' => 'No, it is a minimally invasive procedure.'],
                ],
                'medicines' => ['Minoxidil', 'Finasteride'],
                'assessments' => [
                    [
                        'question' => 'Do you experience hair thinning?',
                        'option' => 'Yes',
                        'note' => 'Check patient scalp condition.',
                    ],
                ],
            ],
            [
                'name' => 'Weight Loss',
                'avatar' => 'uploads/defult-image/treatment2.png',
                'categories' => [
                    ['icon' => 'icon_weight1.png', 'title' => 'Diet Plans'],
                    ['icon' => 'icon_weight2.png', 'title' => 'Exercise Programs'],
                ],
                'details' => [
                    ['title' => 'Keto Diet', 'avatar' => 'keto.jpg'],
                    ['title' => 'Cardio Workouts', 'avatar' => 'cardio.jpg'],
                ],
                'detail_items' => [
                    ['title' => 'Daily Meal Planner'],
                    ['title' => 'Strength Training'],
                ],
                'about' => [
                    'title' => 'Overview of Weight Loss',
                    'avatar' => 'weight_about.jpg',
                    'short_description' => 'Weight loss programs focus on healthy eating and exercise.',
                ],
                'faqs' => [
                    ['question' => 'Are crash diets safe?', 'answer' => 'No, they can harm your metabolism.'],
                ],
                'medicines' => ['Orlistat', 'Phentermine'],
                'assessments' => [
                    [
                        'question' => 'Do you have a daily exercise routine?',
                        'option' => 'No',
                        'note' => 'Suggest low-impact activities for beginners.',
                    ],
                ],
            ],
            [
                'name' => 'Erectile Dysfunction',
                'avatar' => 'uploads/defult-image/treatment3.png',
                'categories' => [
                    ['icon' => 'icon_ed1.png', 'title' => 'Psychological Therapy'],
                    ['icon' => 'icon_ed2.png', 'title' => 'Medication'],
                ],
                'details' => [
                    ['title' => 'Hormonal Therapy', 'avatar' => 'hormonal.jpg'],
                ],
                'detail_items' => [
                    ['title' => 'Lifestyle Changes'],
                ],
                'about' => [
                    'title' => 'Understanding Erectile Dysfunction',
                    'avatar' => 'ed_about.jpg',
                    'short_description' => 'ED can be treated with counseling and medication.',
                ],
                'faqs' => [
                    ['question' => 'Can ED be cured permanently?', 'answer' => 'It depends on the cause. Consult a specialist.'],
                ],
                'medicines' => ['Sildenafil', 'Tadalafil'],
                'assessments' => [
                    [
                        'question' => 'Do you experience difficulty maintaining an erection?',
                        'option' => 'Yes',
                        'note' => 'Check for underlying health conditions.',
                    ],
                ],
            ],
            [
                'name' => 'Sexual Health',
                'avatar' => 'uploads/defult-image/treatment4.png',
                'categories' => [
                    ['icon' => 'icon_sh1.png', 'title' => 'Reproductive Health'],
                    ['icon' => 'icon_sh2.png', 'title' => 'Sexual Wellness'],
                ],
                'details' => [
                    ['title' => 'STI Prevention', 'avatar' => 'sti.jpg'],
                ],
                'detail_items' => [
                    ['title' => 'Safe Practices'],
                ],
                'about' => [
                    'title' => 'Improving Sexual Health',
                    'avatar' => 'sh_about.jpg',
                    'short_description' => 'Sexual health encompasses physical, emotional, and social well-being.',
                ],
                'faqs' => [
                    ['question' => 'What is safe sex?', 'answer' => 'Safe sex involves practices that protect against STIs and unplanned pregnancies.'],
                ],
                'medicines' => ['Antibiotics', 'Antiviral Medications'],
                'assessments' => [
                    [
                        'question' => 'Do you experience frequent infections?',
                        'option' => 'Yes',
                        'note' => 'Discuss safe practices and regular check-ups.',
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
            $medicines = Medicine::inRandomOrder()->take(4)->get();
            foreach ($medicines as $medicine) {
                TreatmentMedicine::create([
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
