<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

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




    //! Send firebase notification
    public static function sendNotifyMobile($token, $data): void
    {
        try {
            // Debugging autoloader
            if (!class_exists(Factory::class)) {
                Log::error('Class "Kreait\\Firebase\\Factory" not found. Check if the Firebase SDK is installed.');
            }
            // $factory = (new Factory)->withServiceAccount(storage_path('app/private/masjid-suite-firebase-adminsdk-geodk-dd6693d7aa.json'));
            $factory = (new Factory)->withServiceAccount(public_path('masjid-suite-firebase-adminsdk-geodk-dd6693d7aa.json'));

            $messaging = $factory->createMessaging();
            $notification = FirebaseNotification::create($data['title'], Str::limit($data['message'], 100));

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification);
            $messaging->send($message);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

  
    

    public static function arrayfileUpload($file, $folder, $name)
    {
        // Ensure the file is not an array or null
        if (is_array($file) || !$file instanceof \Illuminate\Http\UploadedFile) {
            throw new \InvalidArgumentException("Expected an uploaded file, but got an array or invalid type.");
        }
        $uniqueName = Str::slug($name) . '-' . time() . '-' . Str::random(10) . '.' . $file->extension();
        
        $file->move(public_path('uploads/' . $folder), $uniqueName);
    
       
        $path = 'uploads/' . $folder . '/' . $uniqueName;
    
        return $path;
    }
    

}
