@extends('auth.app')

@section('title', 'Login | ' . $setting->title ?? 'SIS')

@push('style')
    <style>
        body {
            background: linear-gradient(to bottom, #FDE9F5, #F786C8) !important;
        }
    </style>
@endpush

@section('content')

    <div class="bg-gradient-to-b from-[#FDE9F5] to-[#F786C8] h-screen w-screen flex justify-center items-center">
        <div>
            <img class="absolute top-0 left-0" src="{{ asset('backend/assets/images/top-include.png') }}" alt="">
        </div>
        
        <div class="2xl:w-1/2 lg:w-1/3 md:w-1/2 w-full">
            

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="card overflow-hidden sm:rounded-md rounded-none">
                    <div class="px-6 py-8">
                        <a class='flex justify-center mb-8' href='{{ url('/') }}'>
                            <img class="h-25" src="{{ asset('backend/assets/images/sis-logo.png') }}" alt="">
                        </a>

                        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </div>
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="flex justify-center mb-3 mt-3">
                            <button type="submit" class="btn w-full text-white bg-primary rounded-md"> Sign In </button>
                        </div>
                    </div>
                </div>

                <p class="text-white text-center mt-8">Create an Account ?<a class='font-medium ms-1'
                        href='{{ route('register') }}'>Register</a></p>
            </form>
        </div>
    </div>


@endsection


@push('script')
@endpush


