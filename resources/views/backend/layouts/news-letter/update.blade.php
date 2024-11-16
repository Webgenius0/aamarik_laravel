@extends('backend.app')

@section('title', 'Edit News Letter | ' . $setting->title ?? 'SIS')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <style>
        /* Write Css Under Backend */
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        .dropify-wrapper {
            border-radius: 0.8rem !important;
        }
        .text-danger strong{
            font-size: 14px;
        }
        .dataTables_wrapper .dataTables_length select {
            width: 68px;
            font-size: 14px;
        }
    </style>
@endpush

@section('content')
    <main class="p-6">
        <div class="card bg-white overflow-hidden">
            <div class="p-4">
                <div class="overflow-x-auto custom-scroll">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">

                            <div class="mb-4 bg-white rounded-xl shadow-lg">
                                <div class="relative px-4 py-3 border-b flex justify-between align-middle">
                                    <h6 class="text-lg font-semibold mb-0">Create News Letter
                                </h6>
                                    <a href="{{ route('news.letter.index') }}"
                                        class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">View All</a>
                                </div>
                                <div class="p-4">
                                    <form action="{{ route('news.letter.update',['id'=> $newsLetter->id]) }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                            <div class=" pe-3 mb-3">
                                                <label class="block text-sm font-medium mb-2" for="PDF">PDF</label>
                                                <input type="file" accept="application/pdf" data-default-file="{{asset('backend/assets/images/pdf_preview.jpg')}}" name="pdf" id="pdf" class="form-input w-full  dropify dropify-wrapper1 .dropify-preview dropify-render img">
                                                @error('file')
                                                    <span class="text-red-500 text-sm" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        <div class="mt-10">
                                            <button type="submit"
                                                class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">
                                                Submit
                                            </button>
                                            <a href="{{ route('news.letter.index') }}" type="button"
                                                class="btn bg-danger text-white py-2 px-5 hover:bg-dark rounded-md">
                                                Back
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


@push('scripts')
    {{-- Dropify --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush
