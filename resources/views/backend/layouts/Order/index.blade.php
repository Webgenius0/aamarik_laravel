@extends('backend.app')

@section('title', 'Order Management| ' . $setting->title ?? 'PrimeCare')

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



            <!-- Order Details Modal -->
{{--            <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">--}}
{{--                <div class="modal-dialog modal-lg">--}}
{{--                    <div class="modal-content">--}}
{{--                        <div class="modal-header">--}}
{{--                            <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>--}}
{{--                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                        </div>--}}
{{--                        <div class="modal-body">--}}
{{--                            <!-- Order details will be injected here via AJAX -->--}}
{{--                            <div class="order-details">--}}
{{--                                <h4 class="text-lg font-bold mb-2">Order Information</h4>--}}
{{--                                <p><strong>Order ID:</strong> <span id="order-id"></span></p>--}}
{{--                                <p><strong>Status:</strong> <span id="order-status"></span></p>--}}
{{--                                <p><strong>Order Date:</strong> <span id="order-date"></span></p>--}}
{{--                                <p><strong>Delivery Date:</strong> <span id="delivery-date"></span></p>--}}

{{--                                <h4 class="text-lg font-bold mb-2">User Information</h4>--}}
{{--                                <p><strong>Name:</strong> <span id="user-name"></span></p>--}}
{{--                                <p><strong>Email:</strong> <span id="user-email"></span></p>--}}

{{--                                <h4 class="text-lg font-bold mb-2">Billing Address</h4>--}}
{{--                                <p><strong>Name:</strong> <span id="billing-name"></span></p>--}}
{{--                                <p><strong>Email:</strong> <span id="billing-email"></span></p>--}}
{{--                                <p><strong>Address:</strong> <span id="billing-address"></span></p>--}}
{{--                                <p><strong>Contact:</strong> <span id="billing-contact"></span></p>--}}
{{--                                <p><strong>City:</strong> <span id="billing-city"></span></p>--}}
{{--                                <p><strong>Postcode:</strong> <span id="billing-postcode"></span></p>--}}

{{--                                <h4 class="text-lg font-bold mb-2">Order Items</h4>--}}
{{--                                <table class="min-w-full table-auto border">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th class="px-4 py-2 border">Medicine</th>--}}
{{--                                        <th class="px-4 py-2 border">Quantity</th>--}}
{{--                                        <th class="px-4 py-2 border">Unit Price</th>--}}
{{--                                        <th class="px-4 py-2 border">Total Price</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody id="order-items">--}}
{{--                                    <!-- Order items will be injected here -->--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}

{{--                                <h4 class="text-lg font-bold mb-2">Treatment Information</h4>--}}
{{--                                <p><strong>Name:</strong> <span id="treatment-name"></span></p>--}}

{{--                                <h4 class="text-lg font-bold mb-2">Pricing Summary</h4>--}}
{{--                                <p><strong>Sub Total:</strong> <span id="sub-total"></span></p>--}}
{{--                                <p><strong>Discount:</strong> <span id="discount"></span></p>--}}
{{--                                <p><strong>Total Price:</strong> <span id="total-price"></span></p>--}}

{{--                                <h4 class="text-lg font-bold mb-2">Order Note</h4>--}}
{{--                                <p id="order-note"></p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}




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
                                        Order Id
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Delivery Date
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

    <!---->


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
                    url: "{{ route('orders.index') }}",
                    type: "GET",
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                    {
                        data: 'order_code',
                        name: 'order_code'
                    },
                    {
                        data: 'order_date',
                        name: 'order_date'
                    },
                    {
                        data: 'delivery_date',
                        name: 'delivery_date'
                    },
                    {
                        data: 'order_status',
                        name: 'order_status'
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


        // AJAX request to update order status
        function updateOrderStatus(orderId) {
            var status = document.getElementById('status-dropdown-' + orderId).value;

            // Show SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to change the order status to " + status,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed to update the order status
                    var route = "{{ route('order.status.update', ':id') }}".replace(':id', orderId);
                    $.ajax({
                        url: route,
                        type: "POST",
                        data: {
                            status: status,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                flasher.success(response.message);
                                // Reload DataTable after successful status update
                                $('#data-table').DataTable().ajax.reload();
                            } else {
                                flasher.error('Error updating the status.');
                            }
                        },
                        error: function(error) {
                            flasher.error('An error occurred. Please try again.');
                        }
                    });
                } else {
                    // Optionally reset the dropdown if the user cancels the update
                    var dropdown = document.getElementById('status-dropdown-' + orderId);
                    dropdown.value = dropdown.getAttribute('data-current-status');
                }
            });
        }

        // View order details in a modal
        function viewOrderDetails(orderId) {
            var route = "{{ route('order.details', ':id') }}".replace(':id', orderId);

            $.ajax({
                url: route,
                type: "GET",
                success: function(response) {
                    $('#orderDetailsModal .modal-body').html(response); // Assuming response contains order details HTML
                    $('#orderDetailsModal').modal('show'); // Show the modal
                },
                error: function(error) {
                    flasher.error('An error occurred while fetching the order details.');
                }
            });
        }

        // Example function to fetch and display order details in the modal
        {{--function viewOrderDetails(orderId) {--}}
        {{--    // Replace with your actual route for fetching order details--}}
        {{--    var route = "{{ route('order.details', ':id') }}".replace(':id', orderId);--}}

        {{--    $.ajax({--}}
        {{--        url: route,--}}
        {{--        type: "GET",--}}
        {{--        success: function(response) {--}}
        {{--            // Assuming your response contains the order details in the response.data--}}
        {{--            var order = response.data;--}}

        {{--            // Injecting the order details into the modal--}}
        {{--            $('#order-id').text(order.uuid);--}}
        {{--            $('#order-status').text(order.status.charAt(0).toUpperCase() + order.status.slice(1));--}}
        {{--            $('#order-date').text(order.created_at);--}}
        {{--            $('#delivery-date').text(order.status == 'delivered' ? order.updated_at : 'Not Delivered');--}}

        {{--            $('#user-name').text(order.user.name);--}}
        {{--            $('#user-email').text(order.user.email);--}}

        {{--            $('#billing-name').text(order.billing_address.name ?? 'N/A');--}}
        {{--            $('#billing-email').text(order.billing_address.email ?? 'N/A');--}}
        {{--            $('#billing-address').text(order.billing_address.address ?? 'N/A');--}}
        {{--            $('#billing-contact').text(order.billing_address.contact ?? 'N/A');--}}
        {{--            $('#billing-city').text(order.billing_address.city ?? 'N/A');--}}
        {{--            $('#billing-postcode').text(order.billing_address.postcode ?? 'N/A');--}}

        {{--            // Inject order items dynamically--}}
        {{--            var itemsHtml = '';--}}
        {{--            order.order_items.forEach(function(item) {--}}
        {{--                itemsHtml += '<tr>' +--}}
        {{--                    '<td class="px-4 py-2 border">' + (item.medicine.name ?? 'N/A') + '</td>' +--}}
        {{--                    '<td class="px-4 py-2 border">' + item.quantity + '</td>' +--}}
        {{--                    '<td class="px-4 py-2 border">' + item.unit_price.toFixed(2) + '</td>' +--}}
        {{--                    '<td class="px-4 py-2 border">' + item.total_price.toFixed(2) + '</td>' +--}}
        {{--                    '</tr>';--}}
        {{--            });--}}
        {{--            $('#order-items').html(itemsHtml);--}}

        {{--            $('#treatment-name').text(order.treatment.name ?? 'N/A');--}}

        {{--            $('#sub-total').text(order.sub_total.toFixed(2));--}}
        {{--            $('#discount').text(order.discount.toFixed(2));--}}
        {{--            $('#total-price').text(order.total_price.toFixed(2));--}}

        {{--            $('#order-note').text(order.note ?? 'N/A');--}}

        {{--            // Open the modal--}}
        {{--            $('#orderDetailsModal').modal('show');--}}
        {{--        },--}}
        {{--        error: function(error) {--}}
        {{--            console.error('Error fetching order details:', error);--}}
        {{--            alert('An error occurred while fetching the order details.');--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}



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
            var url = '';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

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
    </script>
@endpush
