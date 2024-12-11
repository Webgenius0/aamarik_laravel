<?php

namespace App\Helper;

use Illuminate\Support\Str;

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
