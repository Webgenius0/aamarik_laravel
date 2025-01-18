@extends('backend.app')

{{-- Title --}}
@section('title', 'Setting')

@push('styles')
<!-- Dropify CSS for file input styling -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
<!-- Tailwind CSS for layout and styling -->
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
@endpush

@section('content')

@php
// Uncomment this if you want to use dynamic CMS model
//$cms = App\Models\Cms::where('type','personalized')->first();
@endphp

<!-- Display current settings -->


<!-- Form to update settings -->
<div class="overlay mt-5">
    <div class="body-content">
        <div class="decoration blur-2"></div>
        <div class="decoration blur-3"></div>
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
  
            <div class="p-4 bg-white rounded-lg shadow-md mt-2">
                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                
                        <div class="section mb-6">
                        <h1 class="text-center text-lg font-semibold text-gray-900">Home Section</h1>
                            <label for="" class="block text-sm font-medium text-gray-700">Title</label>
                                                <input type="text" id="" name="" value="" class="form-input w-full p-2 mb-2 border rounded">
                            <div class="card mb-4">
                                <!-- Use Tailwind grid system to show 3 items per row -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                                 
                                        <!-- Avatar Card -->
                                        <div class="avatar-card flex flex-col items-center w-full">
                                            <!-- Avatar Section (centered) -->
                                            <div class="avatar-section flex flex-col items-center mb-4">
                                                <label for="" class="block text-sm font-medium text-gray-700">Avatar</label>
                                                <input type="file" id="" name="" class="dropify" data-default-file="" />
                                            </div>

                                            <!-- Title and Subtitle Section (below the avatar) -->
                                            <div class="text-section flex flex-col w-full">
                                                <!-- Title as input field -->
                                                <label for="" class="block text-sm font-medium text-gray-700">Title</label>
                                                <input type="text" id="" name="" value="" class="form-input w-full p-2 mb-2 border rounded">

                                                <!-- Subtitle as textarea field -->
                                                <label for="" class="block text-sm font-medium text-gray-700">Subtitle</label>
                                                <textarea id="" name="" class="form-input w-full p-2 mb-2 border rounded" rows="4"></textarea>
                                            </div>
                                        </div>
                                 

                                </div>
                            </div>
                        </div>
                

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Save Changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


@endsection

{{-- Push Script for Dropify and other custom JS --}}
@push('scripts')
<!-- Tailwind CSS CDN (Tailwind JIT) -->
<script src="https://unpkg.com/tailwindcss-jit-cdn@2.2.19/dist/tailwind.min.js"></script>

<!-- Dropify JS for file input styling -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

<!-- Flasher for notifications -->
<script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>

<!-- TinyMCE editor or any other plugin you might use -->
<script src="{{ asset('Backend/plugins/tinymc/tinymce.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Initialize Dropify file input
        $('.dropify').dropify();

        // Initialize Flasher for notifications
        const flasher = new Flasher({
            selector: '[data-flasher]',
            duration: 3000,
            options: {
                position: 'top-center',
            },
        });
    });
</script>
@endpush