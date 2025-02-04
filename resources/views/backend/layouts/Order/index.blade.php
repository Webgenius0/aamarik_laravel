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
                                        Pay Amount
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Due Amount
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



    <!-- Order Details Modal -->
    <div id="orderDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center transition-opacity duration-300">
        <div class="bg-white p-6 rounded-2xl shadow-2xl max-w-3xl w-full transform scale-95 transition-transform duration-300" id="modalContent">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b pb-3">
                <h2 class="text-2xl font-semibold text-gray-800">Order Details</h2>
                <button onclick="closeOrderModal()" class="text-gray-600 hover:text-red-500 text-2xl font-bold">&times;</button>
            </div>

            <!-- Order Details -->
            <div class="order-details mt-4 space-y-4 text-gray-700">

                <!-- Order Info -->
                <h4 class="text-lg font-bold text-gray-900">🛒 Order Information</h4>
                <div class="grid grid-cols-2 gap-4 text-gray-800">
                    <p><strong>Order ID:</strong> <span id="order-id"></span></p>
                    <p><strong>Status:</strong> <span id="order-status" class="px-3 py-1 rounded-lg bg-gray-200 text-sm"></span></p>
                    <p><strong>Order Date:</strong> <span id="order-date"></span></p>
                    <p><strong>Delivery Date:</strong> <span id="delivery-date"></span></p>
                </div>

                <!-- User Info & Billing Address Side-by-Side -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <!-- User Info -->
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <h4 class="text-lg font-bold text-gray-900">👤 User Information</h4>
                        <p><strong>Name:</strong> <span id="user-name"></span></p>
                        <p><strong>Email:</strong> <span id="user-email"></span></p>
                    </div>

                    <!-- Billing Address -->
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <h4 class="text-lg font-bold text-gray-900">🏠 Shipping Address</h4>
                        <p><strong>Name:</strong> <span id="billing-name"></span></p>
                        <p><strong>Email:</strong> <span id="billing-email"></span></p>
                        <p><strong>Address:</strong> <span id="billing-address"></span></p>
                        <p><strong>Contact:</strong> <span id="billing-contact"></span></p>
                        <p><strong>City:</strong> <span id="billing-city"></span></p>
                        <p><strong>Postcode:</strong> <span id="billing-postcode"></span></p>
                        <p><strong>Gp Number:</strong> <span id="billing-gpNumber"></span></p>
                        <p><strong>Gp :</strong> <span id="billing-gpAddress"></span></p>

                    </div>
                </div>

                <!-- Order Items Table -->
                <h4 class="text-lg font-bold text-gray-900 mt-4">📦 Order Items</h4>
                <div class="overflow-x-auto">
                    <table class="w-full border mt-2 rounded-lg shadow-md bg-gray-100">
                        <thead class="bg-gray-300">
                        <tr>
                            <th class="px-4 py-2 border">Medicine</th>
                            <th class="px-4 py-2 border">Quantity</th>
                            <th class="px-4 py-2 border">Unit Price</th>
                            <th class="px-4 py-2 border">Total Price</th>
                        </tr>
                        </thead>
                        <tbody id="order-items"></tbody>
                    </table>
                </div>

                <!-- Treatment & Pricing Side-by-Side -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <!-- Treatment -->
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <h4 class="text-lg font-bold text-gray-900">💊 Treatment Information</h4>
                        <p><strong>Name:</strong> <span id="treatment-name"></span></p>
                    </div>

                    <!-- Pricing Summary -->
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <h4 class="text-lg font-bold text-gray-900">💰 Pricing Summary</h4>
                        <p><strong>Sub Total:</strong> <span id="sub-total"></span></p>
                        <p><strong>Discount:</strong> <span id="discount"></span></p>
                        <p><strong>Total Price:</strong> <span id="total-price"></span></p>
                    </div>
                </div>

                <!-- Order Note -->
                <h4 class="text-lg font-bold text-gray-900 mt-4">📝 Order Note</h4>
                <p id="order-note" class="italic text-gray-600"></p>
            </div>

            <!-- Close Button -->
            <div class="mt-5 flex justify-end space-x-3">
                <!-- Download PDF Invoice Button -->
                <a href="#"  id="downloadPDF" class="bg-green-500 text-white px-5 py-2 rounded-lg shadow-md hover:bg-green-600 transition">
                    Download Invoice PDF
                </a>

                <button onclick="closeOrderModal()" class="bg-red-500 text-white px-5 py-2 rounded-lg shadow-md hover:bg-red-600 transition">
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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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
                        data: 'pay_amount',
                        name: 'pay_amount'
                    },
                    {
                        data: 'due_amount',
                        name: 'due_amount'
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

        // Open the modal
        function viewOrderDetails(orderId) {

            var route = "{{ route('order.details', ':id') }}".replace(':id', orderId);

            $.ajax({
                url: route,
                type: 'GET',
                success: function (response) {
                    if (response.success === true) {
                        var data = response.data;

                        var downloadRoute = "{{ route('order.invoice.download', ':id') }}".replace(':id', data.uuid);
                        // Set Download PDF button link
                        $('#downloadPDF').attr('href', downloadRoute);
                        // Fill Order Details
                        $('#order-id').text(data.uuid);
                        $('#order-status').text(data.status || 'N/A');
                        $('#order-date').text(data.order_date || 'N/A');
                        $('#delivery-date').text(data.delivery_date || 'N/A');

                        // Fill User Details
                        $('#user-name').text(data.user.name);
                        $('#user-email').text(data.user.email);

                        // Fill Billing Address
                        $('#billing-name').text(data.billing_address.name);
                        $('#billing-email').text(data.billing_address.email);
                        $('#billing-address').text(data.billing_address.address);
                        $('#billing-contact').text(data.billing_address.contact);
                        $('#billing-city').text(data.billing_address.city);
                        $('#billing-postcode').text(data.billing_address.postcode);
                        $('#billing-gpNumber').text(data.billing_address.gp_number);
                        $('#billing-gpAddress').text(data.billing_address.gp_address);

                        // Fill Treatment Info
                        $('#treatment-name').text(data.treatment.name || 'N/A');

                        // Fill Pricing
                        $('#sub-total').text('$' + data.sub_total);
                        $('#discount').text('$' + data.discount);
                        $('#total-price').text('$' + data.total_price);

                        // Fill Order Note
                        $('#order-note').text(data.note || 'No notes available');

                        // Populate Order Items Table
                        var orderItemsHTML = '';
                        data.order_items.forEach(function (item) {
                            orderItemsHTML += `
                        <tr class="border">
                            <td class="px-4 py-2 border">${item.title}</td>
                            <td class="px-4 py-2 border">${item.quantity}</td>
                            <td class="px-4 py-2 border">$${item.unit_price}</td>
                            <td class="px-4 py-2 border">$${item.total_price}</td>
                        </tr>`;
                        });
                        $('#order-items').html(orderItemsHTML);

                        // Show Modal
                        $('#orderDetailsModal').removeClass('hidden');
                        $('#modalContent').removeClass('scale-95').addClass('scale-100');
                    } else {
                        alert('Order not found.');
                    }
                },
                error: function () {
                    alert('Failed to load order details.');
                }
            });
        }

        // Close the modal
        function closeOrderModal() {
            $('#modalContent').removeClass('scale-100').addClass('scale-95');
            setTimeout(() => {
                $('#orderDetailsModal').addClass('hidden');
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


        // download invoice
    function downloadPDF() {
        const { jsPDF } = window.jspdf;
        html2canvas(document.querySelector("#invoice")).then(canvas => {
            let pdf = new jsPDF('p', 'mm', 'a4');
            let imgData = canvas.toDataURL('image/png');
            let imgWidth = 210;
            let imgHeight = (canvas.height * imgWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            pdf.save("Luxury_Invoice.pdf");
        });
    }
    </script>
@endpush
