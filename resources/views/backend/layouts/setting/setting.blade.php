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
        $setting = App\Models\Setting::first();
    @endphp
    <div class="content-wrapper">
        <div class="main-content">
            <div class="body-content">
                <div class="decoration blur-2"></div>
                <div class="decoration blur-3"></div>
                <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                    <div class="p-4 bg-white rounded-lg shadow-md mt-2">
                        <div class="space-y-4">
                            <form action="{{ route('admin.setting.update') }}" class="max-w-6xl w-full mx-auto space-y-4"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="flex-col md:flex-row">
                                    <label for="applicationTitle"
                                        class=" text-lg font-medium mb-2 md:mb-0 md:w-1/3">Application
                                        Title</label>
                                    <div class="w-full">
                                        <input type="text" name="title" class="form-input w-full" id="applicationTitle"
                                            value="{{ $setting ? $setting->title : 'Am_inn' }}">
                                        @error('title')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex-col md:flex-row">
                                    <label for="address" class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Address</label>
                                    <div class="w-full">
                                        <textarea name="address" class="form-textarea w-full" id="address" rows="3">{{ $setting ? $setting->address : 'Example 10 Street' }}</textarea>
                                        @error('address')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex-col md:flex-row">
                                    <label for="description"
                                        class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Description</label>
                                    <div class="w-full">
                                        <textarea name="description" class="form-textarea w-full" id="description" rows="3">{{ $setting ? $setting->description : 'Lorem Ipsam Doller' }}</textarea>
                                    </div>
                                </div>

                                <div class="flex-col md:flex-row">
                                    <label for="email" class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Email
                                        Address</label>
                                    <div class="w-full">
                                        <input name="email" type="email" class="form-input w-full" id="email"
                                            value="{{ $setting ? $setting->email : 'example@aminn.com' }}">
                                        @error('email')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex-col md:flex-row">
                                    <label for="phone" class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Phone
                                        Number</label>
                                    <div class="w-full">
                                        <input name="phone" type="number" class="form-input w-full" id="phone"
                                            value="{{ $setting ? $setting->phone : '+8801 123 456' }}">
                                        @error('phone')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- favicon --}}
                                <div class="flex flex-row space-x-4">
                                    <div class="w-1/2">
                                        <div class="flex-col md:flex-row">
                                            <label class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Favicon</label>
                                            <div class="w-full">
                                                <input name="favicon"
                                                    class="form-input w-full dropify dropify-wrapper1 .dropify-preview dropify-render img"
                                                    data-height="300" type="file"
                                                    {{-- accept=".jpg, .png, image/jpeg, image/png" --}}
                                                     accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg"
                                                    data-default-file="{{ asset($setting->favicon) }}">
                                                @error('favicon')
                                                    <span class="text-red-500 block mt-1 text-sm">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                {{ asset($setting->favicon) }}
                                                <div class="text-blue-500 text-sm mt-1">
                                                    Recommended to 32x32 px(jpeg,png,jpg,gif,ico,webp,bmp,svg).
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    {{-- logo  --}}
                                    <div class="w-1/2">
                                        <div class="flex-col md:flex-row">
                                            <label class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">
                                                Logo
                                            </label>
                                            <div class="w-full">
                                                <input name="logo"
                                                    class="form-input w-full  dropify dropify-wrapper1 .dropify-preview dropify-render img"
                                                    data-height="300" type="file"
                                                    {{-- accept=".jpg, .png, image/jpeg, image/png" --}}
                                                     accept=".jpeg, .png, .jpg, .gif, .ico, .webp, .bmp, .svg"
                                                    data-default-file="{{ asset($setting->logo) }}">
                                                @error('logo')
                                                    <span class="text-red-500 block mt-1 text-sm">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <div class="text-blue-500 text-sm mt-1">Recommended to 184x42 px(jpeg,png,jpg,gif,ico,webp,bmp,svg).</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="flex-col md:flex-row">
                                    <label for="officeTime" class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Office
                                        Time</label>
                                    <div class="w-full">
                                        <textarea name="office_time" class="form-textarea w-full" id="officeTime" rows="3">{{ $setting ? $setting->office_time : '10:00 - 18:00' }}</textarea>
                                        @error('office_time')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex-col md:flex-row">
                                    <label for="footer" class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Footer
                                        Text</label>
                                    <div class="w-full">
                                        <input name="footer_text" type="text" class="form-input w-full"
                                            id="footer"
                                            value="{{ $setting ? $setting->footer_text : 'Copyright © 2021. All rights reserved.' }}">
                                        @error('footer_text')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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
        <!--/.main content-->

        <div class="overlay"></div>
    </div>
@endsection

{{-- Push Script //partials/script.blade.php (scripts stacked) --}}
@push('scripts')
    <!-- Tailwind CSS CDN -->
    <script src="https://unpkg.com/tailwindcss-jit-cdn@2.2.19/dist/tailwind.min.js"></script>
    {{-- Dropify --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    {{-- Ck Editor --}}
    <script src="{{ asset('Backend/plugins/tinymc/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush
