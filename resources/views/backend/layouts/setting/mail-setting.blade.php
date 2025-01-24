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
//$cms = App\Models\Cms::where('type','banner')->first();
//$personalize = App\Models\Cms::where('type','personalized')->first();
@endphp
<div class="content-wrapper">
    <div class="main-content">
        <div class="body-content">
            <div class="decoration blur-2"></div>
            <div class="decoration blur-3"></div>
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="p-4 bg-white rounded-lg shadow-md mt-2">
                    <div class="space-y-4">
                    <form id="mailSettingForm" method="POST" enctype="multipart/form-data" class="max-w-6xl w-full mx-auto space-y-4">
                                @csrf

                                <div class="flex-col md:flex-row">
                                    <h1 class="text-center text-lg">SMTP Mail Server Settings</h1>
                                </div>

                                <!-- First row: Mail Mailer and Mail Host -->
                                <div class="flex flex-col md:flex-row items-center md:space-x-6">
                                    <div class="flex flex-col md:w-1/2">
                                        <label for="mail_mailer" class="text-lg font-medium mb-2">Mail Mailer</label>
                                        <input type="text" name="mail_mailer" class="form-input w-full" id="mail_mailer" value="{{ env('MAIL_MAILER') }}" placeholder="Mail Mailer">
                                        @error('mail_mailer')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="flex flex-col md:w-1/2">
                                        <label for="mail_host" class="text-lg font-medium mb-2">Mail Host</label>
                                        <input type="text" name="mail_host" class="form-input w-full" id="mail_host" value="{{ env('MAIL_HOST') }}" placeholder="Mail Host">
                                        @error('mail_host')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Second row: Mail Port and Mail Username -->
                                <div class="flex flex-col md:flex-row items-center md:space-x-4">
                                    <div class="flex flex-col md:w-1/2">
                                        <label for="mail_port" class="text-lg font-medium mb-2">Mail Port</label>
                                        <input type="text" name="mail_port" class="form-input w-full" id="mail_port" value="{{ env('MAIL_PORT') }}" placeholder="Mail Port">
                                        @error('mail_port')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="flex flex-col md:w-1/2">
                                        <label for="mail_username" class="text-lg font-medium mb-2">Mail Username</label>
                                        <input type="text" name="mail_username" class="form-input w-full" id="mail_username" value="{{ env('MAIL_USERNAME') }}" placeholder="Mail Username">
                                        @error('mail_username')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Third row: Mail Password and Mail Encryption -->
                                <div class="flex flex-col md:flex-row items-center md:space-x-4">
                                    <div class="flex flex-col md:w-1/2">
                                        <label for="mail_password" class="text-lg font-medium mb-2">Mail Password</label>
                                        <input type="text" name="mail_password" class="form-input w-full" id="mail_password" value="{{ env('MAIL_PASSWORD') }}" placeholder="Mail Password">
                                        @error('mail_password')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="flex flex-col md:w-1/2">
                                        <label for="mail_encryption" class="text-lg font-medium mb-2">Mail Encryption</label>
                                        <input type="text" name="mail_encryption" class="form-input w-full" id="mail_encryption" value="{{ env('MAIL_ENCRYPTION') }}" placeholder="Mail Encryption">
                                        @error('mail_encryption')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Fourth row: Mail From Address -->
                                <div class="flex flex-col md:flex-row items-center md:space-x-4">
                                    <div class="flex flex-col md:w-1/2">
                                        <label for="mail_from_address" class="text-lg font-medium mb-2">Mail From Address</label>
                                        <input type="text" name="mail_from_address" class="form-input w-full" id="mail_from_address" value="{{ env('MAIL_FROM_ADDRESS') }}" placeholder="Mail From Address">
                                        @error('mail_from_address')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end mt-4">
                                    <button type="submit" class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold ml-2">
                                        Update
                                    </button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/.body content-->
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


    $(document).ready(function() {
    $('#mailSettingForm').submit(function(e) {
        e.preventDefault(); // Prevent form from submitting the traditional way

        var formData = new FormData(this); // Get form data

        $.ajax({
            url: "{{ route('mail.setting.update') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Check if the response has a success flag
                if (response.success === true) {
                    flasher.success(response.message); // Show success message
                    setTimeout(function() {
                        window.location.href = "{{ route('mail.setting.update') }}"; // Redirect after 2 seconds
                    }, 2000);
                } else {
                    flasher.error(response.message); // Show error message if success is false
                }
            },
            error: function(xhr, status, error) {
                // If the AJAX request fails (network error, etc.), show a generic error
                flasher.error('An error occurred. Please try again later.', 'Error', {timeOut: 5000});
            }
        });
    });
});



</script>
@endpush