@extends('backend.app')
{{-- Title --}}
@section('title', 'Stripe Setting | ' . $setting->title ?? 'Unapologetic Sports')

{{-- Push Style --}}
@push('style')
@endpush

@section('content')

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
                            <h6 class="fs-17 fw-semi-bold mb-0">Payment Gateway Setting(Stripe)</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-10 col-xxl-10">
                                    <form action="{{ route('stripe.setting.update') }}" class="password_form"
                                        method="POST">
                                        @csrf
                                        <div class="form-group mb-3 px-1">
                                            <label for="stripe_public_key"><b>STRIPE PUBLIC KEY</b></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="stripe_public_key"
                                                    name="stripe_public_key" value="{{ env('STRIPE_PUBLIC_KEY') }}">
                                            </div>
                                            @error('stripe_public_key')
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3 px-1">
                                            <label for="stripe_secrate_key"><b>STRIPE SECRATE KEY</b></label>
                                            <div class="input-group input-group-sm">
                                                <input type="password" class="form-control" id="stripe_secrate_key"
                                                    name="stripe_secrate_key" value="{{ env('STRIPE_SECRATE_KEY') }}">
                                            </div>
                                            @error('stripe_secrate_key')
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
