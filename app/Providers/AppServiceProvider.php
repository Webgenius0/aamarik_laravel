<?php

namespace App\Providers;

use App\Models\DynamicPage;
use App\Models\Settings;
use App\Models\SocialMedia;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $setting = Settings::first();
            $socialmedia = SocialMedia::where('status', 1)->get();
            $dynamicPage = DynamicPage::where('status', 1)->get();
            $view->with('setting', $setting);
            $view->with('socialmedia', $socialmedia);
            $view->with('dynamicPages', $dynamicPage);
        });

    }
}
