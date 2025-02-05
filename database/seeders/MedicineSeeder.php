<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineDetails;
use App\Models\MedicineFeature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inserting real data into the 'medicines' table
        $medicines = [
            [
                'title' => 'Paracetamol',
                'brand' => 'Brand A',
                'generic_name' => 'Acetaminophen',
                'description' => 'Used to relieve pain and reduce fever.',
                'status' => 'active',
                'details' => [
                    'avatar' => null,
                    'form' => 'tablet',
                    'dosage' => '500mg',
                    'unit' => 'mg',
                    'price' => 10.99,
                    'quantity' => 100,
                    'stock_quantity' => 50,
                ],
                'features' => [
                    'Relieves pain quickly',
                    'Reduces fever effectively',
                    'Suitable for all ages',
                ],
            ],
            [
                'title' => 'Ibuprofen',
                'brand' => 'Brand B',
                'generic_name' => 'Ibuprofen',
                'description' => 'Used to reduce fever, pain, and inflammation.',
                'status' => 'active',
                'details' => [
                    'avatar' => null,
                    'form' => 'capsule',
                    'dosage' => '250mg',
                    'unit' => 'mg',
                    'price' => 12.50,
                    'quantity' => 50,
                    'stock_quantity' => 30,
                ],
                'features' => [
                    'Reduces inflammation',
                    'Effective for muscle pain',
                    'Fast-acting relief',
                ],
            ],
            [
                'title' => 'Aspirin',
                'brand' => 'Brand C',
                'generic_name' => 'Acetylsalicylic acid',
                'description' => 'Used to reduce pain, fever, and inflammation.',
                'status' => 'active',
                'details' => [
                    'avatar' => null,
                    'form' => 'tablet',
                    'dosage' => '100mg',
                    'unit' => 'mg',
                    'price' => 5.99,
                    'quantity' => 200,
                    'stock_quantity' => 150,
                ],
                'features' => [
                    'Prevents blood clots',
                    'Effective for headaches',
                    'Relieves minor pain',
                ],
            ],
            [
                'title' => 'Amoxicillin',
                'brand' => 'Brand D',
                'generic_name' => 'Amoxicillin',
                'description' => 'Used to treat a variety of bacterial infections.',
                'status' => 'inactive',
                'details' => [
                    'avatar' => null,
                    'form' => 'capsule',
                    'dosage' => '250mg',
                    'unit' => 'mg',
                    'price' => 15.99,
                    'quantity' => 120,
                    'stock_quantity' => 80,
                ],
                'features' => [
                    'Prevents blood clots',
                    'Effective for headaches',
                    'Relieves minor pain',
                ],
            ],
            [
                'title' => 'Ciprofloxacin',
                'brand' => 'Brand E',
                'generic_name' => 'Ciprofloxacin',
                'description' => 'Used to treat various types of bacterial infections.',
                'status' => 'active',
                'details' => [
                    'avatar' => null,
                    'form' => 'tablet',
                    'dosage' => '500mg',
                    'unit' => 'mg',
                    'price' => 14.50,
                    'quantity' => 60,
                    'stock_quantity' => 130,
                ],
                'features' => [
                    'Prevents blood clots',
                    'Effective for headaches',
                    'Relieves minor pain',
                ],
            ],
        ];

        // Loop through the array and insert the data into the 'medicines' table
        foreach ($medicines as $medicine) {
            // Create medicine record
            $medicineRecord = Medicine::create([
                'title' => $medicine['title'],
                'brand' => $medicine['brand'],
                'generic_name' => $medicine['generic_name'],
                'description' => $medicine['description'],
                'status' => $medicine['status'],
            ]);



            // Insert related medicine details
            $medicineDetails = $medicine['details'];
            $medicineDetails['medicine_id'] = $medicineRecord->id; // Link to the created medicine

            // Create the medicine details record
            MedicineDetails::create($medicineDetails);

            // Insert related medicine features
            if (!empty($medicine['features'])) {
                foreach ($medicine['features'] as $feature) {
                    MedicineFeature::create([
                        'medicine_id' => $medicineRecord->id,
                        'feature' => $feature,
                    ]);
                }
            }
        }
    }
}
