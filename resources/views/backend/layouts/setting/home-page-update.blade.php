@extends('backend.app')
{{-- Title --}}
@section('title', 'Additional Setting')

{{-- Push Style --}}
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        .list-group-item.active {
            z-index: 2;
            color: var(--bs-list-group-active-color);
            background-color: var(--bs-primary) !important;
            border: 1px solid var(--bs-primary) !important;
            font-weight: 700;
        }
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
                            <h6 class="fs-17 fw-semi-bold mb-0">Home Page Contents</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-10 col-xxl-10">
                                    <form action="{{ route('admin.home.page.update') }}" class="password_form" method="POST">
                                        @csrf
                                        <div class="form-group mb-3 px-1">
                                            <label for="hero_title"><b>Hero Title</b></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="hero_title" name="hero_title"
                                                    value="{{ $homePageContent ? $homePageContent->hero_title : '' }}">
                                            </div>
                                            @error('hero_title')
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3 px-1">
                                            <label for="hero_description"><b>Mail Host</b></label>
                                            <div class="">
                                                <textarea name="hero_description" id="hero_description">{!! $homePageContent ? $homePageContent->hero_description : '' !!}</textarea>
                                            </div>
                                            @error('hero_description')
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
   {{-- Ck Editor --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#hero_description'), {
                removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'Image', 'ImageCaption', 'ImageStyle',
                    'ImageToolbar', 'ImageUpload', 'MediaEmbed'
                ],
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
