@extends('backend.app')

@section('title', 'Order Management| ' . $setting->title ?? 'PrimeCare')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
    <!-- Dropify CSS for file input styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"/>
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

        #modalContent {
            max-height: 80vh;
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
                                        Products
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Logs
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order Total
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
    <div id="orderDetailsModal"
         class="hidden fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center transition-opacity duration-300">
        <div
            class="bg-white p-6 rounded-2xl shadow-2xl max-w-3xl w-full transform scale-95 transition-transform duration-300 relative"
            id="modalContent">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b pb-3">
                <h2 class="text-2xl font-semibold text-gray-800">Order Details</h2>
                <button onclick="closeOrderModal()" class="text-gray-600 hover:text-red-500 text-2xl font-bold">
                    &times;
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="order-details mt-4 space-y-4 text-gray-700 max-h-[70vh] overflow-y-auto p-2">
                <!-- Order Info -->
                <h4 class="text-lg font-bold text-gray-900">🛒 Order Information</h4>
                <div class="grid grid-cols-2 gap-4 text-gray-800">
                    <p><strong>Order ID:</strong> <span id="order-id"></span></p>
                    <p><strong>Status:</strong> <span id="order-status"
                                                      class="px-3 py-1 rounded-lg bg-gray-200 text-sm"></span></p>
                    <p><strong>Order Date:</strong> <span id="order-date"></span></p>
                    <p><strong>Delivery Date:</strong> <span id="delivery-date"></span></p>
                </div>

                <!-- User Info & Billing Address -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <h4 class="text-lg font-bold text-gray-900">👤 User Information</h4>
                        <p><strong>Name:</strong> <span id="user-name"></span></p>
                        <p><strong>Email:</strong> <span id="user-email"></span></p>
                    </div>


                    <div class="bg-gray-100 p-4 rounded-lg shadow-md relative">
                        <h4 class="text-lg font-bold text-gray-900">🏠 Shipping Address</h4>

                        <!-- Display Address -->
                        <div id="shipping-address-display">
                            <p><strong>Name:</strong> <span
                                    id="billing-name">{{ $order->billing_address->name ?? '' }}</span></p>
                            <p><strong>Email:</strong> <span
                                    id="billing-email">{{ $order->billing_address->email ?? '' }}</span></p>
                            <p><strong>Address:</strong> <span
                                    id="billing-address">{{ $order->billing_address->address ?? '' }}</span></p>
                            <p><strong>Contact:</strong> <span
                                    id="billing-contact">{{ $order->billing_address->contact ?? '' }}</span></p>
                            <p><strong>City:</strong> <span
                                    id="billing-city">{{ $order->billing_address->city ?? '' }}</span></p>
                            <p><strong>Postcode:</strong> <span
                                    id="billing-postcode">{{ $order->billing_address->postcode ?? '' }}</span></p>
                            <p><strong>Gp Number:</strong> <span
                                    id="billing-gpNumber">{{ $order->billing_address->gp_number ?? '' }}</span></p>
                            <p><strong>Gp :</strong> <span
                                    id="billing-gpAddress">{{ $order->billing_address->gp_address ?? '' }}</span></p>
                        </div>

                        <!-- Editable Form (Hidden Initially) -->
                        <form id="edit-shipping-address-form" class="hidden">
                            <input type="text" id="edit-billing-name" class="w-full p-2 border rounded-lg my-2"
                                   placeholder="Name">
                            <input type="email" id="edit-billing-email" class="w-full p-2 border rounded-lg my-2"
                                   placeholder="Email">
                            <input type="text" id="edit-billing-address" class="w-full p-2 border rounded-lg my-2"
                                   placeholder="Address">
                            <input type="text" id="edit-billing-contact" class="w-full p-2 border rounded-lg my-2"
                                   placeholder="Contact">
                            <input type="text" id="edit-billing-city" class="w-full p-2 border rounded-lg my-2"
                                   placeholder="City">
                            <input type="text" id="edit-billing-postcode" class="w-full p-2 border rounded-lg my-2"
                                   placeholder="Postcode">
                            <input type="text" id="edit-billing-gpNumber" class="w-full p-2 border rounded-lg my-2"
                                   placeholder="Gp Number">
                            <input type="text" id="edit-billing-gpAddress" class="w-full p-2 border rounded-lg my-2"
                                   placeholder="Gp Address">

                            <!-- Save Button -->
                            <button type="button" id="save-shipping-address"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition">
                                Save Address
                            </button>
                        </form>

                        <!-- Edit Button -->
                        <button id="edit-shipping-button"
                                class="absolute top-2 right-2 text-blue-600 hover:text-blue-800">
                            ✏️ Edit
                        </button>
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

                <!-- Treatment & Pricing -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <h4 class="text-lg font-bold text-gray-900">💊 Treatment Information</h4>
                        <p><strong>Name:</strong> <span id="treatment-name"></span></p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <h4 class="text-lg font-bold text-gray-900">💰 Pricing Summary</h4>
                        <p><strong>Sub Total:</strong> <span id="sub-total"></span></p>
                        <p><strong>Discount:</strong> <span id="discount"></span></p>
                        <p><strong>Shipping Charge:</strong> <span id="shipping_charge"></span></p>
                        <p><strong>Tax:</strong> <span id="tax"></span></p>
                        <p><strong>Total Price:</strong> <span id="total-price"></span></p>
                    </div>
                </div>

                <!-- Order Note -->
                <h4 class="text-lg font-bold text-gray-900 mt-4">📝 Order Note</h4>
                <textarea id="order-note" class="italic text-gray-600 w-full p-2 border rounded-lg"></textarea>
                <button id="save-order-note"
                        class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition">
                    Save Note
                </button>

                <!-- Order Prescription Section -->
                <h4 class="text-lg font-bold text-gray-900 mt-4">📝 Order Prescription</h4>
                <div class="flex items-center space-x-3">
                    <button id="toggle-prescription"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition">
                        See Prescription
                    </button>
                    <span id="toggle-prescription-icon"
                          class="cursor-pointer text-blue-500 hover:text-blue-700 text-xl">
                        👁️
                    </span>
                </div>

                <!-- Prescription Image or PDF (Initially Hidden) -->
                <div id="prescription-container" class="hidden mt-4">
                    <iframe id="prescription-file" src="" class="rounded-lg shadow-lg max-w-full h-auto"
                            style="width:100%; height:500px; display:none;"></iframe>
                    <img id="prescription-image" src="" alt="Prescription Image"
                         class="rounded-lg shadow-lg max-w-full h-auto" style="display:none;">
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="mt-5 flex justify-end space-x-3">
                <a href="#" id="downloadPDF"
                   class="bg-green-500 text-white px-5 py-2 rounded-lg shadow-md hover:bg-green-600 transition">
                    Download Invoice PDF
                </a>
                <button onclick="closeOrderModal()"
                        class="bg-red-500 text-white px-5 py-2 rounded-lg shadow-md hover:bg-red-600 transition">
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
    {{--    <script src="{{ asset('Backend/plugins/tinymc/tinymce.min.js') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <!-- Define orderDetailsRoute here -->
    <script>
        var orderDetailsRoute = @json(route('order.details', ':id'));

    </script>

    <script>

        $(document).ready(function () {

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
                        data: 'products',
                        name: 'products'
                    },
                    {
                        data: 'status_logs',
                        name: 'status_logs'
                    },
                    {
                        data: 'order_total',
                        name: 'order_total'
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


        function ShowCreateUpdateModal() {
            $('#createUpdateForm')[0].reset();
            $('#medicine_id').val('');
            $('#modalTitle').html('Create New Medicine');
            $('#modalOverlay').show().addClass('modal-open');
        }

        $('#close').click(function () {
            var modal = $('#modalOverlay');
            modal.removeClass('modal-open');
            setTimeout(function () {
                modal.hide();
            }, 200);
        });


        $(document).ready(function () {
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
                        success: function (response) {
                            if (response.success) {
                                flasher.success(response.message);
                                // Reload DataTable after successful status update
                                $('#data-table').DataTable().ajax.reload();
                            } else {
                                flasher.error('Error updating the status.');
                            }
                        },
                        error: function (error) {
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
                        $('#shipping_charge').text('$' + data.shipping_charge);
                        $('#tax').text('$' + data.tax);
                        $('#total-price').text('$' + data.total_price);

                        // Set Prescription Image
                        setPrescriptionImage(data.prescription);


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


        //see order prescription image ----start
        document.addEventListener("DOMContentLoaded", function () {
            // Event listener for button click
            document.getElementById("toggle-prescription").addEventListener("click", function () {
                document.getElementById("prescription-container").classList.toggle("hidden");
            });

            // Event listener for eye icon click
            document.getElementById("toggle-prescription-icon").addEventListener("click", function () {
                document.getElementById("prescription-container").classList.toggle("hidden");
            });
        });

        // Function to set the prescription image or PDF dynamically
        function setPrescriptionImage(imageUrl) {
            if (imageUrl) {
                let fileExtension = imageUrl.split('.').pop().toLowerCase();
                let imageElement = document.getElementById("prescription-image");
                let iframeElement = document.getElementById("prescription-file");

                if (['pdf'].includes(fileExtension)) {
                    iframeElement.src = imageUrl;
                    iframeElement.style.display = "block";
                    imageElement.style.display = "none";
                } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(fileExtension)) {
                    imageElement.src = imageUrl;
                    imageElement.style.display = "block";
                    iframeElement.style.display = "none";
                } else {
                    imageElement.style.display = "none";
                    iframeElement.style.display = "none";
                }

                document.getElementById("prescription-container").classList.remove("hidden");
            } else {
                document.getElementById("prescription-container").classList.add("hidden");
            }
        }

        //see order prescription image ----end

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

                success: function (resp) {
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
                error: function (error) {
                    // location.reload();
                } // Error
            })
        }


        //update order note
        document.getElementById('save-order-note').addEventListener('click', function () {
            var orderId = document.getElementById('order-id').textContent.trim();
            var note = document.getElementById('order-note').value.trim();

            if (!orderId) {
                alert('Order ID not found!');
                return;
            }


            var route = "{{ route('order.note.update', ':id') }}".replace(':id', orderId);


            $.ajax({
                url: route,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {note: note},
                success: function (data) {
                    if (data.success) {
                        Swal.fire('Updated!', 'Order note has been updated successfully.', 'success');
                    } else {
                        Swal.fire('Error!', 'Failed to update order note.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        });


        //edit shipping address
        $(document).ready(function () {
            // Toggle edit form and populate old data
            $('#edit-shipping-button').click(function () {
                // Get current values from displayed fields
                $('#edit-billing-name').val($('#billing-name').text());
                $('#edit-billing-email').val($('#billing-email').text());
                $('#edit-billing-address').val($('#billing-address').text());
                $('#edit-billing-contact').val($('#billing-contact').text());
                $('#edit-billing-city').val($('#billing-city').text());
                $('#edit-billing-postcode').val($('#billing-postcode').text());
                $('#edit-billing-gpNumber').val($('#billing-gpNumber').text());
                $('#edit-billing-gpAddress').val($('#billing-gpAddress').text());

                // Toggle form visibility
                $('#shipping-address-display').toggleClass('hidden');
                $('#edit-shipping-address-form').toggleClass('hidden');
            });

            // Save Updated Shipping Address with AJAX
            $('#save-shipping-address').click(function () {
                var orderId = $('#order-id').text().trim(); // Get Order ID
                var updatedData = {
                    name: $('#edit-billing-name').val(),
                    email: $('#edit-billing-email').val(),
                    address: $('#edit-billing-address').val(),
                    contact: $('#edit-billing-contact').val(),
                    city: $('#edit-billing-city').val(),
                    postcode: $('#edit-billing-postcode').val(),
                    gp_number: $('#edit-billing-gpNumber').val(),
                    gp_address: $('#edit-billing-gpAddress').val(),
                };

                if (!orderId) {
                    alert("Order ID not found!");
                    return;
                }

                var route = "{{ route('order.address.update', ':id') }}".replace(':id', orderId);

                $.ajax({
                    url: route,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: updatedData,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Updated!', 'Shipping address has been updated.', 'success');

                            // Update displayed address with new values
                            $('#billing-name').text(updatedData.name);
                            $('#billing-email').text(updatedData.email);
                            $('#billing-address').text(updatedData.address);
                            $('#billing-contact').text(updatedData.contact);
                            $('#billing-city').text(updatedData.city);
                            $('#billing-postcode').text(updatedData.postcode);
                            $('#billing-gpNumber').text(updatedData.gp_number);
                            $('#billing-gpAddress').text(updatedData.gp_address);

                            // Hide edit form & show updated address
                            $('#shipping-address-display').removeClass('hidden');
                            $('#edit-shipping-address-form').addClass('hidden');
                        } else {
                            Swal.fire('Error!', 'Failed to update shipping address.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            });
        });


    </script>

@endpush
