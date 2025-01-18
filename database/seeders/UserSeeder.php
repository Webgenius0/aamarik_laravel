<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Esther Howard',
            'email' => 'estherhoward@user.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Ronald Richards',
            'email' => 'ronaldrichards@user.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
