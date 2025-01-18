<?php

namespace Database\Seeders;

use App\Models\DeliveryInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryInfo::updateOrCreate(
            ['id' => 1],
            [
            'description' => "Due to increased demand, our clinical team may take up to 4 business days to review your suitability for the treatment and approve your prescription. Once approved, your order will be shipped using your selected delivery method.",
            'note' => "Orders approved after 4 PM on Friday will be dispatched on the next business day (Monday).",
            'option_name' => "Royal Mail Tracked™",
            'option_sub_description' => "Estimated delivery: 1–2 working days after prescription approval Signature required upon delivery",
            'option_value' => 3.95,
        ]);
    }
}
