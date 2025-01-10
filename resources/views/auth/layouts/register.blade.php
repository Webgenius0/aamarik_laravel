@extends('auth.app')

@section('title', 'Register | ' . $setting->title ?? 'PrimeCare')

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
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="card overflow-hidden sm:rounded-md rounded-none">
                    <div class="px-6 py-8">
                        <a class='flex justify-center mb-8' href='{{ url('/') }}'>
                            <img class="h-25" src="{{ asset($setting->logo ?? 'backend/assets/images/logo-sm.png') }}" alt="">
                        </a>

                        <div class="mb-4">
                            <label class="mb-2" for="name">User Name</label>
                            <input id="name" name="name" class="form-input" value="{{old('name')}}" type="name"
                                placeholder="Enter your User Name">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <label class="mb-2" for="LoggingEmailAddress">Email Address</label>
                            <input id="LoggingEmailAddress" name="email" value="{{old('email')}}" class="form-input" type="email"
                                placeholder="Enter your email">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <label for="loggingPassword">Password</label>
                            </div>
                            <input id="loggingPassword" class="form-input" name="password" type="password"
                                placeholder="Enter your password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <label for="password_confirmation">Confirm Password</label>
                            </div>
                            <input id="password_confirmation" class="form-input" name="password_confirmation" type="password"
                                placeholder="Enter your confirm password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex justify-center mb-3">
                            <button class="btn w-full text-white bg-primary"> Sign In </button>
                        </div>
                    </div>
                </div>

                <p class="text-white text-center mt-8">Already have an account ?<a class='font-medium ms-1'
                        href='{{ route('login') }}'>Login</a></p>
            </form>
        </div>
    </div>


@endsection


@push('script')
@endpush












{{--
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
