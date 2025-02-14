




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

        td,
        td p {
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
            /* Add this line to limit the height */
            overflow-y: auto;
            /* Allow scrolling if content overflows */
            padding: 20px;
            /* Optional: Adjust padding as needed */
        }

        #modalOverlay.modal-open #modal {
            opacity: 1;
            top: 50%;
        }

        #modal .modal-content {
            max-height: 70vh;
            /* Optional: Control the content area's max height */
            overflow-y: auto;
            /* Ensure content scrolls if it exceeds the height */
        }



        .image-item {
            width: calc(50% - 1rem); /* For more precise control over image width */
            margin-bottom: 1rem;
        }

        .image-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem; /* Adjust the gap to your liking */
        }
    </style>
@endpush

@section('content')
    <main class="p-6">
        <div class="card bg-white overflow-hidden">
            <div class="card-header">
                <div class="flex justify-between align-middle">
                    <h3 class="card-title">Medicine List</h3>
                    <div>
                        <button class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md"
                                onclick="ShowCreateUpdateModal()">
                            Add Medicine
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto custom-scroll">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table id="data-table" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        S/L
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Brand
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Stock Quantity
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Expiry Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Dynamic content goes here -->
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- modal start --}}
    <div id="modalOverlay" style="display:none;">
        <div id="modal" class="rounded-2xl max-w-2xl">

            <div class="px-8 py-4">
                <form id="createUpdateForm" class="max-w-6xl w-full mx-auto space-y-4"
                      enctype="multipart/form-data" >

                    <h1 class="flex align-left h1">Add/Edit Medicine</h1>
                    <input type="hidden" name="id" id="medicine_id"  value="">

                    {{-- favicon --}}
                    <div class="flex flex-col md:flex-row space-x-8">
                        <div class="w-full">
                            <div class="flex-col md:flex-row">
                                <label class="text-lg font-medium mb-2 md:mb-0 md:w-1/3 flex align-left">Images</label>
                                <div id="avatarContainer" class="w-full">
                                    <!-- Dynamic Image Input Fields Will Appear Here -->
                                    <div class="flex items-center mb-2">
                                        <input name="avatar[]" class="form-input w-full dropify" type="file"
                                               accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg" data-height="300">
                                        <button type="button" class="ml-2 text-xl font-semibold removeBtn hidden">-</button>
                                    </div>
                                </div>
                                <button type="button" id="incrementBtn" class="ml-2 text-xl font-semibold">+</button>

                                @error('avatar')
                                <span class="text-red-500 block mt-1 text-sm">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>
                    </div>



                    <!-- medicine details start -->

                    <div class="flex flex-col md:flex-row items-center md:space-x-4">
                        <div class="flex flex-col md:w-1/2">
                            <label for="email" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Title</label>
                            <input name="title" type="text" class="form-input w-full" id="title" placeholder="Enter medicine title (e.g., Paracetamol)" value="{{ old('title') }}">
                            @error('title')
                            <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>

                        <div class="flex flex-col md:w-1/2">
                            <label for="department" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Brand</label>
                            <input name="brand" type="text" class="form-input w-full" id="brand" placeholder="Enter brand name (e.g., Pfizer)" value="{{ old('brand') }}">

                            @error('brand')
                            <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center md:space-x-4">
                        <div class="flex flex-col md:w-1/2">
                            <label for="email" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Generic Name</label>
                            <input name="generic_name" type="text" class="form-input w-full" id="generic_name" placeholder="Enter generic name (e.g., Acetaminophen)" value="{{ old('generic_name') }}">
                            @error('generic_name')
                            <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>

                        <div class="flex flex-col md:w-1/2">
                            <label for="description" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Description</label>
                            <textarea name="description" class="form-input w-full" id="description" placeholder="Enter a brief description of the medicine" value="{{ old('description') }}"></textarea>

                            @error('description')
                            <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>

                    </div>


                    <div class="flex flex-col md:flex-row items-center md:space-x-4">
                        <div class="flex flex-col md:w-1/2">
                            <label class="text-lg font-medium mb-2 md:mb-0 flex align-left">Form</label>
                            <select name="form" id="form" class="form-input w-full">
                                <option value="" disabled selected>Select the form of medicine</option>
                                <option value="tablet" {{ old('form') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="liquid" {{ old('form') == 'liquid' ? 'selected' : '' }}>Liquid</option>
                                <option value="capsule" {{ old('form') == 'capsule' ? 'selected' : '' }}>Capsule</option>
                                <option value="inhaler" {{ old('form') == 'inhaler' ? 'selected' : '' }}>Inhaler</option>
                                <option value="syrup" {{ old('form') == 'syrup' ? 'selected' : '' }}>Syrup</option>
                                <option value="ointment" {{ old('form') == 'ointment' ? 'selected' : '' }}>Ointment</option>
                            </select>
                            @error('form')
                            <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>


                        <div class="flex flex-col md:w-1/2">
                            <label for="description" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Dosage</label>
                            <input name="dosage" type="text" class="form-input w-full" id="dosage" placeholder="Enter dosage" value="{{ old('dosage') }}" >

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
                            <label for="unit" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Unit</label>
                            <input name="unit" type="text" class="form-input w-full" id="unit" placeholder="Enter unit (e.g., mg, ml, etc.)" value="{{old('unit')}}">
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
                            <label for="description" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Price</label>
                            <input name="price" type="text" class="form-input w-full" id="price" placeholder="Enter price (e.g., 10.99)" value="{{old('price')}}" accept="">

                            @error('price')
                                <span class="text-red-500 block mt-1 text-sm">
                                  <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>



                    <div class="flex flex-col md:flex-row items-center md:space-x-4">
                        <div class="flex flex-col md:w-1/2">
                            <label for="buying_price" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Buying Price</label>
                            <input name="buying_price" type="text" class="form-input w-full" id="buying_price" placeholder="Enter buying price (e.g., 10.99)" value="{{old('buying_price')}}">
                            @error('buying_price')
                                <span class="text-red-500 block mt-1 text-sm">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="flex flex-col md:w-1/2">
                            <label for="expiry_date" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Expiry Date</label>
                            <input name="expiry_date" type="datetime-local" class="form-input w-full" id="expiry_date" value="{{old('expiry_date')}}" >

                            @error('expiry_date')
                            <span class="text-red-500 block mt-1 text-sm">
                                  <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    <div class="flex flex-col md:flex-row items-center md:space-x-4">
                        <div class="flex flex-col md:w-1/2">
                            <label for="unit" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Quantity</label>
                            <input name="quantity" type="text" class="form-input w-full" id="quantity" placeholder="Enter package details (e.g., 1 strip = 20 tablets)" value="{{old('quantity')}}">
                            @error('quantity')
                            <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror

                        </div>

                        <div class="flex flex-col md:w-1/2">
                            <label for="description" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Stock Quantity</label>
                            <input name="stock_quantity" type="text" class="form-input w-full" id="stock_quantity" placeholder="Enter available stock (e.g., 50 strips in stock)" value="{{old('stock_quantity')}}" accept="">

                            @error('stock_quantity')
                            <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror

                        </div>

                    </div>

                    <div class="flex-col md:w-3/4">
                        <label for="applicationTitle" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Feature</label>
                        <div class="w-full flex items-center">
                            <!-- Input container -->
                            <div id="inputContainer" class="w-full">
                                <!-- Initial Input Field -->
                                <div class="flex items-center mb-2">
                                    <input type="text" name="feature[]" class="form-input w-full" id="feature" placeholder="Enter key feature (e.g., Fast pain relief)">
                                    <button type="button"  onclick="removeFeatureField(this)" class="ml-2 text-xl font-semibold removeBtn hidden">-</button>
                                </div>
                            </div>
                            <!-- Plus button on the same row, aligned to the right -->
                            <button type="button" id="incrementBtntext" class="ml-2 text-xl font-semibold">+</button>
                        </div>

                        <!-- Error message handling -->
                        @error('feature')
                        <span class="text-red-500 block mt-1 text-sm">
                        <strong>{{ $message }}</strong>
                    </span>
                        @enderror
                    </div>
                    <div class="flex justify-end mt-4 space-x-2">
                        <button id="close" type="button"
                                class="bg-red-600 text-white py-2 px-4 rounded-lg font-semibold flex items-center">Close</button>

                        <button type="submit"
                                class="bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold flex items-center">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <!-- Tailwind CSS CDN -->
    <script src="https://unpkg.com/tailwindcss-jit-cdn@2.2.19/dist/tailwind.min.js"></script>
    {{-- Dropify --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    {{--Flashar--}}
    <script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
    {{-- Ck Editor --}}
    <script src="{{ asset('Backend/plugins/tinymc/tinymce.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



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
            var searchable = [];
            var selectable = [];
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });
            $('#data-table').DataTable({
                order: [],
                lengthMenu: [
                    [25, 50, 100, 200, 500, -1],
                    [25, 50, 100, 200, 500, "All"]
                ],
                processing: true,
                responsive: true,
                serverSide: true,
                language: {
                    processing: '<i class="ace-icon d-none fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                },
                pagingType: "full_numbers",
                dom: "<'flex justify-between items-center mb-3'<'w-full sm:w-auto'l><'w-full text-center sm:w-auto'B><'w-full sm:w-auto'f>>tipr",
                ajax: {
                    url: "{{ route('medicine.index') }}",
                    type: "GET",
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'brand',
                        name: 'brand'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'stock_quantity',
                        name: 'stock_quantity'
                    },
                    {
                        data: 'expiry_date',
                        name: 'expiry_date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        });




        //modal function
        function ShowCreateUpdateModal() {
            $('#createUpdateForm')[0].reset();
            $('#medicine_id').val('');
            $('#modalTitle').html('Create New Medicine');
            $('#modalOverlay').show().addClass('modal-open');
        }

        $('#close').click(function() {
            var modal = $('#modalOverlay');
            modal.removeClass('modal-open');
            setTimeout(function() {
                modal.hide();
            }, 200);
        });



        // Submit Form
        $('#createUpdateForm').on('submit', function(e) {
            e.preventDefault();

            var faqId = $('#medicine_id').val();

            var url = faqId ? "{{ route('medicine.update', ':id') }}".replace(':id', faqId) : "{{ route('medicine.store') }}";

            var method = faqId ? "POST" : "POST";

            // Create a FormData object to handle file uploads
            var formData = new FormData(this);

            // Make the AJAX request
            $.ajax({
                type: method,
                url: url,
                data: formData,
                processData: false,  // Prevent jQuery from automatically processing the data
                contentType: false,  // Prevent jQuery from setting the content type
                success: function(resp) {

                    // Reload DataTable
                    $('#data-table').DataTable().ajax.reload();

                    // Handle response
                    if (resp.success === true) {
                        // Show success message
                        flasher.success(resp.message);

                        // Close modal
                        $('#modalOverlay').removeClass('modal-open');
                        setTimeout(function() {
                            $('#modalOverlay').hide();
                        }, 200);
                    } else if (resp.errors) {
                        // Show first error message
                        flasher.error(resp.errors[0]);
                    } else {
                        // Show warning message
                        flasher.warning(resp.message);
                    }
                },
                error: function(error) {
                    // Show error message
                    flasher.error('An error occurred. Please try again.');
                    console.error(error);
                }
            });
        });





        function editMedicine(id) {
            let route = "{{ route('medicine.edit', ':id') }}";
            route = route.replace(':id', id);
            $.ajax({
                type: "GET",
                url: route,
                success: function(resp) {
                    console.log(resp);
                    if (resp.success === true) {
                        $('#medicine_id').val(resp.data.id);
                        $('#title').val(resp.data.title); // Ensure these fields are populated
                        $('#brand').val(resp.data.brand);

                        // Ensure the avatar field is set properly
                        if (resp.data.details) {
                            let detail = resp.data.details;  // Single related detail object
                            let formattedDate = new Date(detail.expiry_date).toISOString().slice(0, 16); // This formats it to "YYYY-MM-DDTHH:MM"
                            $('#quantity').val(detail.quantity);
                            $('#stock_quantity').val(detail.stock_quantity);
                            $('#form').val(detail.form);
                            $('#price').val(detail.price);
                            $('#generic_name').val(resp.data.generic_name);
                            $('#dosage').val(detail.dosage);
                            $('#unit').val(detail.unit);
                            $('#buying_price').val(detail.buying_price);
                            $('#expiry_date').val(formattedDate);
                        } else {
                            // If no details, set defaults
                            $('#quantity').val(0);
                            $('#stock_quantity').val(0);
                            $('#form').val('');
                            $('#price').val('');
                            $('#dosage').val(detail.dosage);
                            $('#unit').val(detail.unit);
                            $('#avatar').val(detail.avatar);
                        }

                        // if (resp.features && resp.features.length > 0) {
                        //     const featureInputContainer = $('#inputContainer');
                        //     featureInputContainer.empty(); // Clear existing features
                        //     resp.features.forEach(function(feature, index) {
                        //         featureInputContainer.append(`
                        //         <div class="flex items-center mb-2">
                        //             <input type="text" name="feature[]" class="form-input w-full" value="${feature}" placeholder="Add feature">
                        //             <button type="button" class="ml-2 text-xl font-semibold removeBtn">-</button>
                        //         </div>
                        //        `);
                        //     });
                        // }

                        $('#inputContainer').html(''); // Clear previous feature inputs

                        if (resp.features && resp.features.length > 0) {
                            resp.features.forEach(function(feature, index) {
                                let featureField = `
                            <div class="flex items-center mb-2 feature-item">
                                <input type="text" name="feature[]" class="form-input w-full" value="${feature}" placeholder="Add feature">
                                <button type="button" class="ml-2 text-xl font-semibold removeFeatureBtn" onclick="removeFeatureField(this)">-</button>
                            </div>
                        `;
                                $('#inputContainer').append(featureField);
                            });
                        }


                        if (resp.images && resp.images.length > 0) {
                            const featureAvatarContainer = $('#avatarContainer');
                            featureAvatarContainer.empty(); // Clear any existing inputs

                            // Create a flex container for the images
                            featureAvatarContainer.append('<div class="image-row flex flex-wrap gap-4">');

                            let baseUrl = "{{ asset('') }}";  // Get the base URL for assets

                            // Loop through the images and display them with dropify for changing images
                            resp.images.forEach(function(image, index) {
                                console.log(image);  // Check the image object in the console

                                // Construct the image URL relative to the public folder
                                let imageUrl = baseUrl  + image;  // Assuming image is the filename

                                // Add a dropify input for the existing image preview
                                featureAvatarContainer.append(`
                                    <div class="image-item d-flex flex-shrink-0 w-1/2 mb-4  ">
                                        <!-- Add a dropify input field for replacing the image -->
                                        <input type="file" name="avatar[]" class="dropify" data-default-file="${imageUrl}" data-height="300" />

                                    </div>
                                `);
                            });

                            // Close the image container
                            featureAvatarContainer.append('</div>');

                            // Initialize Dropify
                            $('.dropify').dropify();
                        }




                        // Open the modal
                        $('#modalTitle').html('Update Medicine');
                        $('#modalOverlay').show().addClass('modal-open');
                    } else {
                        flasher.warning(resp.message);
                    }
                },
                error: function(error) {
                    flasher.error('Error while fetching data.');
                }
            });
        }



        // Add new input field when "+" is clicked
        document.getElementById('incrementBtntext').addEventListener('click', function() {
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

        function removeFeatureField(element) {
            $(element).closest('.feature-item').remove();
        }

        // Show/hide remove button dynamically based on the presence of input fields
        document.getElementById('inputContainer').addEventListener('click', function(event) {
            if (event.target.tagName.toLowerCase() === 'input') {
                var removeBtns = document.querySelectorAll('.removeBtn');
                removeBtns.forEach(function(btn) {
                    btn.classList.remove('hidden'); // Show the remove button
                });
            }
        });
        //medicine image multiple create

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Dropify for all existing file inputs when the page loads
            $('.dropify').dropify();

            const incrementBtn = document.getElementById('incrementBtn');
            const avatarContainer = document.getElementById('avatarContainer'); // Container for avatar fields

            // Listen for click event to add a new avatar field
            incrementBtn.addEventListener('click', function () {
                // Create a new field for avatar input
                const newAvatarField = document.createElement('div');
                newAvatarField.classList.add('flex', 'flex-col', 'items-center', 'mb-2');

                newAvatarField.innerHTML = `
            <!-- Avatar input field -->
            <input name="avatar[]" class="form-input w-full dropify" type="file" accept=".jpeg, .png, .jpg, .gif, .ico, .bmp, .svg" data-height="300">
            <!-- Remove button below the image -->
            <button type="button" class="ml-2 text-xl font-semibold removeBtn mt-2">- Remove</button>
        `;

                // Append the new field to the container
                avatarContainer.appendChild(newAvatarField);

                // Reinitialize Dropify on the newly added input field
                const dropifyField = newAvatarField.querySelector('.dropify');
                $(dropifyField).dropify();

                // Add functionality to remove the field when the remove button is clicked
                const removeBtn = newAvatarField.querySelector('.removeBtn');
                removeBtn.addEventListener('click', function () {
                    avatarContainer.removeChild(newAvatarField);
                });
            });
        });



        // delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this record?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        };


        // Delete Button
        function deleteItem(id) {
            var url = '{{route('medicine.destroy', ':id')}}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                success: function(resp) {
                    console.log(resp);
                    // Reloade DataTable
                    $('#data-table').DataTable().ajax.reload();
                    if (resp.success === true) {
                        // show toast message
                        flasher.success(resp.message);

                    } else if (resp.errors) {
                        flasher.error(resp.errors[0]);
                    } else {
                        flasher.error(resp.message);
                    }
                }, // success end
                error: function(error) {
                    // location.reload();
                } // Error
            })
        }


        // Status Change Confirm Alert
        function ShowStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                }
            });
        }

        // Status Change
        function statusChange(id) {
            var url = '{{ route('medicine.status.update', ':id') }}';
            $.ajax({
                type: "GET",
                url: url.replace(':id', id),
                success: function(resp) {

                    console.log(resp);
                    // Reloade DataTable
                    $('#data-table').DataTable().ajax.reload();
                    if (resp.success === true) {
                        // show toast message
                        flasher.success(resp.message);
                    } else if (resp.errors) {
                        flasher.error(resp.errors[0]);
                    } else {
                        flasher.warning(resp.message);
                    }
                }, // success end
                error: function(error) {
                    flasher.error(error);
                }
            })
        }


    </script>
@endpush





