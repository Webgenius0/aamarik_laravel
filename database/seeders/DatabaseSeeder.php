<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Mr Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Mr user',
            'email' => 'user@user.com',
            'password' => Hash::make('12345678'),
        ]);
        //create department
        $this->call(DepartmentSeeder::class);

        $this->call(UserSeeder::class);

        Setting::create([
            'title' => 'pharmacy',
            'description' => "Lorem Ipsdam Doller"
        ]);
        $this->call(DoctorSeeder::class);


        // Call the CMSseeder
        $this->call(CMSseeder::class);

        $this->call([
            Sectionseeder::class,
            SectionCardseeder::class,
        ]);

        $this->call(FAQSeeder::class);

        //call the medicineSeeder
        $this->call(MedicineSeeder::class);

        //call the treatmetn
        $this->call(TreatmentSeeder::class);

        //call the deliver info
        $this->call(DeliveryInfoSeeder::class);
        //Start Your Quick Consultation
        $this->call(ConsultationSeeder::class);
    }
}
