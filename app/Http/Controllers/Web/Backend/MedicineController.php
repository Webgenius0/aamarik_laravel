<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function createMedicine(){
        return view('backend.layouts.medicine.create-medicine');
    }
}
