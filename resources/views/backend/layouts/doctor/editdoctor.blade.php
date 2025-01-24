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
                        <form class="max-w-6xl w-full mx-auto space-y-4"
                            method="POST" enctype="multipart/form-data" id="doctorForm">
                            @csrf
                            <div class="flex justify-end">
                                <a href="{{ route('doctor.index') }}" class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md align-right">
                                    Back
                                </a>
                            </div>
                            <div class="flex-col md:flex-row">
                                <h1 class="text-center text-lg ">Create Doctor</h1>

                                <label for="applicationTitle"
                                    class=" text-lg font-medium mb-2 md:mb-0 md:w-1/3">User Name
                                </label>
                                <div class="w-full">
                                    <input type="text" name="name" class="form-input w-full" id="name"
                                        value="" placeholder="User Name">
                                    @error('name')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row items-center md:space-x-4">
                                <div class="flex flex-col md:w-1/2">
                                    <label for="email" class="text-lg font-medium mb-2 md:mb-0">Email</label>
                                    <input name="email" type="email" class="form-input w-full" id="email" placeholder="example@gmail.com" value="">
                                    @error('email')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="flex flex-col md:w-1/2">
                                    <label for="department" class="text-lg font-medium mb-2 md:mb-0">Department</label>
                                    <select name="department" id="department" class="form-select w-full">
                                        <option value="">Select Department..</option>
                                    </select>

                                    @error('department')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>



                            {{-- favicon --}}
                            <div class="flex flex-row space-x-8">
                                <div class="w-full">
                                    <div class="flex-col md:flex-row">
                                        <label class="text-lg font-medium mb-2 md:mb-0 md:w-1/3">Image</label>
                                        <div class="w-full">
                                            <input name="avatar"
                                                class="form-input w-full dropify dropify-wrapper1 .dropify-preview dropify-render img"
                                                data-height="300" type="file"
                                                {{-- accept=".jpg, .png, image/jpeg, image/png" --}}
                                                accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg"
                                                data-default-file="">
                                            @error('avatar')
                                            <span class="text-red-500 block mt-1 text-sm">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                            <div class="text-blue-500 text-sm mt-1">
                                                Recommended to 32x32 px(jpeg,png,jpg,gif,ico,webp,bmp,svg).
                                            </div>
                                        </div>
                                    </div>
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



    $(document).ready(function() {
        $.ajax({
            url: "{{ route('doctor.department') }}",
            method: "GET",
            success: function(data) {
                $('#department').empty().append('<option value="">Select Department..</option>');
                $.each(data, function(index, department) {
                    $('#department').append('<option value="' + department.department_name  + '">' + department.department_name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });

    });
    //doctor store ajax
    $(document).ready(function() {
        $('#doctorForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('doctor.store') }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(resp) {
                    console.log(resp);
                    if (resp.success === true) {
                        flasher.success(resp.message);
                        setTimeout(function() {
                            window.location.href = "{{ route('doctor.index') }}";
                        }, 2000);
                    } else if (resp.errors) {
                        $.each(resp.errors, function(key, value) {
                            flasher.error(value);
                        });
                    } else {
                        flasher.error(resp.message);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });



    // Function to edit a doctor
function editDoctor(id) {
    // URL to get doctor details (replace with your actual route)
    let route = '{{ route("doctor.edit", ":id") }}';
    route = route.replace(':id', id);  // Replace `:id` with the actual doctor ID

    $.ajax({
        type: "GET",  // Make a GET request to fetch doctor data
        url: route,
        success: function(response) {
            if (response.success) {
                // Populate the form with the doctor's data
                $('#doctor_id').val(response.data.id);  // Set the doctor ID
                $('#name').val(response.data.name);  // Populate name field
                $('#email').val(response.data.email);  // Populate email field
                $('#department').val(response.data.department);  // Populate department field

                // For avatar, handle the file input and preview if necessary
                if (response.data.avatar) {
                    // Set the avatar input to the correct file path
                    $('.dropify').dropify('resetPreview').dropify('clearElement').val('').trigger('change');
                    $('.dropify').dropify('setFile', response.data.avatar);
                }

                // Optionally open modal if required, or use the form directly without modal
                // If you are using a modal, you can show it here
                // $('#editDoctorModal').modal('show'); // If you have a modal
            } else {
                alert('Doctor not found!');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching doctor data:', error);
            alert('An error occurred while fetching doctor data.');
        }
    });
}

</script>
@endpush