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

        // UserUpdateController Permissions
        Permission::create(['name' => 'edit profile']);
        Permission::create(['name' => 'change password']);

        // SettingController Permissions
        Permission::create(['name' => 'update settings']);
        Permission::create(['name' => 'view settings']);
        Permission::create(['name' => 'update home page']);
        Permission::create(['name' => 'view home page']);
        Permission::create(['name' => 'update stripe settings']);
        Permission::create(['name' => 'view stripe settings']);
        Permission::create(['name' => 'update microsoft settings']);
        Permission::create(['name' => 'view microsoft settings']);
        Permission::create(['name' => 'update zoom settings']);
        Permission::create(['name' => 'view zoom settings']);

        // SocialMediaController Permissions
        Permission::create(['name' => 'view social media settings']);
        Permission::create(['name' => 'manage social media settings']);
        Permission::create(['name' => 'create social media']);
        Permission::create(['name' => 'edit social media']);
        Permission::create(['name' => 'update social media']);
        Permission::create(['name' => 'delete social media']);

        // FaqController Permissions
        Permission::create(['name' => 'view faqs']);
        Permission::create(['name' => 'create faq']);
        Permission::create(['name' => 'update faq']);
        Permission::create(['name' => 'delete faq']);
        Permission::create(['name' => 'update faq status']);

        // CMSController Permissions
        Permission::create(['name' => 'update cms settings']);
        Permission::create(['name' => 'update home section']);
        Permission::create(['name' => 'update personalized cms']);
        Permission::create(['name' => 'update confidential cms section']);
        Permission::create(['name' => 'update working process cms section']);
        Permission::create(['name' => 'update doctor section']);

        // DoctorController Permissions
        Permission::create(['name' => 'view doctors']);
        Permission::create(['name' => 'add doctor']);
        Permission::create(['name' => 'edit doctor']);
        Permission::create(['name' => 'update doctor']);
        Permission::create(['name' => 'delete doctor']);
        Permission::create(['name' => 'view departments']);
        Permission::create(['name' => 'manage departments']);

        // DepartmentController Permissions
        Permission::create(['name' => 'view departments']);
        Permission::create(['name' => 'create department']);
        Permission::create(['name' => 'edit department']);
        Permission::create(['name' => 'update department']);
        Permission::create(['name' => 'delete department']);

        // MedicineController Permissions
        Permission::create(['name' => 'view medicines']);
        Permission::create(['name' => 'add medicine']);
        Permission::create(['name' => 'edit medicine']);
        Permission::create(['name' => 'update medicine']);
        Permission::create(['name' => 'delete medicine']);
        Permission::create(['name' => 'update medicine status']);

        // TreatMentController Permissions
        Permission::create(['name' => 'view treatments']);
        Permission::create(['name' => 'add treatment']);
        Permission::create(['name' => 'edit treatment']);
        Permission::create(['name' => 'update treatment']);
        Permission::create(['name' => 'delete treatment']);
        Permission::create(['name' => 'update treatment status']);
        Permission::create(['name' => 'view treatment list']);

        // CouponController Permissions
        Permission::create(['name' => 'view coupons']);
        Permission::create(['name' => 'create coupon']);
        Permission::create(['name' => 'edit coupon']);
        Permission::create(['name' => 'update coupon']);
        Permission::create(['name' => 'delete coupon']);
        Permission::create(['name' => 'update coupon status']);

        // OrderManagementController Permissions
        Permission::create(['name' => 'view orders']);
        Permission::create(['name' => 'update order status']);
        Permission::create(['name' => 'view order details']);
        Permission::create(['name' => 'delete order']);
        Permission::create(['name' => 'update order note']);
        Permission::create(['name' => 'update order address']);

        // CustomerManagementController Permissions
        Permission::create(['name' => 'view customers']);
        Permission::create(['name' => 'view customer details']);
        Permission::create(['name' => 'view customer order sheet']);
        Permission::create(['name' => 'view customer order details']);

        // EmployeeManagementController Permissions
        Permission::create(['name' => 'view employees']);
        Permission::create(['name' => 'add employee']);
        Permission::create(['name' => 'edit employee']);
        Permission::create(['name' => 'update employee']);

        // MeetingController Permissions
        Permission::create(['name' => 'view meetings']);
        Permission::create(['name' => 'update meeting status']);
        Permission::create(['name' => 'delete meeting']);
    }


}
