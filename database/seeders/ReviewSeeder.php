<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = Medicine::all();

        $reviewTexts = [
            "This medicine worked wonders for me!",
            "I noticed some side effects, but it helped overall.",
            "Great product! Highly recommended.",
            "It didn't work as expected, but no major issues.",
            "Fantastic quality and effective results.",
            "Mediocre performance; could have been better.",
            "Helped me recover quickly without side effects.",
            "Wouldn't recommend it due to adverse reactions.",
            "Effective and affordable!",
            "Not the best experience; caused minor discomfort."
        ];

        foreach ($medicines as $medicine) {
           $users = User::where('role', 'user')->get();
           foreach ($users as $user) {
               Review::create([
                   'medicine_id' => $medicine->id,
                   'user_id' => $user->id,
                   'rating'  => rand(1, 5),
                   'review'  => $reviewTexts[array_rand($reviewTexts)], // Assign random review text
            ]);
           }
        }
    }
}
