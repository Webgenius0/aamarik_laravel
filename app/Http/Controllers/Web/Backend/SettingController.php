<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\DynamicPage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;



class SettingController extends Controller
{

    public function index()
    {
        return view('backend.layouts.setting.setting');
    }

    public function additional()
    {
        return view('backend.layouts.setting.dynamic-page');
    }




    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'office_time' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:5120',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:5120',
        ]);

        try {
            $setting = Setting::latest('id')->first() ?: new Setting();

            $setting->title = $request->title;
            $setting->address = $request->address;
            $setting->description = $request->description;
            $setting->email = $request->email;
            $setting->phone = $request->phone;
            $setting->office_time = $request->office_time;
            $setting->footer_text = $request->footer_text;

            // Upload Logo
            if ($request->hasFile('logo')) {
                if ($setting->logo) {
                    File::delete(public_path($setting->logo));
                }
                $setting->logo = Helper::fileUpload($request->file('logo'), 'setting', 'logo');
            }

            // Upload Favicon
            if ($request->hasFile('favicon')) {
                if ($setting->favicon) {
                    File::delete(public_path($setting->favicon));
                }
                $setting->favicon = Helper::fileUpload($request->file('favicon'), 'setting', 'favicon');
            }

            $setting->save();

            return redirect()->route('admin.setting')->with('t-success', 'Update successfully.');
        } catch (\Exception $e) {
            // \Log::error('Settings update failed: '.$e->getMessage());
            return redirect()->route('admin.setting')->with('t-error', 'Something went wrong. Please try again.');
        }

    }


    /**
     *
     * Dynamic Pages
     *
     */

    public function dynamicPageCreate()
    {
        return view('backend.layouts.setting.dynamic-page');
    }

    public function dynamicPageStore(Request $request)
    {
        $messages = [
            'page_title.required' => 'The page title is required.',
            'page_title.string' => 'The page title must be a string.',
            'page_title.max' => 'The page title must not exceed 100 characters.',
            'page_content.required' => 'The page content is required.',
            'page_content.string' => 'The page content must be a string.',
        ];

        $request->validate([
            'page_title' => 'required|string|max:100',
            'page_content' => 'required|string',
        ], $messages);

        $data = new DynamicPage();
        $data->page_title = $request->page_title;
        $data->page_slug = Str::slug($request->page_title);
        $data->page_content = $request->page_content;
        $data->save();

        return redirect()->route('dynamic_page.create')->with('t-success', 'New pages added successfully.');
    }

    public function dynamicPageEdit($id)
    {

        return view('backend.layouts.setting.dynamic-page');
    }

    public function dynamicPageUpdate(Request $request, $id)
    {
        $messages = [
            'page_title.required' => 'The page title is required.',
            'page_title.string' => 'The page title must be a string.',
            'page_title.max' => 'The page title must not exceed 100 characters.',
            'page_content.required' => 'The page content is required.',
            'page_content.string' => 'The page content must be a string.',
        ];

        $request->validate([
            'page_title' => 'required|string|max:100',
            'page_content' => 'required|string',
        ], $messages);

        $data = DynamicPage::findOrFail($id);
        $data->page_title = $request->page_title;
        $data->page_slug = Str::slug($request->page_title);
        $data->page_content = $request->page_content;
        $data->update();

        return redirect()->route('dynamic_page.create')->with('t-success', 'Selected pages updated successfully.');
    }

    public function dynamicPageDelete($id)
    {
        DynamicPage::findOrFail($id)->delete();
        return redirect()->route('dynamic_page.create')->with('t-success', 'Selected pages deleted successfully.');
    }
    /**
     * Display Mail resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function mailSetting()
    {
        return view('backend.layouts.setting.mail-setting');
    }
    /**
     * Update Mail Setting
     *
     */
    public function mailSettingUpdate(Request $request)
    {

        $messages = [
            'mail_mailer.required' => 'The mailer is required.',
            'mail_mailer.string' => 'The mailer must be a string.',
            'mail_host.required' => 'The mail host is required.',
            'mail_host.string' => 'The mail host must be a string.',
            'mail_port.required' => 'The mail port is required.',
            'mail_port.string' => 'The mail port must be a string.',
            'mail_username.nullable' => 'The mail username must be a string.',
            'mail_password.nullable' => 'The mail password must be a string.',
            'mail_encryption.nullable' => 'The mail encryption must be a string.',
            'mail_from_address.required' => 'The mail from address is required.',
            'mail_from_address.string' => 'The mail from address must be a string.',
        ];

        $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|string',
        ], $messages);

        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak = "\n";
            $envContent = preg_replace([
                '/MAIL_MAILER=(.*)\s/',
                '/MAIL_HOST=(.*)\s/',
                '/MAIL_PORT=(.*)\s/',
                '/MAIL_USERNAME=(.*)\s/',
                '/MAIL_PASSWORD=(.*)\s/',
                '/MAIL_ENCRYPTION=(.*)\s/',
                '/MAIL_FROM_ADDRESS=(.*)\s/',
            ], [
                'MAIL_MAILER=' . $request->mail_mailer . $lineBreak,
                'MAIL_HOST=' . $request->mail_host . $lineBreak,
                'MAIL_PORT=' . $request->mail_port . $lineBreak,
                'MAIL_USERNAME=' . $request->mail_username . $lineBreak,
                'MAIL_PASSWORD=' . $request->mail_password . $lineBreak,
                'MAIL_ENCRYPTION=' . $request->mail_encryption . $lineBreak,
                'MAIL_FROM_ADDRESS=' . '"' . $request->mail_from_address . '"' . $lineBreak,
            ], $envContent);

            if ($envContent !== null) {
                File::put(base_path('.env'), $envContent);
            }
            return response()->json([
                'success' => true,
                'message' => 'Mail Setting Updated Successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Mail Setting Update Failed'
            ]);
        }
    }

    /**
     * Display Stripe resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function stripeSetting()
    {
        return view('backend.layouts.setting.stripe-setting');
    }

    /**
     * Update stripe Setting
     *
     */
    public function stripeSettingUpdate(Request $request)
    {
        $request->validate([
            'STRIPE_KEY' => 'nullable|string',
            'STRIPE_SECRET' => 'nullable|string',
            'STRIPE_WEBHOOK_SECRET' => 'nullable|string',
        ]);

        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak = "\n";
            $envContent = preg_replace([
                '/STRIPE_KEY=(.*)\s/',
                '/STRIPE_SECRET=(.*)\s/',
                '/STRIPE_WEBHOOK_SECRET=(.*)\s/',
            ], [
                'STRIPE_KEY=' . $request->STRIPE_KEY . $lineBreak,
                'STRIPE_SECRET=' . $request->STRIPE_SECRET . $lineBreak,
                'STRIPE_WEBHOOK_SECRET=' . $request->STRIPE_WEBHOOK_SECRET . $lineBreak,
            ], $envContent);

            if ($envContent !== null) {
                File::put(base_path('.env'), $envContent);
            }
            session()->flash('success', 'Stripe settings updated successfully.');
            return redirect()->back();
           // return redirect()->back()->with('t-success', 'Stripe Setting Update successfully.');
        } catch (\Throwable $th) {
            return redirect(route('dashboard'))->with('t-error', 'Stripe Setting Update Failed');
        }
    }

         /**
             * get Microsoft Setting
             *
             */

        public function microsoftSetting()
        {
            return view('backend.layouts.setting.microsoft-setting');
        }

      /**
     * Update Microsoft Setting
     *
     */



     public function microsoftSettingUpdate(Request $request)
     {
         $request->validate([
             'MS_CLIENT_ID' => 'nullable|string',
             'MS_CLIENT_SECRET' => 'nullable|string',
             'MS_TENANT_ID' => 'nullable|string',
             'MS_REDIRECT_URI' => 'nullable|string',
         ]);

         try {
             $envContent = File::get(base_path('.env'));
             $lineBreak = "\n";
             $envContent = preg_replace([
                 '/MS_CLIENT_ID=(.*)\s/',
                 '/MS_CLIENT_SECRET=(.*)\s/',
                 '/MS_TENANT_ID=(.*)\s/',
                 '/MS_REDIRECT_URI=(.*)\s/',
             ], [
                 'MS_CLIENT_ID=' . $request->MS_CLIENT_ID . $lineBreak,
                 'MS_CLIENT_SECRET=' . $request->MS_CLIENT_SECRET . $lineBreak,
                 'MS_TENANT_ID=' . $request->MS_TENANT_ID . $lineBreak,
                 'MS_REDIRECT_URI=' . $request->MS_REDIRECT_URI . $lineBreak,
             ], $envContent);

             if ($envContent !== null) {
                 File::put(base_path('.env'), $envContent);
             }
             session()->flash('success', 'Microsoft settings updated successfully.');
             return redirect()->back();
            // return redirect()->back()->with('t-success', 'Stripe Setting Update successfully.');
         } catch (\Throwable $th) {
             return redirect(route('dashboard'))->with('t-error', 'Microsoft Setting Update Failed');
         }
     }


     /**
      * zoom setting
      */
     public function zoomSetting()
     {
         return view('backend.layouts.setting.zoom-setting');
     }

     /**
      * Zoom update
      */
     public function zoomSettingUpdate(Request $request)
     {
         $request->validate([
             'ZOOM_CLIENT_ID' => 'nullable|string',
             'ZOOM_CLIENT_SECRET' => 'nullable|string',
             'ZOOM_ACCOUNT_ID' => 'nullable|string',
         ]);

         try {
             $envContent = File::get(base_path('.env'));
             $lineBreak = "\n";
             $envContent = preg_replace([
                 '/ZOOM_CLIENT_ID=(.*)\s/',
                 '/ZOOM_CLIENT_SECRET=(.*)\s/',
                 '/ZOOM_ACCOUNT_ID=(.*)\s/',
             ], [
                 'ZOOM_CLIENT_ID=' . $request->ZOOM_CLIENT_ID . $lineBreak,
                 'ZOOM_CLIENT_SECRET=' . $request->ZOOM_CLIENT_SECRET . $lineBreak,
                 'ZOOM_ACCOUNT_ID' . $request->ZOOM_ACCOUNT_ID . $lineBreak,
             ], $envContent);

             if ($envContent !== null) {
                 File::put(base_path('.env'), $envContent);
             }
             session()->flash('success', 'Zoom settings updated successfully.');
             return redirect()->back();
         } catch (\Throwable $th) {
             return redirect(route('dashboard'))->with('t-error', 'Microsoft Setting Update Failed');
         }
     }
}
