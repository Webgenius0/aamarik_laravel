<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

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

        //permission seeder
        $this->call(PermissionSeeder::class);

        Setting::create([
            'title' => 'myhealthneeds',
            'description' => "Lorem Ipsdam Doller"
        ]);
        $this->call(DoctorSeeder::class);


        // Call the CMS seeder
        $this->call(CMSseeder::class);

        $this->call([
            Sectionseeder::class,
            SectionCardseeder::class,
        ]);

        $this->call(FAQSeeder::class);

        //call the medicineSeeder
        $this->call(MedicineSeeder::class);

        //call the treatment
        $this->call(TreatmentSeeder::class);

        //call the delivery info
        $this->call(DeliveryInfoSeeder::class);
        //Start Your Quick Consultation
        $this->call(ConsultationSeeder::class);

        //call coupon seeder
        $this->call(CouponSeeder::class);

        //admin
        $admin = User::where('role', 'admin')->first();
        $admin->givePermissionTo(Permission::all());
    }
}
