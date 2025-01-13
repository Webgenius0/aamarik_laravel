<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Sectionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sections')->insert([
            ['title' => "Quick, Confidential, and Reliable Healthcare", 'type' => 'healthcare'],
            ['title' => 'Our Working Process', 'type' => 'process'],
        ]);
    }
}
