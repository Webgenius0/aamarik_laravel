@extends('backend.app')

@section('title', 'Customers Management| ' . $setting->title ?? 'PrimeCare')

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
        <div class="card bg-white overflow-hidden">

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
                                        Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Phone
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Gender
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Order
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



    <!-- Order Details Modal -->
    <!-- User Details Modal -->
    <div id="userDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center transition-opacity duration-300">
        <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-3xl w-full transform scale-95 transition-transform duration-300" id="modalContent">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b pb-4">
                <h2 class="text-2xl font-semibold text-gray-800">User Details</h2>
                <button onclick="closeOrderModal()" class="text-gray-600 hover:text-red-500 text-2xl font-bold">&times;</button>
            </div>

            <!-- User Details -->
            <div class="order-details mt-6 space-y-6 text-gray-700">
                <!-- User Info & Avatar Side-by-Side -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- User Avatar -->
                    <div class="flex justify-center items-center">
                        <div class="w-48 h-48 rounded-full overflow-hidden border-4 border-gray-200 shadow-lg">
                            <img id="avatar" src="https://via.placeholder.com/150" alt="User Avatar"  class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">👤 User Information</h4>
                        <div class="space-y-3">
                            <p><strong>Name:</strong> <span id="name" class="text-gray-700">N/A</span></p>
                            <p><strong>Email:</strong> <span id="email" class="text-gray-700">N/A</span></p>
                            <p><strong>Phone:</strong> <span id="phone" class="text-gray-700">N/A</span></p>
                            <p><strong>Gender:</strong> <span id="gender" class="text-gray-700">N/A</span></p>
                            <p><strong>Address:</strong> <span id="address" class="text-gray-700">N/A</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Close Button -->
            <div class="mt-8 flex justify-end">
                <button onclick="closeOrderModal()" class="bg-red-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-red-600 transition">
                    Close
                </button>
            </div>
        </div>
    </div>



@endsection

@push('scripts')

    <script src="https://unpkg.com/tailwindcss-jit-cdn@2.2.19/dist/tailwind.min.js"></script>
    {{-- Dropify --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    {{--Flashar--}}
    <script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
    {{-- Ck Editor --}}
    <script src="{{ asset('Backend/plugins/tinymc/tinymce.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Define orderDetailsRoute here -->
    <script>
        var orderDetailsRoute = @json(route('order.details', ':id'));
    </script>

    <script>
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



        $(document).ready(function() {
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
                    url: "{{ route('customer.index') }}",
                    type: "GET",
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'total_order',
                        name: 'total_order'
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

        $(document).ready(function() {
            const flasher = new Flasher({
                selector: '[data-flasher]',
                duration: 3000,
                options: {
                    position: 'top-right',
                },
            });
        });



        function view(id) {
            var route = "{{ route('customer.show', ':id') }}".replace(':id', id);

            $.ajax({
                url: route,
                type: 'GET',
                success: function (response) {
                    if (response.success === true) {

                        console.log(response);
                        var data = response.data;

                        // Set user details
                        $('#name').text(data.name || 'N/A');
                        $('#email').text(data.email || 'N/A');
                        $('#phone').text(data.phone || 'N/A');
                        $('#gender').text(data.gender || 'N/A');
                        $('#address').text(data.address || 'N/A');


                        // Assuming data.avatar contains the relative path to the avatar image
                        var avatarUrl = data.avatar ? "{{ asset('') }}" + data.avatar : "{{ asset('uploads/defult-image/user.png') }}";

                        $('#avatar').attr('src', avatarUrl);

                        // Show Modal
                        $('#userDetailsModal').removeClass('hidden');
                        $('#modalContent').removeClass('scale-95').addClass('scale-100');
                    } else {
                        alert('User not found.');
                    }
                },
                error: function () {
                    alert('Failed to load user details.');
                }
            });
        }

        // Close the modal
        function closeOrderModal() {
            $('#modalContent').removeClass('scale-100').addClass('scale-95');
            setTimeout(() => {
                $('#userDetailsModal').addClass('hidden');
            }, 200);
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
            var route = "{{ route('order.delete', ':id') }}".replace(':id', id);

            $.ajax({
                type: "DELETE",
                url: route,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function(resp) {
                    console.log(resp);
                    // Reload DataTable
                    $('#data-table').DataTable().ajax.reload();

                    if (resp.success === true) {
                        // Show toast message
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
    </script>
@endpush
