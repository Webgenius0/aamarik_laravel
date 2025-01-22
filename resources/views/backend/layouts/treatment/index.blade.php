@extends('backend.app')

@section('title', 'Medicine| ' . $setting->title ?? 'PrimeCare')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
<!-- Dropify CSS for file input styling -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
<!-- Tailwind CSS for layout and styling -->
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<style>
    .dataTables_wrapper .dataTables_length select {
        width: 68px;
        font-size: 14px;
        border: 0;
        border-radius: 0px;
        padding: 5px;
        background-color: transparent;
        padding: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: rgb(60 186 222 / var(--tw-bg-opacity));
        color: white !important;
        border: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background-color: rgb(32 183 153 / var(--tw-bg-opacity));
        color: white !important;
        border: none;
    }

    .dataTables_wrapper .dataTables_filter input {
        margin-left: 11px;
    }

    td, td p {
        font-size: 15px;
    }

    #data-table {
        border: 0;
        margin-bottom: 24px;
    }

    div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-confirm) {
        background-color: #7066e0 !important;
    }

    div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-cancel) {
        background-color: #6e7881 !important;
    }

    #modalOverlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 9999;
    }

    #modal {
        position: fixed;
        width: 90%;
        top: 55%;
        left: 50%;
        text-align: center;
        background-color: #fafafa;
        box-sizing: border-box;
        opacity: 0;
        transform: translate(-50%, -50%);
        transition: all 300ms ease-in-out;
        max-height: 80%;
        overflow-y: auto;
        padding: 20px;
    }

    #modalOverlay.modal-open #modal {
        opacity: 1;
        top: 50%;
    }

    #modal .modal-content {
        max-height: 70vh;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<main class="p-6">
</main>

{{-- modal start --}}
<form id="createUpdateForm" class="max-w-6xl w-full mx-auto space-y-4" enctype="multipart/form-data">
    
    <input type="hidden" name="id" id="medicine_id">

    <!-- Step 1: Title and Avatar -->
    <div class="step step-1">
        <div class="flex items-center justify-center">
            <h1 class="h1">Create Treatment</h1>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="name" class="text-lg font-medium mb-2">Title</label>
            <input name="name" type="text" class="form-input w-full" id="name" placeholder="Enter name.." value="{{ old('title') }}">
            @error('title') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
        </div>

        <div class="flex flex-col md:w-full">
            <label for="avatar" class="text-lg font-medium mb-2">Avatar</label>
            <input name="avatar" type="file" class="form-input w-full dropify" id="avatar" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
            @error('avatar') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
            <div class="text-blue-500 text-sm mt-1">Recommended to 32x32 px (jpeg, png, jpg, gif, ico, bmp, svg).</div>
        </div>
    </div>

    <!-- Step 2: Treatment Category -->
    <div class="step step-2 hidden">
        <div class="flex items-center justify-center">
            <h1 class="h1">Treatment Category</h1>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="icon" class="text-lg font-medium mb-2">Icon</label>
            <input name="icon" type="file" class="form-input w-full dropify" id="icon" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
            @error('icon') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
            <div class="text-blue-500 text-sm mt-1">Recommended to 32x32 px (jpeg, png, jpg, gif, ico, bmp, svg).</div>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="title" class="text-lg font-medium mb-2">Title</label>
            <input name="title" type="text" class="form-input w-full" id="title" placeholder="Title" value="{{ old('title') }}">
            @error('title') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>

    <!-- Step 3: Category Details -->
    <div class="step step-3 hidden">
        <div class="flex items-center justify-center">
            <h1 class="h1">Category Details</h1>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="icon" class="text-lg font-medium mb-2">Icon</label>
            <input name="icon" type="file" class="form-input w-full dropify" id="icon" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
            @error('icon') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
            <div class="text-blue-500 text-sm mt-1">Recommended to 32x32 px (jpeg, png, jpg, gif, ico, bmp, svg).</div>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="description" class="text-lg font-medium mb-2">Title</label>
            <textarea name="title" class="form-input w-full" id="title" placeholder="title">{{ old('description') }}</textarea>
            @error('title') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
<!-- Step 4: Category Details -->
    
    <div class="step step-4 hidden">
        <div class="flex items-center justify-center">
            <h1 class="h1">Category Items</h1>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="icon" class="text-lg font-medium mb-2">Icon</label>
            <input name="icon" type="file" class="form-input w-full dropify" id="icon" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
            @error('icon') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
            <div class="text-blue-500 text-sm mt-1">Recommended to 32x32 px (jpeg, png, jpg, gif, ico, bmp, svg).</div>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="title" class="text-lg font-medium mb-2">Title</label>
            <input name="title" class="form-input w-full" id="description" placeholder="Description">{{ old('description') }}</input>
            @error('description') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
    <!-- Step 5: Category Details -->

    <div class="step step-5 hidden">
        <div class="flex items-center justify-center">
            <h1 class="h1">Category abouts</h1>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="icon" class="text-lg font-medium mb-2">Icon</label>
            <input name="icon" type="file" class="form-input w-full dropify" id="icon" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
            @error('icon') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
            <div class="text-blue-500 text-sm mt-1">Recommended to 32x32 px (jpeg, png, jpg, gif, ico, bmp, svg).</div>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="description" class="text-lg font-medium mb-2">Description</label>
            <textarea name="description" class="form-input w-full" id="description" placeholder="Description">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
    <!-- Step 6: Category Details -->
    <div class="step step-6 hidden">
        <div class="flex items-center justify-center">
            <h1 class="h1">Faq</h1>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="icon" class="text-lg font-medium mb-2">Icon</label>
            <input name="icon" type="file" class="form-input w-full dropify" id="icon" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
            @error('icon') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
            <div class="text-blue-500 text-sm mt-1">Recommended to 32x32 px (jpeg, png, jpg, gif, ico, bmp, svg).</div>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="description" class="text-lg font-medium mb-2">Description</label>
            <textarea name="description" class="form-input w-full" id="description" placeholder="Description">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
    <!-- Step 7: Category Details -->
    <div class="step step-7 hidden">
        <div class="flex items-center justify-center">
            <h1 class="h1">Medicines</h1>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="icon" class="text-lg font-medium mb-2">Icon</label>
            <input name="icon" type="file" class="form-input w-full dropify" id="icon" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg">
            @error('icon') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
            <div class="text-blue-500 text-sm mt-1">Recommended to 32x32 px (jpeg, png, jpg, gif, ico, bmp, svg).</div>
        </div>
        <div class="flex flex-col md:w-full">
            <label for="description" class="text-lg font-medium mb-2">Description</label>
            <textarea name="description" class="form-input w-full" id="description" placeholder="Description">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
    <!-- Step 8: Category Details -->
    <div class="step step-8 hidden">
        <div class="flex items-center justify-center">
            <h1 class="h1">Assestment</h1>
        </div>
       
        <div class="flex flex-col md:w-full">
            <label for="description" class="text-lg font-medium mb-2">Assesment</label>
            <textarea name="assesment" class="form-input w-full" id="assesment" placeholder="assesment">{{ old('assesment') }}</textarea>
            @error('assesment') <span class="text-red-500 block mt-1 text-sm"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
     
    <!-- Buttons -->
    <div class="flex justify-between mt-4">
        <button type="button" id="prevBtn" class="btn bg-gray-500 text-white py-2 px-4 rounded-lg font-semibold hidden">Previous</button>
        <button type="button" id="nextBtn" class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold">Next</button>
        <button type="submit" id="submitBtn" class="btn bg-green-500 text-white py-2 px-4 rounded-lg font-semibold hidden">Submit</button>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

<script>
   $(document).ready(function() {
    // Initialize Dropify
    $('.dropify').dropify();

    let currentStep = 1; // Start from step 1

    // Show step based on currentStep
    // Show step based on currentStep
function showStep(step) {
    $('.step').addClass('hidden'); // Hide all steps
    $('.step-' + step).removeClass('hidden'); // Show current step

    // Reinitialize Dropify for the current step
    reinitializeDropify();

    // Show/hide Previous, Next, and Submit buttons
    if (step === 1) {
        $('#prevBtn').addClass('hidden');
        $('#nextBtn').removeClass('hidden');
        $('#submitBtn').addClass('hidden');
    } else if (step >= 2 && step <= 6) {
        $('#prevBtn').removeClass('hidden');
        $('#nextBtn').removeClass('hidden');
        $('#submitBtn').addClass('hidden');
    } else if (step === 8) {
        $('#prevBtn').removeClass('hidden');
        $('#nextBtn').addClass('hidden');
        $('#submitBtn').removeClass('hidden');
    }
}

    // Function to reinitialize Dropify
    function reinitializeDropify() {
        // Reinitialize Dropify for file inputs in the visible step
        $('.step-' + currentStep + ' .dropify').dropify();
    }

    // Go to the next step
    // Go to the next step
$('#nextBtn').click(function() {
    // Ensure we are not going beyond step 8
    if (currentStep < 8) {
        currentStep++;
        if (currentStep === 8) {
            // If it's the final step, hide "Next" and show "Submit"
            $('#nextBtn').addClass('hidden');
            $('#submitBtn').removeClass('hidden');
        }
        showStep(currentStep);
    }
});


    // Go to the previous step
    $('#prevBtn').click(function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Handle form submission
    $('#createUpdateForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var url = "{{ route('medicine.store') }}"; // Default route for creation

        var method = "POST"; // Default method for creation
        if ($('#medicine_id').val()) {
            url = "{{ route('medicine.update', ':id') }}".replace(':id', $('#medicine_id').val());
            method = "PUT"; // Use PUT for updates
        }

        $.ajax({
            type: method,
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {
                // Handle success response
                alert(resp.message);
                $('#createUpdateForm')[0].reset();
                showStep(1); // Reset to step 1
            },
            error: function(error) {
                // Handle error
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Initialize the first step
    showStep(currentStep);
});

</script>
@endpush
