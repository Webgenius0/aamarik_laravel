@extends('backend.app')
{{-- Title --}}
@section('title', 'Setting')


@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<style>

</style>
@endpush

@section('content')

@php
$cms = App\Models\Cms::where('type','banner')->first();
$personalize = App\Models\Cms::where('type','personalized')->first();
@endphp
<div class="content-wrapper">
    <div class="main-content">
        <div class="body-content">
            <div class="decoration blur-2"></div>
            <div class="decoration blur-3"></div>
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="p-4 bg-white rounded-lg shadow-md mt-2">
                    <div class="space-y-4">
                        <form action="{{ route('cms.update') }}" class="max-w-6xl w-full mx-auto space-y-4"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <div class="flex-col md:flex-row">
                                <h1 class="text-center text-lg ">Home Banner</h1>
                              
                                <label for="applicationTitle"
                                    class=" text-lg font-medium mb-2 md:mb-0 md:w-1/3">Title
                                </label>
                                <div class="w-full">
                                    <input type="text" name="title" class="form-input w-full" id="applicationTitle"
                                        value="{{ $cms ? $cms->title : 'N/A' }}">
                                    @error('title')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex-col md:flex-row">
                                <label for="address" class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">sub Title</label>
                                <div class="w-full">
                                    <textarea name="sub_title" class="form-textarea w-full" id="sub_title" rows="3">{{ $cms ? $cms->sub_title : 'Example 10 Street' }}</textarea>
                                    @error('sub_title')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            

                            <div class="flex-col md:flex-row">
                                <label for="email" class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Button</label>
                                <div class="w-full">
                                    <input name="button_name" type="text" class="form-input w-full" id="button_name"
                                        value="{{ $cms ? $cms->button_name : 'example@aminn.com' }}">
                                    @error('button_name')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex-col md:flex-row">
                                <label for="email" class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Button Url</label>
                                <div class="w-full">
                                    <input name="button_url" type="button_url" class="form-input w-full" id="email"
                                        value="{{ $cms ? $cms->button_url : 'example@aminn.com' }}">
                                    @error('email')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- favicon --}}
                            <div class="flex flex-row space-x-8">
                                <div class="w-1/2">
                                    <div class="flex-col md:flex-row">
                                        <label class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Avatar</label>
                                        <div class="w-full">
                                            <input name="avatar"
                                                class="form-input w-full dropify dropify-wrapper1 .dropify-preview dropify-render img"
                                                data-height="300" type="file"
                                                {{-- accept=".jpg, .png, image/jpeg, image/png" --}}
                                                accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg"
                                                data-default-file="{{ asset($cms->avatar) }}">
                                            @error('avatar')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            {{ asset($setting->avatar) }}
                                            <div class="text-blue-500 text-sm mt-1">
                                                Recommended to 32x32 px(jpeg,png,jpg,gif,ico,webp,bmp,svg).
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="flex justify-center mt-4">
                                <button type="button"
                                    class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold">Reset</button>
                                <button type="submit"
                                    class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold ml-2">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/.body content-->
    </div>
    

    <!-- personalized Helth care -->
    <div class="overlay mt-5">
         <div class="body-content">
            <div class="decoration blur-2"></div>
            <div class="decoration blur-3"></div>
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="p-4 bg-white rounded-lg shadow-md mt-2">
                    <div class="space-y-4">
                        <form action="{{ route('cms.personalized') }}" class="max-w-6xl w-full mx-auto space-y-4"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <div class="flex-col md:flex-row">
                                <h1 class="text-center text-lg ">Personalized HelthCare</h1>
                              
                                <label for="applicationTitle"
                                    class=" text-lg font-medium mb-2 md:mb-0 md:w-1/3">Title
                                </label>
                                <div class="w-full">
                                    <input type="text" name="title" class="form-input w-full" id="applicationTitle"
                                        value="{{ $personalize ? $personalize->title : 'N/A' }}">
                                    @error('title')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex-col md:flex-row">
                                <label for="address" class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Description</label>
                                <div class="w-full">
                                    <textarea name="description" class="form-textarea w-full" id="desctiption" rows="3">{{ $personalize ? $personalize->description : 'Example 10 Street' }}</textarea>
                                    @error('sub_title')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- avatar --}}
                            <div class="flex flex-row space-x-8">
                                <div class="w-1/2">
                                    <div class="flex-col md:flex-row">
                                        <label class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Avatar</label>
                                        <div class="w-full">
                                            <input name="avatar"
                                                class="form-input w-full dropify dropify-wrapper1 .dropify-preview dropify-render img"
                                                data-height="300" type="file"
                                                {{-- accept=".jpg, .png, image/jpeg, image/png" --}}
                                                accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg"
                                                data-default-file="{{ asset($personalize->avatar) }}">
                                            @error('avatar')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            {{ asset($personalize->avatar) }}
                                            <div class="text-blue-500 text-sm mt-1">
                                                Recommended to 32x32 px(jpeg,png,jpg,gif,ico,webp,bmp,svg).
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="flex justify-center mt-4">
                                <button type="button"
                                    class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold">Reset</button>
                                <button type="submit"
                                    class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold ml-2">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Push Script //partials/script.blade.php (scripts stacked) --}}
@push('scripts')
<!-- Tailwind CSS CDN -->
<script src="https://unpkg.com/tailwindcss-jit-cdn@2.2.19/dist/tailwind.min.js"></script>
{{-- Dropify --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
{{--Flashar--}}
<script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
{{-- Ck Editor --}}
<script src="{{ asset('Backend/plugins/tinymc/tinymce.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.dropify').dropify();
    });


    $(document).ready(function() {
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