@extends('backend.app')
{{-- Title --}}
@section('title', 'Setting')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="content-wrapper">
    <div class="main-content">
        <div class="body-content">
            <div class="decoration blur-2"></div>
            <div class="decoration blur-3"></div>
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="p-4 bg-white rounded-lg shadow-md mt-2">
                    <div class="space-y-4">
                        <form method="POST" action="{{ route('microsoft.setting.update') }}" class="max-w-6xl w-full mx-auto space-y-4">
                            @csrf

                            <div class="flex-col md:flex-row">
                                <h1 class="text-center text-lg">Microsoft  Settings</h1>
                            </div>

                            <!-- MICROSOFT CLIENT ID -->
                            <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                                <label for="MS_CLIENT_ID" class="text-lg font-medium mb-2 md:w-1/4">MICROSOFT CLIENT ID</label>
                                <input class="form-control @error('MS_CLIENT_ID') is-invalid @enderror md:w-3/4" type="text" name="MS_CLIENT_ID" id="MS_CLIENT_ID" value="{{ old('MS_CLIENT_ID', env('MS_CLIENT_ID')) }}" placeholder="Microsoft Client Id ">
                                @error('MS_CLIENT_ID')
                                <span class="text-red-500 block mt-1 text-sm">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- MICROSOFT CLIENT SECRET -->
                            <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                                <label for="MS_CLIENT_SECRET" class="text-lg font-medium mb-2 md:w-1/4">MICROSOFT CLIENT SECRET</label>
                                <input class="form-control @error('MS_CLIENT_SECRET') is-invalid @enderror md:w-3/4" id="MS_CLIENT_SECRET"
                                    name="MS_CLIENT_SECRET" placeholder="Enter your Stripe Secret" type="text"
                                    value="{{ old('MS_CLIENT_SECRET', env('MS_CLIENT_SECRET')) }}">
                                @error('MS_CLIENT_SECRET')
                                <span class="text-red-500 block mt-1 text-sm">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- MICROSOFT TENANT ID -->
                            <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                                <label for="MS_TENANT_ID" class="text-lg font-medium mb-2 md:w-1/4">MICROSOFT TENANT ID</label>
                                <input class="form-control @error('MS_TENANT_ID') is-invalid @enderror md:w-3/4" id="MS_TENANT_ID"
                                    name="MS_TENANT_ID" placeholder="Enter your Microsoft Tenant Id" type="text"
                                    value="{{ old('MS_TENANT_ID',env('MS_TENANT_ID')) }}">
                                @error('MS_TENANT_ID')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                             <!-- Ms AutoHority -->
                             <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                                <label for="MS_REDIRECT_URI" class="text-lg font-medium mb-2 md:w-1/4">MICROSOFT REDIRECT URL</label>
                                <input class="form-control @error('MS_REDIRECT_URI') is-invalid @enderror md:w-3/4" id="MS_REDIRECT_URI"
                                    name="MS_REDIRECT_URI" placeholder="Enter your Stripe Secret" type="text"
                                    value="{{ old('MS_REDIRECT_URI',env('MS_REDIRECT_URI')) }}">
                                @error('MS_REDIRECT_URI')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Submit Button -->
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold ml-2">
                                    Update
                                </button>
                            </div>
                        </form>

                        <!-- Flash Messages -->
                        <!-- Flash Messages -->
                        @if (session('success'))
                        <div class="fixed top-0 right-0 mt-4 mr-4 text-green-600 bg-green-100 p-4 rounded-lg shadow-lg w-1/3">
                            <strong>{{ session('success') }}</strong>
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="fixed top-0 right-0 mt-4 mr-4 text-red-600 bg-red-100 p-4 rounded-lg shadow-lg w-1/3">
                            <strong>{{ session('error') }}</strong>
                        </div>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
<!-- Tailwind CSS CDN -->
<script src="https://unpkg.com/tailwindcss-jit-cdn@2.2.19/dist/tailwind.min.js"></script>
{{-- Dropify --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
@endpush
