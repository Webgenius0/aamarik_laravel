<?php

namespace App\Http\Controllers\Web\Backend\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MailSettingsController extends Controller
{
    public function index()
    {
        return view('backend.layouts.setting.mail-setting');
    }
}
