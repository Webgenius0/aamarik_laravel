<?php

namespace App\Helper;

use App\Models\PendingFees;
use App\Models\User;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Stripe\Stripe;
use Stripe\Subscription;

class Helper
{
  // Upload Image
  public static function fileUpload($file, $folder, $name)
  {
    $imageName = Str::slug($name) . '.' . $file->extension();
    $file->move(public_path('uploads/' . $folder), $imageName);
    $path = 'uploads/' . $folder . '/' . $imageName;
    return $path;
  }
}
