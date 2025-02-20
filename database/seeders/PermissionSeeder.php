<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dashboard Permissions
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'filter orders']);

        // Coupon Permissions
        Permission::create(['name' => 'coupon management']);
        Permission::create(['name' => 'coupon edit']);
        Permission::create(['name' => 'coupon delete']);

        //meeting permissions
        Permission::create(['name' => 'meeting management']);
        Permission::create(['name' => 'meeting delete']);

        //order permissions
        Permission::create(['name' => 'order management']);
        Permission::create(['name' => 'order edit']);
        Permission::create(['name' => 'order delete']);

        //role permissions

        // Setting  Permissions
        Permission::create(['name' => 'settings management']);

        //Customer permissions
        Permission::create(['name' => 'customer management']);

        //Employee permissions
        Permission::create(['name' => 'employee management']);

        //CMS permissions
        Permission::create(['name' => 'cms management']);

        // Department permissions
        Permission::create(['name' => 'department management']);

        // Department permissions
        Permission::create(['name' => 'doctor management']);

        // Faq Permissions
        Permission::create(['name' => 'faq management']);

        //medicine permissions
        Permission::create(['name' => 'medicine management']);
        Permission::create(['name' => 'medicine edit']);
        Permission::create(['name' => 'medicine update stock']);
        Permission::create(['name' => 'medicine delete']);

        // SocialMedia Permissions
        Permission::create(['name' => 'socialMedia management']);

        // treatment Permissions
        Permission::create(['name' => 'treatment management']);

    }


}
