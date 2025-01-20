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
                        Create Medicine
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
        <div class="flex py-2 w-full justify-between border-b">

            <button id="close"
                class="m-4 absolute top-0 right-1 hover:bg-gray-200 rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-black"
                type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="px-8 py-4">
            <form class="max-w-6xl w-full mx-auto space-y-4"
                method="POST" enctype="multipart/form-data" id="createUpdateForm">
                @csrf
                <h1 class="flex align-left h1">Create Medicine</h1>
                <input type="hidden" name="id" id="medicene_id">

                {{-- favicon --}}
                <div class="flex flex-row space-x-8">

                    <div class="w-full">

                        <div class="flex-col md:flex-row">
                            <label class="text-lg font-medium mb-2 md:mb-0 md:w-1/3 flex align-left">Image</label>
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
                        <label for="email" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Title</label>
                        <input name="title" type="text" class="form-input w-full" id="title" placeholder="example@gmail.com" value="{{old('title')}}">
                        @error('title')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="flex flex-col md:w-1/2">
                        <label for="department" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Brand</label>
                        <input name="brand" type="text" class="form-input w-full" id="brand" placeholder="Brand-name.." value="{{old('brand')}}">

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
                        <input name="generic_name" type="text" class="form-input w-full" id="generic_name" placeholder="generic name.." value="{{old('generic_name')}}">
                        @error('generic_name')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="flex flex-col md:w-1/2">
                        <label for="description" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Description</label>
                        <textarea name="description" class="form-input w-full" id="description" placeholder="generic name.." value="{{old('description')}}"></textarea>

                        @error('description')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                </div>


                <div class="flex flex-col md:flex-row items-center md:space-x-4">
                    <div class="flex flex-col md:w-1/2">
                        <label  class="text-lg font-medium mb-2 md:mb-0 flex align-left">Form</label>
                        <input name="form" type="text" class="form-input w-full" id="form" placeholder="generic name.." value="{{old('form')}}">
                        @error('form')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="flex flex-col md:w-1/2">
                        <label for="description" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Doges</label>
                        <input name="doges" type="text" class="form-input w-full" id="doges" placeholder="Brand-name.." value="{{old('dogase')}}" accept="">

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
                        <input name="unit" type="text" class="form-input w-full" id="unit" placeholder="Unit...." value="{{old('unit')}}">
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
                        <input name="price" type="text" class="form-input w-full" id="price" placeholder="Price....." value="{{old('price')}}" accept="">

                        @error('price')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>

                </div>

                <div class="flex flex-col md:flex-row items-center md:space-x-4">
                    <div class="flex flex-col md:w-1/2">
                        <label for="unit" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Quantity</label>
                        <input name="quantity" type="text" class="form-input w-full" id="quantity" placeholder="Unit...." value="{{old('quantity')}}">
                        @error('quantity')
                        <span class="text-red-500 block mt-1 text-sm">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>

                    <div class="flex flex-col md:w-1/2">
                        <label for="description" class="text-lg font-medium mb-2 md:mb-0 flex align-left">Stock Quantity</label>
                        <input name="stock_quantity" type="text" class="form-input w-full" id="stock_quantity" placeholder="stock quantity....." value="{{old('stock_quantity')}}" accept="">

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

                    <button type="submit"
                        class="btn bg-blue-500 text-white py-2 px-4 rounded-lg font-semibold ml-auto flex">Save</button>


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




    $('#createUpdateForm').on('submit', function(e) {
    e.preventDefault();

    var faqId = $('#medicene_id').val(); // Retrieve the FAQ ID from the hidden input
    var url = faqId ? "{{ route('medicine.update', ':id') }}".replace(':id', faqId) : "{{ route('medicine.store') }}";
    var method = faqId ? "PUT" : "POST"; // Use PUT for updates, POST for creation

    // Create FormData object
    var formData = new FormData($('#createUpdateForm')[0]);

    $.ajax({
        type: method,
        url: url,
        data: formData,
        processData: false, // Don't process the data
        contentType: false, // Don't set content type
        success: function(resp) {
            console.log(resp);

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


//edit medicine
function editMedicine(id) {
            let route = '{{ route('medicine.edit', ':id') }}';
            route = route.replace(':id', id);
            $.ajax({
                type: "GET",
                url: route,
                success: function(resp) {
                    console.log(resp.data); // Ensure the response data structure is as expected


                    if (resp.success === true) {
                        $('#medicene_id').val(resp.data.id);
                        $('#title').val(resp.data.title); // Set the question field
                        $('#brand').val(resp.data.brand); // Set the answer field
                        $('#quantity').val(resp.data.quantity); // Set the type dropdown
                        $('#stock_quantity').val(resp.data.stock_quantity);
                       $('#generic_name').val(resp.data.generic_name);
                       $('#description').val(resp.data.description);
                       $('#quantity').val(resp.data.quantity);
                       $('#price').val(resp.data.price);
                       
                       if (resp.data.details) {
                                let detail = resp.data.details;  // Single related detail object
                                $('#quantity').val(detail.quantity);
                                $('#stock_quantity').val(detail.stock_quantity);
                                $('#form').val(detail.form);
                                $('#price').val(detail.price);
                            } else {
                                // If no details, set defaults
                                $('#quantity').val(0);
                                $('#stock_quantity').val(0);
                                $('#form').val('');
                                $('#price').val('');
                                $('#dosage').val(detail.dosage);
                                $('#unit').val(detail.unit);
                                $('#unit').val(detail.unit);
                            }

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

    // //store medicine

    // $(document).ready(function() {
    //     $('#createUpdateForm').on('submit', function(e) {
    //         e.preventDefault();
    //         $.ajax({
    //             url: "{{route('medicine.store')}}",
    //             type: "POST",
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             processData: false,
    //             contentType: false,
    //             data: new FormData(this),
    //             success: function(resp) {
    //                 console.log(resp);
    //                 if (resp.success === true) {
    //                     $('#modalOverlay').removeClass('modal-open');
    //                     setTimeout(function() {
    //                         $('#modalOverlay').hide();
    //                     }, 200);
    //                     $('#data-table').DataTable().ajax.reload();
    //                     flasher.success(resp.message);
    //                 } else if (resp.errors) {
    //                     flasher.error(resp.errors[0]);
    //                 } else {
    //                     flasher.warning(resp.message);
    //                 }
    //             },
    //             error: function(error) {
    //                 flasher.error(error);
    //             }
    //         });
    //     });
    // });
</script>
@endpush