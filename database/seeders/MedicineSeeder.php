<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineDetails;
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
                    'avatar' => 'uploads/defult-image/productImage.png',
                    'form' => 'tablet',
                    'dosage' => '500mg',
                    'unit' => 'mg',
                    'price' => 10.99,
                    'quantity' => 100,
                    'stock_quantity' => 50,
                ]
            ],
            [
                'title' => 'Ibuprofen',
                'brand' => 'Brand B',
                'generic_name' => 'Ibuprofen',
                'description' => 'Used to reduce fever, pain, and inflammation.',
                'status' => 'active',
                'details' => [
                    'avatar' => 'uploads/defult-image/productImage.png',
                    'form' => 'capsule',
                    'dosage' => '250mg',
                    'unit' => 'mg',
                    'price' => 12.50,
                    'quantity' => 50,
                    'stock_quantity' => 30,
                ]
            ],
            [
                'title' => 'Aspirin',
                'brand' => 'Brand C',
                'generic_name' => 'Acetylsalicylic acid',
                'description' => 'Used to reduce pain, fever, and inflammation.',
                'status' => 'active',
                'details' => [
                    'avatar' => 'uploads/defult-image/productImage.png',
                    'form' => 'tablet',
                    'dosage' => '100mg',
                    'unit' => 'mg',
                    'price' => 5.99,
                    'quantity' => 200,
                    'stock_quantity' => 150,
                ]
            ],
            [
                'title' => 'Amoxicillin',
                'brand' => 'Brand D',
                'generic_name' => 'Amoxicillin',
                'description' => 'Used to treat a variety of bacterial infections.',
                'status' => 'inactive',
                'details' => [
                    'avatar' => 'uploads/defult-image/productImage.png',
                    'form' => 'capsule',
                    'dosage' => '250mg',
                    'unit' => 'mg',
                    'price' => 15.99,
                    'quantity' => 120,
                    'stock_quantity' => 80,
                ]
            ],
            [
                'title' => 'Ciprofloxacin',
                'brand' => 'Brand E',
                'generic_name' => 'Ciprofloxacin',
                'description' => 'Used to treat various types of bacterial infections.',
                'status' => 'active',
                'details' => [
                    'avatar' => 'uploads/defult-image/productImage.png',
                    'form' => 'tablet',
                    'dosage' => '500mg',
                    'unit' => 'mg',
                    'price' => 14.50,
                    'quantity' => 200,
                    'stock_quantity' => 130,
                ]
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
        }
    }
}
