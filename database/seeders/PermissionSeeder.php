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
        // Coupon Permissions -*-
        Permission::create(['name' => 'coupon view']); // index
        Permission::create(['name' => 'coupon create']); // store
        Permission::create(['name' => 'coupon edit']); // edit and update
        Permission::create(['name' => 'coupon delete']); // delete

        //meeting permissions -*-
        Permission::create(['name' => 'meeting index']);
        Permission::create(['name' => 'meeting update']);
        Permission::create(['name' => 'meeting delete']);

        //role permissions -*-
        Permission::create(['name' => 'role and permission manage']);


        // Setting  Permissions -*-
        Permission::create(['name' => 'settings management']);

        //Customer permissions -*-
        Permission::create(['name' => 'customer management']);

        //Employee permissions -*-
        Permission::create(['name' => 'employee management']);

        //CMS permissions -*-
        Permission::create(['name' => 'cms management']);

        // Department permissions -*-
        Permission::create(['name' => 'department management']);

        // Department permissions -*-
        Permission::create(['name' => 'doctor management']);

        // Faq Permissions -*-
        Permission::create(['name' => 'faq management']);

        //medicine permissions -*-
        Permission::create(['name' => 'medicine view']); //index
        Permission::create(['name' => 'medicine create']); //store
        Permission::create(['name' => 'medicine edit']); //edit
        Permission::create(['name' => 'medicine delete']);

        // SocialMedia Permissions -*-
        Permission::create(['name' => 'socialMedia management']);

        // treatment Permissions -*-
        Permission::create(['name' => 'treatment management']);

        // order permissions
        Permission::create(['name' => 'order management']);
    }


}
