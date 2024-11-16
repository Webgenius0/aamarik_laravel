@extends('backend.app')
{{-- Title --}}
@section('title', 'Your Profile')

{{-- Push Style --}}
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        .profile-image-upload {
            width: 144px;
            position: absolute;
            bottom: 47px;
            right: 44%;
            border-radius: 50%;
            overflow: hidden;
            height: 146px;
            object-fit: cover;
        }

        .profile-image-upload .dropify-wrapper {
            width: 200px;
        }

        .dropify-wrapper .dropify-preview {
            display: none;
            position: absolute;
            z-index: 1;
            padding: 0px;
            width: 100%;
            height: 120%;
            top: -11px !important;
            right: 0;
            bottom: 0;
            left: 0;
            overflow: hidden;
            text-align: center;
        }

        .dropify-wrapper .dropify-preview .dropify-render img {
            top: 38%;
        }
    </style>
@endpush

@section('content')


    <div class="main-content ">
        <!-- Star navbar -->
        <!-- End /. navbar -->
        <div class="content-wrapper m-5">
            <div class="main-content">
                <!-- Star navbar -->
                <!-- End /. navbar -->
                <div class="body-content">
                    <div class="decoration blur-2"></div>
                    <div class="decoration blur-3"></div>
                    <div class="container-xxl">
                        <form action="{{ route('admin.edit.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Start Page Header -->
                            <div class="card mb-3 p-5 bg-gradient-to-b from-[#FDE9F5] to-[#F786C8] rounded-xl shadow-lg">
                                <!-- Profile Cover -->
                                <div class="profile-cover">
                                    <div class="profile-cover-img-wrapper ">

                                    </div>
                                </div>
                                <!-- /. End Profile Cover -->
                                <div class="text-center mt-52 " style="position: relative;">
                                    <!-- Start Image -->
                                    <div class="profile-image-upload">
                                        <input name="avatar" class="dropify"
                                            data-default-file="{{ Auth::user()->avatar != null ? asset(Auth::user()->avatar) : asset('backend/assets/images/avatar.png') }}"
                                            type="file" name="files" accept=".jpg, .png, image/jpeg, image/png"
                                            multiple>
                                        @error('avatar')
                                            <span class="text-red-500 text-sm" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <!-- Start Title -->
                                    <h4
                                        class="flex items-center justify-center gap-2 mt-5 mb-2 font-semibold text-2xl ms-10">
                                        {{ auth()->user()->name }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            fill="currentColor" class="bi bi-patch-check-fill text-green-500"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708z">
                                            </path>
                                        </svg>
                                    </h4>
                                </div>
                            </div>
                            <!-- /. End Page Header -->
                            <div class="mb-4 mt-10 bg-white rounded rounded-xl shadow-lg">
                                <div class="relative px-4 py-3 border-b">
                                    <h6 class="text-lg font-semibold mb-0">Profile Setting</h6>
                                </div>
                                <div class="p-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <!-- Full Name -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2" for="name">User Name</label>
                                            <input type="text"
                                                class="form-input form-input w-full border border-gray-300 rounded p-2 bg-gray-100"
                                                name="name" id="name" placeholder="Name"
                                                value="{{ old('name', auth()->user()->name) }}" >
                                            @error('name')
                                                <span class="text-red-500 text-sm" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <!-- language -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2" for="language">Language</label>
                                            <input type="text"
                                                class="form-input form-input w-full border border-gray-300 rounded p-2 bg-gray-100"
                                                id="language" name="language" value="{{ auth()->user()->language }}">
                                            @error('language')
                                                <span class="text-red-500 text-sm" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <!-- Email Address -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2">Email Address</label>
                                            <input type="email"
                                                class="form-input form-input w-full border border-gray-300 rounded p-2 bg-gray-100 bg-gray-100"
                                                disabled value="{{ auth()->user()->email }}">
                                        </div>
                                        <!-- Update Button -->
                                        <div>
                                            <button class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md"
                                                type="submit">Update
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <div class="mb-4 mt-10 bg-white rounded-xl shadow-lg">
                            <div class="relative px-4 py-3 border-b">
                                <h6 class="text-lg font-semibold mb-0">Change Password</h6>
                            </div>
                            <div class="p-4">
                                <form action="{{ route('admin.change.password') }}" method="POST">
                                    @csrf
                                    <div class="">
                                        <!-- Current Password -->
                                        <div class="basis-6/12 pe-3 mb-3">
                                            <label class="block text-sm font-medium mb-2">Current Password</label>
                                            <input type="password" name="old_password"
                                                class="form-input w-full border border-gray-300 rounded p-2 bg-gray-100"
                                                >
                                            @error('old_password')
                                                <span class="text-red-500 text-sm" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="mt-5 flex">
                                        <!-- New Password -->
                                        <div class="basis-6/12 pe-3 mb-3">
                                            <label class="block text-sm font-medium mb-2">New Password</label>
                                            <input type="password" name="new_password"
                                                class="form-input w-full border border-gray-300 rounded p-2 bg-gray-100"
                                                >
                                                @error('new_password')
                                                <span class="text-red-500 text-sm" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <!-- Confirm Password -->
                                        <div class="basis-6/12 pe-3 mb-3">
                                            <label class="block text-sm font-medium mb-2">Confirm Password</label>
                                            <input type="password" name="confirm_password"
                                                class="form-input w-full border border-gray-300 rounded p-2 bg-gray-100"
                                                >
                                                @error('confirm_password')
                                                <span class="text-red-500 text-sm" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <!-- Change Password Button -->
                                    </div>
                                    <div>
                                        <button type="submit"
                                            class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">
                                            Change
                                            Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>



                    </div>
                </div>
                <!--/.body content-->
            </div>
            <!--/.main content-->
            <!--/.footer content-->
            <div class="overlay"></div>
        </div>
    </div>
@endsection

{{-- Push Script --}}
@push('scripts')
    {{-- Dropify --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush
