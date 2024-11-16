@extends('backend.app')

@section('title', 'Social Media | ' . $setting->title ?? 'SIS')

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
    </style>
@endpush

@section('content')
    <main class="p-6">
        <div class="card bg-white overflow-hidden">
            <div class="card-header">
                <div class="flex justify-between align-middle">
                    <h3 class="card-title">Social Media</h3>
                    <div>
                        <button class="btn bg-info text-white py-2 px-5 hover:bg-success rounded-md"
                            onclick="ShowCreateUpdateModal()">

                            Add New
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
                                            URL
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
                <h1 class="pt-4 ps-8 text-xl text-black font-semibold pb-4" id="modalTitle">
                    Create Media
                </h1>
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
                        <div class="pe-3 mb-3">
                            <label class="block text-sm font-medium mb-2 text-start" for="url">URL:</label>
                            <input type="url" name="url" id="url" placeholder="https://example.com"
                                value="{{ old('url') }}"
                                class="form-input w-full border border-gray-300 rounded p-2 bg-gray-100">
                            <span class="text-red-500 text-sm" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>

                    <div class="mt-5 flex justify-end gap-x-2">

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
                    url: "{{ route('social.media') }}",
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
                        data: 'url',
                        name: 'url'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
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
        
        //modal function
        function ShowCreateUpdateModal() {
            $('#modalTitle').html('Create Dua Category');
            $("#id").val('');
            $("#title").val('');
            $('#modalOverlay').show().addClass('modal-open');
        }


        //modal function
        function ShowCreateUpdateModal() {
            $('#modalTitle').html('Create Social Media');
            $("#id").val('');
            $("#title").val('');
            $("#url").val('');
            $('#modalOverlay').show().addClass('modal-open');
        }


        $('#close').click(function() {
            var modal = $('#modalOverlay');
            modal.removeClass('modal-open');
            setTimeout(function() {
                modal.hide();
            }, 200);
        });


        function editMedia(id) {
            let route = '{{ route('social.media.edit', ':id') }}';
            route = route.replace(':id', id);
            $.ajax({
                type: "GET",
                url: route,
                success: function(resp) {
                    console.log(resp);
                    // Reloade DataTable
                    if (resp.success === true) {
                        $('#title').val(resp.data.title);
                        $('#url').val(resp.data.url);
                        $('#id').val(resp.data.id);
                    } else if (resp.errors) {
                        flasher.error(resp.errors[0]);
                    } else {
                        flasher.warning(resp.message);
                    }
                }
            });
            $('#modalTitle').html('Update Social Media');
            $('#modalOverlay').show().addClass('modal-open');
        }
        
        // Submit Form
        $('#createUpdateForm').on('submit', function(e) {
            e.preventDefault();
            var url = "{{ route('social.media.store') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function(resp) {
                    console.log(resp);
                    // Reloade DataTable
                    $('#data-table').DataTable().ajax.reload();
                    if (resp.success === true) {
                        // show toast message
                        flasher.success(resp.message);
                        $('#modalOverlay').removeClass('modal-open');
                        setTimeout(function() {
                            $('#modalOverlay').hide();
                        }, 200);

                    } else if (resp.errors) {
                        flasher.error(resp.errors[0]);
                    } else {
                        flasher.warning(resp.message);
                    }
                }, // success end
                error: function(error) {
                    flasher.error(error);
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
            var url = '{{ route('social.media.destroy', ':id') }}';
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
            var url = '{{ route('social.media.status', ':id') }}';
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
