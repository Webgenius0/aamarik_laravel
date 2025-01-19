<?php

namespace App\Http\Controllers\Web\Backend;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CMS;
use App\Models\Section;
use App\Models\SectionCard;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class CMSController extends Controller
{
    /**
     * Display cms page
    */
    public function banner()
    {
        $cms = CMS::where('type','banner')->first();
        $personalize = CMS::where('type','personalized')->first();
        return view('backend.layouts.cms.index',compact('cms','personalize'));
    }
    
  
    public function update(Request $request)
    {
     
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:1000',
            'button_name' => 'required|string|max:255',
            'button_url' => 'required|url|max:255',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
        ]);

        try {

            $cms = CMS::where('type', 'banner')->first();
            $data = [
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'button_name' => $request->button_name,
                'button_url' => $request->button_url,
                'type' => 'banner',
            ];
            if ($request->hasFile('avatar')) {

                if ($cms && $cms->avatar) {
                    File::delete(public_path($cms->avatar));
                }

                $data['avatar'] = Helper::fileUpload($request->file('avatar'), 'cms', 'avatar');
            }
            if ($cms) {

               $cms->update($data);
                $message = 'Banner settings updated successfully!';
            }

            return redirect()->route('banner')->with('t-success', $message);

        } catch (Exception $e) {

           // \Log::error('Settings update failed: '.$th->getMessage());
            return redirect()->route('banner')->with('t-error', 'Something went wrong. Please try again.');
        }
    }

    //personalized helth care
    public function personalized(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',

        ]);
        try {
            $cms = CMS::where('type', 'personalized')->first();
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'type' => 'personalized',
            ];
            if ($request->hasFile('avatar')) {
                if ($cms && $cms->avatar) {
                    File::delete(public_path($cms->avatar));
                }
                $data['avatar'] = Helper::fileUpload($request->file('avatar'), 'cms', 'avatar');
            }
            if ($cms) {
                $cms->update($data);
                $message = 'Personalized settings updated successfully!';
            }
            return redirect()->route('banner')->with('t-success', $message);

            //return response()->json(['success' => true, 'message' => 'FAQ created successfully']);
        } catch (\Throwable $th) {
            return redirect()->route('banner')->with('t-error', 'Something went wrong. Please try again.');
        }
    }

    //personalized helth care

public function homeSection(Request $request)
{

$sections = Section::with('sectionCards')->where('type', 'healthcare')->get();
$workingProcess = Section::with('sectionCards')->where('type', 'process')->get();
//dd($workingProcess);
    
    return view('backend.layouts.cms.home-section', compact( 'sections','workingProcess'));
    
}


//update home section
public function updateSection(Request $request)
{
    $request->validate([
        
        'title' => 'array',
        'title.*' => 'nullable|string|max:255',
        'sub_title' => 'array',
        'sub_title.*' => 'nullable|string|max:1000',  // Allow null values for sub_title
        'avatar' => 'array',
        'avatar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
    ]);

   
    foreach ($request->title as $cardId => $title) {
        try {
            
            $card = SectionCard::findOrFail($cardId);

            // Update title and sub_title
            $card->title = $title;
            $card->sub_title = $request->sub_title[$cardId] ?? 'N/A';  // Allow null if no sub_title

            // Handle avatar upload if present
            if ($request->hasFile('avatar') && isset($request->file('avatar')[$cardId])) {
               
                if ($card->avatar) {
                    $oldAvatarPath = public_path( $card->avatar);
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath);
                    }
                }

                // Upload the new avatar
                $avatarFile = $request->file('avatar')[$cardId];
                $avatarUrl = Helper::arrayfileUpload($avatarFile, 'default-image', 'avatar');

                if (!$avatarUrl) {
                    return redirect()->back()->with('error', 'Failed to upload avatar');
                }

                // Save the new avatar URL
                $card->avatar = $avatarUrl;
            }

            // Save the card
            $card->save();

        } catch (\Exception $e) {
         dd($e->getMessage());
            // Handle potential errors
            return redirect()->back()->with('error', 'An error occurred while updating the cards.');
        }
    }

    // Return success message after the loop
    return redirect()->back()->with('success', 'Cards updated successfully.');
}


//doctor section

public function doctorSection()
{
    return view('backend.layouts.cms.doctor-section');
}


//Working process
public function updateWorkingProcess(Request $request)
{
    //dd($request->all());
    $request->validate([
        'title' => 'array',
        'title.*' => 'nullable|string|max:255',
        'sub_title' => 'array',
        'sub_title.*' => 'nullable|string|max:1000',  // Allow null values for sub_title
        'avatar' => 'array',
        'avatar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
    ]);

    foreach ($request->title as $cardId => $title) {
        try {
            // Find the card by ID
            $card = SectionCard::findOrFail($cardId);

            // Update title and sub_title
            $card->title = $title;
            $card->sub_title = $request->sub_title[$cardId] ?? 'N/A';  // Allow null if no sub_title

            // Handle avatar upload if present
            if ($request->hasFile('avatar') && isset($request->file('avatar')[$cardId])) {
               
                if ($card->avatar) {
                    $oldAvatarPath = public_path( $card->avatar);
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath);
                    }
                }

                // Upload the new avatar
                $avatarFile = $request->file('avatar')[$cardId];
                $avatarUrl = Helper::arrayfileUpload($avatarFile, 'defult-image', 'avatar');

                if (!$avatarUrl) {
                    return redirect()->back()->with('error', 'Failed to upload avatar');
                }

                // Save the new avatar URL
                $card->avatar = $avatarUrl;
            }

            // Save the card
            $card->save();

        } catch (\Exception $e) {
         dd($e->getMessage());
            // Handle potential errors
            return redirect()->back()->with('error', 'An error occurred while updating the cards.');
        }
    }

    // Return success message after the loop
    return redirect()->back()->with('success', 'Cards updated successfully.');
}

}