@extends('auth.app')

@section('title', 'Login | ' . $setting->title ?? 'PrimeCare')

@push('style')
    <style>
        body {
            background: linear-gradient(to bottom, #d7e9fb, #60AFFF) !important;
        }
    </style>
@endpush

@section('content')

    <div class="bg-gradient-to-b from-[#d7e8f9] to-[#60AFFF] h-screen w-screen flex justify-center items-center">

        <div class="2xl:w-1/2 lg:w-1/3 md:w-1/2 w-full">

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <div class="card overflow-hidden sm:rounded-md rounded-none">
                    <div class="px-6 py-8">
                        <a class='flex justify-center mb-8' href='{{ url('/') }}'>
                            <img class="h-25" src="{{ asset($setting->logo ?? 'uploads/defult-image/logo.png') }}" alt="logo">
                        </a>

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email', $request->email)" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        <div class="flex justify-center mb-3 mt-3">
                            <button type="submit" class="btn w-full text-white bg-primary rounded-md"> Sign In </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>


@endsection


@push('script')
@endpush







