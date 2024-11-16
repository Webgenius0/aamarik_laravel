@extends('backend.app')

@section('title', 'Dua Category List | ' . $setting->title ?? 'SIS')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
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
        }

        #modalOverlay.modal-open #modal {
            opacity: 1;
            top: 50%;
        }

        /* SubCat Modal */
        #SubCatModal {
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
        }

        #SubCatmodalOverlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 9999;
        }

        #SubCatmodalOverlay.SubCatmodal-open #SubCatModal {
            opacity: 1;
            top: 50%;
        }
    </style>
@endpush

@section('content')
    <main class="p-6">
        <div class="card bg-white overflow-hidden">
            <div class="card-header">
                <div class="flex justify-between align-middle">
                    <h3 class="card-title">Dua Category List</h3>
                    <div>
                        <button class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md"
                            onclick="ShowCreateUpdateModal()">
                            Add Category
                        </button>
                        <button class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md"
                            onclick="CreateSubCategoryModal()">
                            Add SubCategory
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
                                            Category
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Sub_Categories
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


    {{-- Modal Start --}}
    <div id="modalOverlay" style="display:none;">
        <div id="modal" class="rounded-2xl max-w-2xl">
            <div class="flex py-2 w-full justify-between border-b">
                <h1 class="pt-4 ps-8 text-xl text-black font-semibold pb-4" id="modalTitle">
                    Create Dua Category
                </h1>
                <button id="close" type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="px-8 py-4">
                <form id="createUpdateForm">
                    <div class="">
                        <input type="hidden" name="id" id="id">
                        <!-- title -->
                        <div class="pe-3 mb-3">
                            <label class="block text-sm font-medium mb-2 text-start" for="title">Title:</label>
                            <input type="text" name="title" id="title" placeholder="Enter Title"
                                value="{{ old('title') }}"
                                class="form-input w-full border border-gray-300 rounded p-2 bg-gray-100">
                            <span class="text-red-500 text-sm" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="mt-5">
                        <button type="submit" class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal End --}}

    {{-- Sub_Cat Modal Start --}}
    <div id="SubCatmodalOverlay" style="display:none;">
        <div id="SubCatModal" class="rounded-2xl max-w-2xl">
            <div class="flex py-2 w-full justify-between border-b">
                <h1 class="pt-4 ps-8 text-xl text-black font-semibold pb-4" id="SubCatmodalTitle">
                    Create Subcategory
                </h1>
                <button id="SCclose" type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="px-8 py-4">
                <form id="SubCatcreateUpdateForm">
                    <div class="">
                        <input type="hidden" name="id" id="subCatid">
                        <!-- Category Dropdown -->
                        <div class="pe-3 mb-3">
                            <label class="block text-sm font-medium mb-2 text-start" for="category_id">Category:</label>
                            <select name="category_id" id="category_id"
                                class="form-input w-full border border-gray-300 rounded p-2 bg-gray-100">
                                <option value="">Select Category</option>
                                <!-- Categories will be populated here via AJAX -->
                            </select>
                            <span class="text-red-500 text-sm" role="alert">
                                <strong></strong>
                            </span>
                        </div>

                        <!-- Subcategory Title -->
                        <div class="pe-3 mb-3">
                            <label class="block text-sm font-medium mb-2 text-start" for="sctitle">Title:</label>
                            <input type="text" name="sctitle" id="sctitle" placeholder="Subcategory Title"
                                value="{{ old('title') }}"
                                class="form-input w-full border border-gray-300 rounded p-2 bg-gray-100">
                            <span class="text-red-500 text-sm" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="mt-5">
                        <button type="submit" class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>


    <script>
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
                    url: "{{ route('dua.category.index') }}",
                    type: "GET",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'subcategory',
                        name: 'subcategory'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });
        });

        /* Subcategory start */

        // Function to open the modal
        function CreateSubCategoryModal() {
            $('#SubCatmodalTitle').html('Create Subcategory');
            $("#subCatid").val('');
            $("#sctitle").val('');

            // Fetch categories using AJAX
            $.ajax({
                type: "GET",
                url: "{{ route('fetch.categories') }}",
                success: function(data) {
                    // Clear previous options
                    $('#category_id').empty();
                    $('#category_id').append('<option value="">Select Category</option>');

                    // Populate category dropdown
                    $.each(data, function(index, category) {
                        $('#category_id').append('<option value="' + category.id + '">' + category
                            .title + '</option>');
                    });
                },
                error: function(error) {
                    console.error('Error fetching categories:', error);
                    flasher.error('Failed to load categories.');
                }
            });

            $('#SubCatmodalOverlay').show().addClass('SubCatmodal-open');
        }

        // Close modal function
        $('#SCclose').click(function() {
            var modal = $('#SubCatmodalOverlay');
            modal.removeClass('SubCatmodal-open');
            setTimeout(function() {
                modal.hide();
            }, 200);
        });

        // Handle the form submission for subcategory
        $('#SubCatcreateUpdateForm').on('submit', function(e) {
            e.preventDefault();

            var url = "{{ route('sub_category.store') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function(resp) {
                    console.log(resp);
                    // Reload DataTable if necessary
                    $('#data-table').DataTable().ajax.reload();

                    if (resp.success === true) {
                        // Show flasher message with Toastr
                        flasher.success(resp.message);
                        $('#SubCatmodalOverlay').removeClass('SubCatmodal-open');

                        // Hide modal after a short delay
                        setTimeout(function() {
                            $('#SubCatmodalOverlay').hide();
                        }, 200);

                        // Optionally reset the form
                        $('#SubCatcreateUpdateForm')[0].reset();
                    } else if (resp.errors) {
                        // Display the validation errors
                        flasher.error(resp.errors.sctitle ? resp.errors.sctitle[0] :
                            'An error occurred.');
                    } else {
                        // Show warning message using Toastr
                        flasher.warning(resp.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Handle validation errors (e.g. subcategory already exists)
                        var errors = xhr.responseJSON.errors;
                        flasher.error(errors.sctitle ? errors.sctitle[0] : 'An error occurred.');
                    } else {
                        // Handle server errors
                        flasher.error('An error occurred while creating the subcategory.');
                        console.error('Error:', xhr);
                    }
                }
            });
        });


        // delete subcategory start
        $(document).on('click', '.delete-subcategory', function() {
            var subcategoryId = $(this).data('id');

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this subcategory?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, proceed with the AJAX request
                    $.ajax({
                        url: "{{ route('subcategory.delete') }}",
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: subcategoryId
                        },
                        success: function(resp) {
                            if (resp.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Subcategory deleted successfully.',
                                    icon: 'success',
                                    timer: 1000, // Auto close after 1 second
                                    showConfirmButton: false
                                });

                                // Reload DataTable or remove the deleted subcategory from the UI
                                $('#data-table').DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while deleting the subcategory.',
                                    icon: 'error',
                                    timer: 1000, // Auto close after 1 second
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred.',
                                icon: 'error',
                                timer: 1000, // Auto close after 1 second
                                showConfirmButton: false
                            });
                            console.error('Error:', error);
                        }
                    });
                }
                // If cancel button is clicked, just close the SweetAlert (no additional message shown)
            });
        });
        // delete subcategory end




        /* Subcategory end */


        /* Dua category start */

        function ShowCreateUpdateModal() {
            $('#modalTitle').html('Create Dua Category');
            $("#id").val('');
            $("#title").val('');
            $('#modalOverlay').show().addClass('modal-open');
        }

        $('#close').click(function() {
            var modal = $('#modalOverlay');
            modal.removeClass('modal-open');
            setTimeout(function() {
                modal.hide();
            }, 200);
        });

        // modal function Edit
        function editDuaCategory(id) {
            let route = '{{ route('dua.category.edit', ':id') }}';
            route = route.replace(':id', id);
            $.ajax({
                type: "GET",
                url: route,
                success: function(resp) {
                    console.log(resp);
                    // Reload DataTable
                    if (resp.success === true) {
                        $('#title').val(resp.data.title);
                        $('#id').val(resp.data.id);
                    } else if (resp.errors) {
                        flasher.error(resp.errors[0]);
                    } else {
                        flasher.warning(resp.message);
                    }
                }
            });
            $('#modalTitle').html('Update Dua Category');
            $('#modalOverlay').show().addClass('modal-open');
        }

        // Submit Form
        $('#createUpdateForm').on('submit', function(e) {
            e.preventDefault();
            var url = "{{ route('dua.category.store') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function(resp) {
                    console.log(resp);
                    // Reload DataTable
                    $('#data-table').DataTable().ajax.reload();
                    if (resp.success === true) {
                        // show success flasher message
                        flasher.success(resp.message);
                        $('#modalOverlay').removeClass('modal-open');
                        setTimeout(function() {
                            $('#modalOverlay').hide();
                        }, 200);
                    } else if (resp.errors) {
                        // Display the first error message
                        flasher.error(resp.errors.title ? resp.errors.title[0] : 'An error occurred.');
                    } else {
                        flasher.warning(resp.message);
                    }
                }, // success end
                error: function(xhr) {
                    console.log(xhr);
                    if (xhr.status === 422) {
                        // Display validation errors
                        var errors = xhr.responseJSON.errors;
                        console.log(errors);
                        flasher.error(errors.title ? errors.title[0] : 'An error occurred.');
                    } else {
                        flasher.error('An error occurred while processing your request.');
                    }
                }
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
            var url = '{{ route('dua.category.destroy', ':id') }}';
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
            var url = '{{ route('dua.category.status', ':id') }}';
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
        /* Dua category end */
    </script>
@endpush
