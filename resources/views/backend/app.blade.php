<!DOCTYPE html>
<html lang="en" data-sidebar-color="light" data-topbar-color="light" data-sidebar-view="default">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="MyraStudio" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/sis-logo.png') }}">

    @vite('resources/css/app.css')

    @include('backend.partials.style')
</head>

<body>
    <div class="app-wrapper">

        @include('backend.partials.sidebar')

        <!-- Start Page Content here -->
        <div class="app-content">

            @include('backend.partials.header')

              <!-- Include Flash Messages Partial Here -->
              @include('backend.partials.flash-messages')

            @yield('content')

            @include('backend.partials.footer')
        </div>

        <!-- End Page content -->

        <div class="profile-menu" >
            <div class="flex flex-col items-center h-full gap-4 py-10 px-3 ">
                <!-- Profile Link -->
                {{-- <a href="#" type="button" class="flex flex-col items-center gap-1 "> --}}

                <div class="hs-dropdown relative">
                    <button type="button" class="hs-dropdown-toggle text-slate-900  ">
                        <div class="text-center">
                            <img src="{{ asset('backend/assets/images/users/avatar-6.jpg') }}" alt="user-image"
                                class="rounded-full h-8 w-8 ms-4">

                        </div>
                        <span class="font-medium text-base">{{ auth()->user()->name}}</span> <br>
                        <span class="text-sm">Admin</span>
                    </button>

                    <div
                        class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden bg-white shadow py-2 z-10">
                        <a class="flex items-center py-2 px-6 rounded-sm text-gray-800 hover:bg-gray-100"
                            href="#">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center py-2 px-6 rounded-sm text-gray-800 hover:bg-gray-100">
                                Log Out
                            </button>
                        </form>

                    </div>
                </div>
                {{-- </a> --}}

                <!-- Search Modal Button -->
                <button type="button" data-hs-overlay="#search-modal" class="bg-white rounded-full shadow-md p-2">
                    <span class="sr-only">Search</span>
                    <span class="flex items-center justify-center h-6 w-6">
                        <i class="uil uil-search text-2xl"></i>
                    </span>
                </button>

                <!-- Fullscreen Toggle Button -->
                <div class="flex">
                    <button data-toggle="fullscreen" type="button" class="bg-white rounded-full shadow-md p-2">
                        <span class="sr-only">Fullscreen Mode</span>
                        <span class="flex items-center justify-center h-6 w-6">
                            <i class="uil uil-focus text-2xl"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>



        <div id="search-modal"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[60] overflow-x-hidden overflow-y-auto pointer-events-none">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div class="flex flex-col bg-white shadow-sm rounded-xl pointer-events-auto overflow-hidden">
                    <div class="relative z-[60]">
                        <input type="search" id="search-input" class="form-input ps-10">
                        <span class="absolute start-3 top-1.5">
                            <i class="uil uil-search text-lg"></i>
                        </span>

                        <span class="absolute end-3 top-1.5">
                            <button data-hs-overlay="#search-modal">
                                <i class="uil uil-times text-lg"></i>
                            </button>
                        </span>
                    </div>

                </div>
            </div>
        </div>

    </div>



    @include('backend.partials.script')

    {{-- @stack('script') --}}
</body>

</html>
