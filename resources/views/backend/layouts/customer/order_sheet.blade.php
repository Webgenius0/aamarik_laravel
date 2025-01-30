@extends('backend.app')

@section('title', 'Customer Orders | ' . $customer->name)

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.tailwind.min.css">
@endpush

@section('content')
    <main class="p-6">
        <div class="card bg-white overflow-hidden">
            <div class="p-4">
                <h2 class="text-xl font-semibold text-gray-800">Orders for {{ $customer->name }}</h2>
                <div class="overflow-x-auto custom-scroll">
                    <table id="orders-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>

                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orders as $order)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{'#'. $order->uuid }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->sub_total }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->discount }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->total_price }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->status }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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

                <!-- User Info & Billing Address -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <!-- User Info -->
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <h4 class="text-lg font-bold text-gray-900">👤 User Information</h4>
                        <p><strong>Name:</strong> <span id="user-name"></span></p>
                        <p><strong>Email:</strong> <span id="user-email"></span></p>
                    </div>

                    <!-- Billing Address -->
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <h4 class="text-lg font-bold text-gray-900">🏠 Billing Address</h4>
                        <p><strong>Name:</strong> <span id="billing-name"></span></p>
                        <p><strong>Email:</strong> <span id="billing-email"></span></p>
                        <p><strong>Address:</strong> <span id="billing-address"></span></p>
                        <p><strong>Contact:</strong> <span id="billing-contact"></span></p>
                        <p><strong>City:</strong> <span id="billing-city"></span></p>
                        <p><strong>Postcode:</strong> <span id="billing-postcode"></span></p>
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

                <!-- Review Section -->
                <div class="mt-6">
                    <h4 class="text-lg font-bold text-gray-900">✍️ Leave a Review</h4>
                    <textarea id="review-text" class="w-full p-2 border rounded-lg mt-2" rows="4" placeholder="Write your review here..."></textarea>
                    <div class="mt-4 flex justify-end">
                        <button onclick="submitReview()" class="bg-blue-500 text-white px-5 py-2 rounded-lg shadow-md hover:bg-blue-600 transition">
                            Submit Review
                        </button>
                    </div>
                </div>

                <!-- Close Button -->
                <div class="mt-5 flex justify-end">
                    <button onclick="closeOrderModal()" class="bg-red-500 text-white px-5 py-2 rounded-lg shadow-md hover:bg-red-600 transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#orders-table').DataTable({
                order: [],
                lengthMenu: [
                    [25, 50, 100, 200, 500, -1],
                    [25, 50, 100, 200, 500, "All"]
                ],
                processing: true,
                responsive: true,
                language: {
                    processing: '<i class="ace-icon d-none fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                },
                pagingType: "full_numbers",
                dom: "<'flex justify-between items-center mb-3'<'w-full sm:w-auto'l><'w-full text-center sm:w-auto'B><'w-full sm:w-auto'f>>tipr",
            });
        });




        //show order details
        // Open the modal
        function viewOrderDetails(id) {
            var route = "{{ route('customer.order.details', ':id') }}".replace(':id', id);

            $.ajax({
                url: route,
                type: 'GET',
                success: function (response) {
                    if (response.success === true) {
                        var data = response.data;

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

                        // Fill Order Items Table
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
            $('#orderDetailsModal').addClass('hidden');
            $('#modalContent').removeClass('scale-100').addClass('scale-95');
        }
    </script>
@endpush
