@extends('auth.app')

@section('title', 'Login | ' . optional($setting)->title ?? 'Chazzle')


@push('style')
    <style>
        body {
            background: linear-gradient(to bottom, #d9e9f8, #60AFFF) !important;
        }
    </style>
@endpush

@section('content')

    <div class="bg-gradient-to-b from-[#dfeaf6] to-[#60AFFF] h-screen w-screen flex justify-center items-center">
        <div>
            <img class="absolute top-0 left-0" src="{{ asset('backend/assets/images/top-include.png') }}" alt="">
        </div>
        <div class="2xl:w-1/2 lg:w-1/3 md:w-1/2 w-full">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="card overflow-hidden sm:rounded-md rounded-none">
                    <div class="px-6 py-8">
                        <a class='flex justify-center mb-8' href='{{ url('/') }}'>
                            <img class="h-22" src="{{ asset($setting->logo ?? 'backend/assets/images/logo-sm.png') }}" alt="">
                        </a>

                        <div class="mb-4">
                            <label class="mb-2" for="LoggingEmailAddress">Email Address</label>
                            <input id="LoggingEmailAddress" name="email" value="{{ old('email') }}" class="form-input" type="email"
                                placeholder="Enter your email">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <label for="loggingPassword">Password</label>
                                @if (Route::has('password.request'))
                                    <a class='text-sm text-primary' href='{{ route('password.request') }}'>Forget Password
                                        ?</a>
                                @endif
                            </div>
                            <input id="loggingPassword" class="form-input" name="password" type="password"
                                placeholder="Enter your password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center mb-4">
                            <input type="checkbox" class="form-checkbox rounded" name="remember" id="checkbox-signin">
                            <label class="ms-2" for="checkbox-signin">{{ __('Remember me') }}</label>
                        </div>

                        <div class="flex justify-center mb-3">
                            <button class="btn w-full text-white bg-primary"> Sign In </button>
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
