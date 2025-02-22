<?php

//use App\Http\Controllers\API\Frontend\CMSController;
use App\Http\Controllers\Web\Backend\MeetingManagement\MeetingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Backend\VerseController;
use App\Http\Controllers\Web\Backend\SettingController;
use App\Http\Controllers\Web\Backend\settings\MailSettingsController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\UserUpdateController;
use App\Http\Controllers\Web\Backend\SocialMediaController;
use App\Http\Controllers\Web\Backend\CMSController;
use App\Http\Controllers\Web\Backend\DoctorController;
use App\Http\Controllers\Web\Backend\MedicineController;
use App\Http\Controllers\Web\Backend\TreatMentController;
use App\Http\Controllers\Web\Backend\coupon\CouponController;
use Illuminate\Support\Facades\Mail;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/orders/filter', [DashboardController::class, 'sellingFiltering'])->name('orders.filter');
//Route::middleware(['role_or_permission:filter orders'])->get('/dashboard/filter', [DashboardController::class, 'sellingFiltering']);

// Edit Profile
Route::get('profile', [DashboardController::class, 'editProfile'])->name('admin.edit.profile');
Route::post('edit-profile', [UserUpdateController::class, 'update'])->name('admin.edit.profile.update');
Route::post('change-password', [UserUpdateController::class, 'userPasswordUpdate'])->name('admin.change.password');

// Setting
Route::middleware(['role_or_permission:settings management'])->post('setting-update', [SettingController::class, 'update'])->name('admin.setting.update');
Route::middleware(['role_or_permission:settings management'])->get('setting', [SettingController::class, 'index'])->name('admin.setting');
//Route::get('/mail/setting', [MailSettingsController::class, 'index'])->name('admin.mail.setting');


// Home Page Content Update
Route::middleware(['role_or_permission:settings management'])->get('home-page-update', [SettingController::class, 'homePageContents'])->name('admin.home.page.edit');
Route::middleware(['role_or_permission:settings management'])->post('home-page-update', [SettingController::class, 'homePageUpdate'])->name('admin.home.page.update');

//stripe Setting Controller
Route::middleware(['role_or_permission:settings management'])->get('mail/setting', [SettingController::class, 'mailSetting'])->name('mail.setting');
Route::middleware(['role_or_permission:settings management'])->post('mail/setting', [SettingController::class, 'mailSettingUpdate'])->name('mail.setting.update');

//Mail Setting Controller
Route::middleware(['role_or_permission:settings management'])->get('stripe/setting', [SettingController::class, 'stripeSetting'])->name('stripe.setting');
Route::middleware(['role_or_permission:settings management'])->post('stripe/setting', [SettingController::class, 'stripeSettingUpdate'])->name('stripe.setting.update');
//Microsoft Setting Controller
Route::middleware(['role_or_permission:settings management'])->get('microsoft/setting', [SettingController::class, 'microsoftSetting'])->name('microsoft.setting');
Route::middleware(['role_or_permission:settings management'])->post('microsoft/setting', [SettingController::class, 'microsoftSettingUpdate'])->name('microsoft.setting.update');

//zoom Setting Controller
Route::middleware(['role_or_permission:settings management'])->get('zoom/setting', [SettingController::class, 'zoomSetting'])->name('zoom.setting');
Route::middleware(['role_or_permission:settings management'])->post('zoom/setting', [SettingController::class, 'zoomSettingUpdate'])->name('zoom.setting.update');
/* // Social Media
Route::get('social-media/setting', [SocialMediaController::class, 'index'])->name('social.setting'); */

// Dynamic Page
Route::middleware(['role_or_permission:settings management'])->get('/dynamic-page/index', [SettingController::class, 'additional'])->name('dynamic.page.index');
Route::middleware(['role_or_permission:settings management'])->get('/dynamic-page/create', [SettingController::class, 'dynamicPageCreate'])->name('dynamic.page.create');
Route::middleware(['role_or_permission:settings management'])->post('/dynamic-page/create', [SettingController::class, 'dynamicPageStore'])->name('dynamic.page.store');
Route::middleware(['role_or_permission:settings management'])->get('/dynamic-page/update/{id}', [SettingController::class, 'dynamicPageEdit'])->name('dynamic.page.edit');
Route::middleware(['role_or_permission:settings management'])->post('/dynamic-page/update/{id}', [SettingController::class, 'dynamicPageUpdate'])->name('dynamic.page.update');
Route::middleware(['role_or_permission:settings management'])->get('/dynamic-page/delete/{id}', [SettingController::class, 'dynamicPageDelete'])->name('dynamic.page.delete');



// social media
Route::middleware(['role_or_permission:socialMedia management'])->get('social-media/setting', [SocialMediaController::class, 'index'])->name('social.setting');
Route::middleware(['role_or_permission:socialMedia management'])->get('social-media/media', [SocialMediaController::class, 'index'])->name('social.media');
Route::middleware(['role_or_permission:socialMedia management'])->get('social-media/media/create', [SocialMediaController::class, 'create'])->name('social.media.create');
Route::middleware(['role_or_permission:socialMedia management'])->post('social-media/media', [SocialMediaController::class, 'store'])->name('social.media.store');
Route::middleware(['role_or_permission:socialMedia management'])->get('social-media/media/edit/{id}', [SocialMediaController::class, 'edit'])->name('social.media.edit');
Route::middleware(['role_or_permission:socialMedia management'])->put('social-media/media/{id}', [SocialMediaController::class, 'update'])->name('social.media.update');
Route::middleware(['role_or_permission:socialMedia management'])->get('social-media/media/status/{id}', [SocialMediaController::class, 'status'])->name('social.media.status');
Route::middleware(['role_or_permission:socialMedia management'])->delete('social-media/media/{id}', [SocialMediaController::class, 'destroy'])->name('social.media.destroy');


//FAQ
Route::controller(\App\Http\Controllers\Web\Backend\FaqController::class)->group(function (){
    Route::middleware(['role_or_permission:faq management'])->get('faqs','index')->name('faq.index');
    Route::middleware(['role_or_permission:faq management'])->post('faqs/store', 'store')->name('faq.store');
    Route::middleware(['role_or_permission:faq management'])->get('faq/status/update/{id}','updateStatus')->name('update.status.faq');
    Route::middleware(['role_or_permission:faq management'])->get('faqs/edit/{id}', 'edit')->name('faq.edit');
    Route::middleware(['role_or_permission:faq management'])->put('faqs/update/{id}', 'update')->name('faq.update');
    Route::middleware(['role_or_permission:faq management'])->delete('faqs/destroy/{id}','destroy')->name('faq.destroy');
});

//cms banner
Route::middleware(['role_or_permission:cms management'])->get('/settings/banner',[ CMSController::class ,'banner'])->name('banner');
Route::middleware(['role_or_permission:cms management'])->get('/cms/home-section',[ CMSController::class ,'homeSection'])->name('home.section');
Route::middleware(['role_or_permission:cms management'])->put('/cms', [CmsController::class, 'update'])->name('cms.update');
Route::middleware(['role_or_permission:cms management'])->get('/cms/personalized', [CmsController::class, 'showPersonalized'])->name('cms.personalized');
Route::middleware(['role_or_permission:cms management'])->put('/cms/personalized', [CmsController::class, 'personalized'])->name('cms.personalized');

//cms section

Route::middleware(['role_or_permission:cms management'])->get('/cms/confediential', [CmsController::class, 'homeSection'])->name('cms.confediential');
Route::middleware(['role_or_permission:cms management'])->put('/cms/update', [CmsController::class, 'updateSection'])->name('section.update');
Route::middleware(['role_or_permission:cms management'])->put('/cms/working-process', [CmsController::class, 'updateWorkingProcess'])->name('cms.working.process');
//doctor section
//Route::get('/cms/doctor-section', [CmsController::class, 'doctorSection'])->name('cms.doctor.section');
Route::middleware(['role_or_permission:doctor management'])->get('/doctor', [DoctorController::class, 'index'])->name('doctor.index');
Route::middleware(['role_or_permission:doctor management'])->post('/doctor-add', [DoctorController::class, 'store'])->name('doctor.store');
Route::middleware(['role_or_permission:doctor management'])->get('/doctor-create', [DoctorController::class, 'create'])->name('doctor.create-form');
Route::middleware(['role_or_permission:doctor management'])->get('/doctor-edit/{doctor}', [DoctorController::class, 'edit'])->name('doctor.edit');
Route::middleware(['role_or_permission:doctor management'])->put('/doctor-update/{id}', [DoctorController::class, 'update'])->name('doctor.update');
Route::middleware(['role_or_permission:doctor management'])->delete('/doctor-delete/{id}', [DoctorController::class, 'destroy'])->name('doctor.delete');


Route::middleware(['role_or_permission:doctor management'])->get('/department', [DoctorController::class, 'getDeparments'])->name('doctor.department');



//Department
Route::controller(\App\Http\Controllers\Web\Backend\DepartmentController::class)->group(function (){
    Route::middleware(['role_or_permission:department management'])->get('/departments','index')->name('department.index');
    Route::middleware(['role_or_permission:department management'])->get('/department/create', 'create')->name('department.create');
    Route::middleware(['role_or_permission:department management'])->post('/department/store', 'store')->name('department.store');
    Route::middleware(['role_or_permission:department management'])->get('/department/edit/{id}', 'edit')->name('department.edit');
    Route::middleware(['role_or_permission:department management'])->post('/department/update/{id}', 'update')->name('department.update');
    Route::middleware(['role_or_permission:department management'])->delete('/department/delete/{id}', 'destroy')->name('department.delete');
});


//Medicine section
Route::controller(MedicineController::class)->group(function () {
    Route::middleware(['role_or_permission:medicine view'])->get('/medicine', 'index')->name('medicine.index');
    Route::middleware(['role_or_permission:medicine create'])->post('/medicine', 'store')->name('medicine.store');
    Route::middleware(['role_or_permission:medicine edit'])->get('/medicine/edit/{id}', 'edit')->name('medicine.edit');
    Route::middleware(['role_or_permission:medicine edit'])->post('/medicine/update/{id}', 'update')->name('medicine.update');
    Route::middleware(['role_or_permission:medicine delete'])->delete('/medicine/delete/{id}', 'destroy')->name('medicine.destroy');
    Route::middleware(['role_or_permission:medicine edit'])->get('/medicine/status/update/{id}', 'updateStatus')->name('medicine.status.update');
});

//treatment section
Route::controller(TreatMentController::class)->group(function () {
    Route::middleware(['role_or_permission:treatment management'])->get('/treatment', 'index')->name('treatment.index');
    Route::middleware(['role_or_permission:treatment management'])->post('/treatment', 'store')->name('treatment.store');
    Route::middleware(['role_or_permission:treatment management'])->get('/treatment/edit/{id}', 'edit')->name('treatment.edit');
    Route::middleware(['role_or_permission:treatment management'])->post('/treatment/update/{id}', 'update')->name('treatment.update');
    Route::middleware(['role_or_permission:treatment management'])->delete('/treatment/delete/{id}', 'destroy')->name('treatment.destroy');
    Route::middleware(['role_or_permission:treatment management'])->get('/treatment/status/update/{id}', 'updateStatus')->name('treatment.status.update');
    //for list
    Route::middleware(['role_or_permission:treatment management'])->get('/treatment/list', 'treatmentList')->name('treatment.list');
});

//coupons
Route::controller(CouponController::class)->group(function () {
    Route::middleware(['role_or_permission:coupon view'])->get('/coupon', 'index')->name('coupon.index');
    Route::middleware(['role_or_permission:coupon create'])->post('/coupon', 'store')->name('coupon.store');
    Route::middleware(['role_or_permission:coupon edit'])->get('/coupon/edit/{id}', 'edit')->name('coupon.edit');
    Route::middleware(['role_or_permission:coupon edit'])->put('/coupon/update/{id}', 'update')->name('coupon.update');
    Route::middleware(['role_or_permission:coupon delete'])->delete('/coupon/delete/{id}', 'destroy')->name('coupon.destroy');
    Route::middleware(['role_or_permission:coupon edit'])->get('/coupon/status/update/{id}', 'updateStatus')->name('coupon.status.update');
});

//! Route for order management
Route::controller(\App\Http\Controllers\Web\Backend\Order\OrderManagementController::class)->group(function () {
    Route::middleware(['role_or_permission:order management'])->get('/orders', 'index')->name('orders.index');
    Route::middleware(['role_or_permission:order management'])->post('/order/status/update/{id}', 'updateStatus')->name('order.status.update');
    Route::middleware(['role_or_permission:order management'])->get('/order/details/{id}', 'show')->name('order.details');
    Route::middleware(['role_or_permission:order management'])->delete('/order/delete/{id}', 'destroy')->name('order.delete');
    Route::middleware(['role_or_permission:order management'])->post('/order/note/update/{id}', 'updateNote')->name('order.note.update');
    Route::middleware(['role_or_permission:order management'])->post('/order/billing-address/update/{id}', 'updateAddress')->name('order.address.update');
});

//! Route for customer Management
Route::controller(\App\Http\Controllers\Web\Backend\user\CustomerManagementController::class)->group(function () {
    Route::middleware(['role_or_permission:customer management'])->get('/customers', 'index')->name('customer.index');
    Route::middleware(['role_or_permission:customer management'])->get('/customer/details/{id}', 'show')->name('customer.show');
    Route::middleware(['role_or_permission:customer management'])->get('/customer/order/sheet/{id}', 'orderSheet')->name('customer.order.sheet');
    Route::middleware(['role_or_permission:customer management'])->get('/customer/order-details/{id}', 'orderDetails')->name('customer.order.details');
});

//! Route for employee management
Route::controller(\App\Http\Controllers\Web\Backend\user\EmployeeManagementController::class)->group(function () {
    Route::middleware(['role_or_permission:employee management'])->get('/employees', 'index')->name('employees.index');
    Route::middleware(['role_or_permission:employee management'])->get('/employees/add', 'create')->name('employees.add');
    Route::middleware(['role_or_permission:employee management'])->post('/employees/add', 'store')->name('employees.store');
    Route::middleware(['role_or_permission:employee management'])->get('/employees-edit/{id}', 'edit')->name('employees.edit');
    Route::middleware(['role_or_permission:employee management'])->post('/employees-update/{id}', 'update')->name('employees.update');
});


//! Route for meeting management
Route::controller(MeetingController::class)->group(function () {
    Route::middleware(['role_or_permission:meeting index'])->get('/meetings', 'index')->name('meetings.index');
    Route::middleware(['role_or_permission:meeting update'])->post('/meeting/status/update/{id}', 'updateStatus')->name('meeting.status.update');
    Route::middleware(['role_or_permission:meeting delete'])->delete('/meeting/delete/{id}', 'destroy')->name('meeting.delete');
});

//! Route for role management
Route::controller(\App\Http\Controllers\Web\Backend\RoleAndPermission\RoleController::class)->group(function () {
    Route::middleware(['role_or_permission:role and permission manage'])->get('/roles', 'index')->name('roles.index');
    Route::middleware(['role_or_permission:role and permission manage'])->get('/roles/add', 'create')->name('roles.add');
    Route::middleware(['role_or_permission:role and permission manage'])->post('/role/store', 'store')->name('role.store');
    Route::middleware(['role_or_permission:role and permission manage'])->get('/role/edit/{id}', 'edit')->name('role.edit');
    Route::middleware(['role_or_permission:role and permission manage'])->put('/role/update/{id}', 'update')->name('role.update');
    Route::middleware(['role_or_permission:role and permission manage'])->delete('/role/delete/{id}', 'destroy')->name('role.delete');
});

//! Route for permission management
Route::middleware(['role_or_permission:role and permission manage'])->get('/permissions', [\App\Http\Controllers\Web\Backend\RoleAndPermission\PermissionController::class, 'index'])->name('permissions.index');  // Get permissions

//! Route for employee role management
Route::middleware(['role_or_permission:role and permission manage'])->get('/employee-roles', [\App\Http\Controllers\Web\Backend\RoleAndPermission\EmployeeRoleManagementController::class, 'index'])->name('employee.roles.index');
Route::middleware(['role_or_permission:role and permission manage'])->post('/employee/{id}/attach-role', [\App\Http\Controllers\Web\Backend\RoleAndPermission\EmployeeRoleManagementController::class, 'attachRole'])->name('employee.attach.role');
Route::middleware(['role_or_permission:role and permission manage'])->post('/employee/{id}/detach-role', [\App\Http\Controllers\Web\Backend\RoleAndPermission\EmployeeRoleManagementController::class, 'detachRole'])->name('employee.detach.role');


