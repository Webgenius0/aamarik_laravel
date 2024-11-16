@extends('backend.app')
{{-- Title --}}
@section('title', 'Additional Setting')

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
                            <h6 class="fs-17 fw-semi-bold mb-0">Social Config</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-9 col-xxl-9 mx-auto">
                                    <div class="tab-content">
                                        {{-- Mail Setting Tab --}}
                                        @can('setting update')
                                            <div
                                                class="tab-pane fade show active">
                                                <div class="card">
                                                    <div
                                                        class="card-header d-flex justify-content-between align-content-center">
                                                        <div class="d-flex align-content-center">
                                                            <strong>Social Setting</strong>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <form action="{{ route('social.setting.update') }}" class="password_form"
                                                            method="POST">
                                                            @csrf

                                                            {{--Google Configuration--}}
                                                            <h3>Google Configuration</h3>
                                                            <div class="form-group mb-3 px-1">
                                                                <label for="google_client_id"><b>Client Id</b></label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control"
                                                                        id="google_client_id" name="google_client_id"
                                                                        value="{{ env('GOOGLE_CLIENT_ID') }}">
                                                                </div>
                                                                @error('google_client_id')
                                                                    <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-3 px-1">
                                                                <label for="google_client_secret"><b>Client Secret</b></label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" id="google_client_secret"
                                                                        name="google_client_secret" value="{{ env('GOOGLE_CLIENT_SECRET') }}">
                                                                </div>
                                                                @error('google_client_secret')
                                                                    <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-3 px-1">
                                                                <label for="google_redirect_url"><b>Redirect URL</b></label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" id="google_redirect_url"
                                                                        name="google_redirect_url" value="{{ env('GOOGLE_REDIRECT_URI') }}">
                                                                </div>
                                                                @error('google_redirect_url')
                                                                    <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                           {{--Facebook Configuration--}}
                                                            <h3 class="mt-5">Facebook Configuration</h3>
                                                            <div class="form-group mb-3 px-1">
                                                                <label for="facebook_client_id"><b>Client Id</b></label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control"
                                                                           id="facebook_client_id" name="facebook_client_id"
                                                                           value="{{ env('FACEBOOK_CLIENT_ID') }}">
                                                                </div>
                                                                @error('facebook_client_id')
                                                                <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-3 px-1">
                                                                <label for="facebook_client_secret"><b>Client Secret</b></label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" id="facebook_client_secret"
                                                                           name="facebook_client_secret" value="{{ env('FACEBOOK_CLIENT_SECRET') }}">
                                                                </div>
                                                                @error('facebook_client_secret')
                                                                <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-3 px-1">
                                                                <label for="facebook_redirect_url"><b>Redirect URL</b></label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" id="facebook_redirect_url"
                                                                           name="facebook_redirect_url" value="{{ env('FACEBOOK_REDIRECT_URI') }}">
                                                                </div>
                                                                @error('facebook_redirect_url')
                                                                <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            {{--Twitter Configuration--}}
                                                            <h3 class="mt-5">Twitter Configuration</h3>
                                                            <div class="form-group mb-3 px-1">
                                                                <label for="twitter_client_id"><b>Client Id</b></label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control"
                                                                           id="twitter_client_id" name="twitter_client_id"
                                                                           value="{{ env('TWITTER_CLIENT_ID') }}">
                                                                </div>
                                                                @error('twitter_client_id')
                                                                <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-3 px-1">
                                                                <label for="twitter_client_secret"><b>Client Secret</b></label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" id="twitter_client_secret"
                                                                           name="twitter_client_secret" value="{{ env('TWITTER_CLIENT_SECRET') }}">
                                                                </div>
                                                                @error('twitter_client_secret')
                                                                <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group mb-3 px-1">
                                                                <label for="twitter_redirect_url"><b>Redirect URL</b></label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" id="twitter_redirect_url"
                                                                           name="twitter_redirect_url" value="{{ env('TWITTER_REDIRECT_URI') }}">
                                                                </div>
                                                                @error('twitter_redirect_url')
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
                                        @endcan
                                    </div>
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
