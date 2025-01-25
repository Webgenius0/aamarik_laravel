@extends('backend.app')
{{-- Title --}}
@section('title', 'Setting')


@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<style>

</style>
@endpush

@section('content')

@php
//$cms = App\Models\Cms::where('type','banner')->first();
//$personalize = App\Models\Cms::where('type','personalized')->first();
@endphp
<div class="content-wrapper">
    <div class="main-content">
        <div class="body-content">
            <div class="decoration blur-2"></div>
            <div class="decoration blur-3"></div>
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="p-4 bg-white rounded-lg shadow-md mt-2">
                    <div class="space-y-4">
                        <form  class="max-w-6xl w-full mx-auto space-y-4"
                             enctype="multipart/form-data" id="departmentForm">
                            @csrf

                            <div class="flex-col md:flex-row">
                                <h1 class="text-center text-lg ">Create Doctor Department</h1>

                                <label for="applicationTitle"
                                    class=" text-lg font-medium mb-2 md:mb-0 md:w-1/3">Department
                                </label>
                                <div class="w-full">
                                    <input type="text" name="department_name" class="form-input w-full" id="department_name"
                                        value="" placeholder="Department..">
                                    @error('department_name')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-center mt-4">
                                <button type="button"
                                    class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold">Reset</button>
                                <button type="submit"
                                    class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold ml-2">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/.body content-->
    </div>



</div>
@endsection

{{-- Push Script //partials/script.blade.php (scripts stacked) --}}
@push('scripts')
<!-- Tailwind CSS CDN -->
<script src="https://unpkg.com/tailwindcss-jit-cdn@2.2.19/dist/tailwind.min.js"></script>
{{-- Dropify --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
{{--Flashar--}}
<script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
{{-- Ck Editor --}}
<script src="{{ asset('Backend/plugins/tinymc/tinymce.min.js') }}"></script>

<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        $('.dropify').dropify();
    });


    $(document).ready(function() {
        const flasher = new Flasher({
            selector: '[data-flasher]',
            duration: 3000,
            options: {
                position: 'top-center',
            },
        });
    });


    //for store department

    $(document).ready(function(){
        $('#departmentForm').on('submit',function(e){
            e.preventDefault();
            let departmentName=$('#department_name').val();
            $.ajax({
            url:"{{route('doctor.department.store')}}",
            method:"POST",
            data:{
                department_name:departmentName,
                _token:"{{csrf_token()}}"
            },
            success: function(response){
                if (response.success) {
                    $('#department_name').val('');
                    flasher.success('Department Added Successfully.');
                } else {
                    flasher.error(response.message || 'Something went wrong.');
                }
            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON ? xhr.responseJSON.message : error;
                flasher.error(errorMessage || 'Something went wrong.');
            }
            });
        });
    });
</script>
@endpush