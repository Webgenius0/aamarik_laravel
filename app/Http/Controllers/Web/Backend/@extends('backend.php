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
                            <div>
                                <h1 class="text-center text-lg ">Create Medicine</h1>

                                <div class="flex justify-end">

                                    <a href="{{ route('doctor.index') }}" class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md align-right">
                                        Back
                                    </a>
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


                            <!-- medicine details start -->

                            <div class="flex flex-col md:flex-row items-center md:space-x-4">
                                <div class="flex flex-col md:w-1/2">
                                    <label for="email" class="text-lg font-medium mb-2 md:mb-0">Title</label>
                                    <input name="title" type="text" class="form-input w-full" id="title" placeholder="example@gmail.com" value="">
                                    @error('title')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="flex flex-col md:w-1/2">
                                    <label for="department" class="text-lg font-medium mb-2 md:mb-0">Brand</label>
                                    <input name="brand" type="text" class="form-input w-full" id="brand" placeholder="Brand-name.." value="">

                                    @error('brand')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row items-center md:space-x-4">
                                <div class="flex flex-col md:w-1/2">
                                    <label for="email" class="text-lg font-medium mb-2 md:mb-0">Generic Name</label>
                                    <input name="generic_name" type="text" class="form-input w-full" id="generic_name" placeholder="generic name.." value="">
                                    @error('generic_name')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="flex flex-col md:w-1/2">
                                    <label for="description" class="text-lg font-medium mb-2 md:mb-0">Description</label>
                                    <textarea name="description" class="form-input w-full" id="description" placeholder="generic name.."></textarea>

                                    @error('description')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>


                            <div class="flex flex-col md:flex-row items-center md:space-x-4">
                                <div class="flex flex-col md:w-1/2">
                                    <label for="email" class="text-lg font-medium mb-2 md:mb-0">Form</label>
                                    <input name="form" type="text" class="form-input w-full" id="form" placeholder="generic name.." value="">
                                    @error('form')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="flex flex-col md:w-1/2">
                                    <label for="description" class="text-lg font-medium mb-2 md:mb-0">Doges</label>
                                    <input name="doges" type="text" class="form-input w-full" id="doges" placeholder="Brand-name.." value="" accept="">

                                    @error('description')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="text-blue-500 text-sm mt-1">
                                    Dosage per unit (e.g., 500mg for a tablet or 10ml for syrup)	
                                            </div>
                                </div>

                            </div>

                            <div class="flex flex-col md:flex-row items-center md:space-x-4">
                                <div class="flex flex-col md:w-1/2">
                                    <label for="unit" class="text-lg font-medium mb-2 md:mb-0">Unit</label>
                                    <input name="form" type="text" class="form-input w-full" id="unit" placeholder="Unit...." value="">
                                    @error('unit')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="text-blue-500 text-sm mt-1">
                                    Unit of measurement (e.g., mg, ml, etc.)	
                                            </div>
                                </div>

                                <div class="flex flex-col md:w-1/2">
                                    <label for="description" class="text-lg font-medium mb-2 md:mb-0">Price</label>
                                    <input name="price" type="text" class="form-input w-full" id="price" placeholder="Price....." value="" accept="">

                                    @error('price')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    
                                </div>

                            </div>

                            <div class="flex flex-col md:flex-row items-center md:space-x-4">
                                <div class="flex flex-col md:w-1/2">
                                    <label for="unit" class="text-lg font-medium mb-2 md:mb-0">Quantity</label>
                                    <input name="form" type="text" class="form-input w-full" id="quantity" placeholder="Unit...." value="">
                                    @error('unit')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    
                                </div>

                                <div class="flex flex-col md:w-1/2">
                                    <label for="description" class="text-lg font-medium mb-2 md:mb-0">Stock Quantity</label>
                                    <input name="sotck_quantity" type="text" class="form-input w-full" id="price" placeholder="stock quantity....." value="" accept="">

                                    @error('price')
                                    <span class="text-red-500 block mt-1 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    
                                </div>

                            </div>

                            <div class="flex-col md:w-3/4">
                                <label for="applicationTitle" class="text-lg font-medium mb-2 md:mb-0">Feature</label>
                                <div class="w-full flex items-center">
                                    <!-- Input container -->
                                    <div id="inputContainer" class="w-full">
                                        <!-- Initial Input Field -->
                                        <div class="flex items-center mb-2">
                                            <input type="text" name="feature[]" class="form-input w-full" id="feature" placeholder="Add feature">
                                            <button type="button" class="ml-2 text-xl font-semibold removeBtn hidden">-</button>
                                        </div>
                                    </div>
                                    <!-- Plus button on the same row, aligned to the right -->
                                    <button type="button" id="incrementBtn" class="ml-2 text-xl font-semibold">+</button>
                                </div>

                                <!-- Error message handling -->
                                @error('feature')
                                <span class="text-red-500 block mt-1 text-sm">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
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

    //incriment feature 


    // Add new input field when "+" is clicked
    document.getElementById('incrementBtn').addEventListener('click', function() {
        var container = document.getElementById('inputContainer');
        
        // Create a new input field container
        var newInputContainer = document.createElement('div');
        newInputContainer.classList.add('flex', 'items-center', 'mb-2');
        
        // Create a new input field
        var newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = 'feature[]';  // Allow multiple values to be submitted as an array
        newInput.classList.add('form-input', 'w-full');
        newInput.placeholder = 'Add feature';
        
        // Create a new remove button
        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('ml-2', 'text-xl', 'font-semibold', 'removeBtn');
        removeButton.innerText = '-';  // Set text to "-"
        
        // Add event listener to remove input field when "-" is clicked
        removeButton.addEventListener('click', function() {
            newInputContainer.remove();
        });

        // Append the new input field and remove button to the container
        newInputContainer.appendChild(newInput);
        newInputContainer.appendChild(removeButton);
        
        // Append the new input container to the main container
        container.appendChild(newInputContainer);
    });

    // Show/hide remove button dynamically based on the presence of input fields
    document.getElementById('inputContainer').addEventListener('click', function(event) {
        if (event.target.tagName.toLowerCase() === 'input') {
            var removeBtns = document.querySelectorAll('.removeBtn');
            removeBtns.forEach(function(btn) {
                btn.classList.remove('hidden'); // Show the remove button
            });
        }
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
</script>
@endpush





function editFAQ(id) {
        let route = '';
        route = route.replace(':id', id);
        $.ajax({
            type: "GET",
            url: route,
            success: function(resp) {
                console.log(resp.data); // Ensure the response data structure is as expected


                if (resp.success === true) {
                    $('#faq_id').val(resp.data.id);
                    $('#question').val(resp.data.question); // Set the question field
                    $('#answer').val(resp.data.answer); // Set the answer field
                    $('#type').val(resp.data.type); // Set the type dropdown

                    $('#modalTitle').html('Update FAQ');
                    $('#modalOverlay').show().addClass('modal-open');
                } else if (resp.errors) {
                    flasher.error(resp.errors[0]);
                } else {
                    flasher.warning(resp.message);
                }
            },
            error: function(error) {
                flasher.error('Error while fetching data.');
            }
        });
        // $('#modalTitle').html('Update FAQ');
        // $('#modalOverlay').show().addClass('modal-open');
    }