@extends('backend.app')
{{-- Title --}}
@section('title', 'SMTP Mail server setting')

{{-- Push Style --}}
@push('style')
    <style>
       
    </style>
@endpush

@section('content')

    @php
        $setting = App\Models\Setting::first();
    @endphp

    <div class="content-wrapper">
        <div class="main-content">
            <!-- Star navbar -->
            @include('Backend.partials.topbar')
            <!-- End /. navbar -->
            <div class="body-content">
                <div class="decoration blur-2"></div>
                <div class="decoration blur-3"></div>
                <div class="container-xxl">
                    <div class="card">
                        <div class="card-header position-relative">
                            <h6 class="fs-17 fw-semi-bold mb-0">SMTP Mail Server Setting</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-10 col-xxl-10">
                                    <form action="{{ route('mail.setting.update') }}" class="password_form"
                                          method="POST">
                                        @csrf
                                        <div class="form-group mb-3 px-1">
                                            <label for="mail_mailer"><b>Mail Mailer</b></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control"
                                                       id="mail_mailer" name="mail_mailer"
                                                       value="{{ env('MAIL_MAILER') }}">
                                            </div>
                                            @error('mail_mailer')
                                            <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3 px-1">
                                            <label for="mail_host"><b>Mail Host</b></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="mail_host"
                                                       name="mail_host" value="{{ env('MAIL_HOST') }}">
                                            </div>
                                            @error('mail_host')
                                            <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3 px-1">
                                            <label for="mail_port"><b>Mail Port</b></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="mail_port"
                                                       name="mail_port" value="{{ env('MAIL_PORT') }}">
                                            </div>
                                            @error('mail_port')
                                            <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3 px-1">
                                            <label for="mail_username"><b>Mail Username</b></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control"
                                                       id="mail_username" name="mail_username"
                                                       value="{{ env('MAIL_USERNAME') }}">
                                            </div>
                                            @error('mail_username')
                                            <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3 px-1">
                                            <label for="mail_password"><b>Mail Password</b></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control"
                                                       id="mail_password" name="mail_password"
                                                       value="{{ env('MAIL_PASSWORD') }}">
                                            </div>
                                            @error('mail_password')
                                            <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3 px-1">
                                            <label for="mail_encryption"><b>Mail Encryption</b></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control"
                                                       id="mail_encryption" name="mail_encryption"
                                                       value="{{ env('MAIL_ENCRYPTION') }}">
                                            </div>
                                            @error('mail_encryption')
                                            <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3 px-1">
                                            <label for="mail_from_address"><b>Mail From Address</b></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control"
                                                       id="mail_from_address" name="mail_from_address"
                                                       value="{{ env('MAIL_FROM_ADDRESS') }}">
                                            </div>
                                            @error('mail_from_address')
                                            <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary mx-1 px-4">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.body content-->
        </div>
        <!--/.main content-->
        @include('Backend.partials.footer')
        <!--/.footer content-->
        <div class="overlay"></div>
    </div>
@endsection

{{-- Push Script --}}
@push('script')
    
@endpush
