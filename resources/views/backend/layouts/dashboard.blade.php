@extends('backend.app')

@section('title', 'Admin Dashboard | ' . ($setting ? $setting->title : 'PrimeCare'))

@push('styles')
    <style>

    </style>
@endpush

@section('content')
    <main class="p-6">
        <div class="grid xl:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
            <div class="card">
                <div class="p-5 flex items-center justify-between">
                    <span>
                        <span class="text-slate-400 font-semibold block">Total Users</span>
                        <span class="text-xl font-semibold"><span></span>{{ $total_users }}</span>
                    </span>

                    <span
                        class="flex justify-center items-center h-14 w-14 bg-blue-600/5 shadow shadow-blue-600/5 text-blue-600">
                        <i class="uil uil-user text-xl"></i>
                    </span>
                </div>

                <div class="px-5 py-4 bg-slate-50">
                    <a href="#"
                        class="relative inline-block font-semibold tracking-wide align-middle text-base text-center border-none after:content-[''] after:absolute after:h-px after:w-0 hover:after:w-full after:end-0 hover:after:end-auto after:bottom-0 after:start-0 after:transition-all after:duration-500 text-blue-600 hover:text-blue-600 after:bg-blue-600"></a>
                </div>
            </div><!--end-->

            <div class="card">
                <div class="p-5 flex items-center justify-between">
                    <span>
                        <span class="text-slate-400 font-semibold block">New order</span>
                        <span class="text-xl font-semibold"><span></span>{{ $today_orders ?? '0'  }}</span>
                    </span>

                    <span
                        class="flex justify-center items-center h-14 w-14 bg-blue-600/5 shadow shadow-blue-600/5 text-blue-600">
                        <i class="uil uil-shopping-cart-alt text-xl"></i>
                    </span>
                </div>

                <div class="px-5 py-4 bg-slate-50">
                    <a href="{{ route('orders.index')  }}"
                        class="relative inline-block font-semibold tracking-wide align-middle text-base text-center border-none after:content-[''] after:absolute after:h-px after:w-0 hover:after:w-full after:end-0 hover:after:end-auto after:bottom-0 after:start-0 after:transition-all after:duration-500 text-blue-600 hover:text-blue-600 after:bg-blue-600">View
                        data <i class="uil uil-arrow-right "></i></a>
                </div>
            </div><!--end-->

            <div class="card">
                <div class="p-5 flex items-center justify-between">
                    <span>
                        <span class="text-slate-400 font-semibold block">Total Medicine</span>
                        <span class="text-xl font-semibold"><span></span>{{ $total_medicines ?? '0'  }}</span>
                    </span>

                    <span
                        class="flex justify-center items-center h-14 w-14 bg-blue-600/5 shadow shadow-blue-600/5 text-blue-600">
                      <i class="uil uil-medkit text-xl"></i>

                    </span>
                </div>

                <div class="px-5 py-4 bg-slate-50">
                    <a href="{{ route('medicine.index')  }}"
                        class="relative inline-block font-semibold tracking-wide align-middle text-base text-center border-none after:content-[''] after:absolute after:h-px after:w-0 hover:after:w-full after:end-0 hover:after:end-auto after:bottom-0 after:start-0 after:transition-all after:duration-500 text-blue-600 hover:text-blue-600 after:bg-blue-600">View
                        data <i class="uil uil-arrow-right "></i></a>
                </div>
            </div><!--end-->

            <div class="card">
                <div class="p-5 flex items-center justify-between">
                    <span>
                        <span class="text-slate-400 font-semibold block">Low Stock Medicine</span>
                        <span class="text-xl font-semibold"><span></span>{{ $low_stock ?? '0'  }}</span>
                    </span>

                    <span
                        class="flex justify-center items-center h-14 w-14 bg-blue-600/5 shadow shadow-blue-600/5 text-blue-600">
                        <i class="uil uil-exclamation-triangle text-xl text-red-500"></i>

                    </span>
                </div>

                <div class="px-5 py-4 bg-slate-50">
                    <a href="{{ route('medicine.index')  }}"
                       class="relative inline-block font-semibold tracking-wide align-middle text-base text-center border-none after:content-[''] after:absolute after:h-px after:w-0 hover:after:w-full after:end-0 hover:after:end-auto after:bottom-0 after:start-0 after:transition-all after:duration-500 text-blue-600 hover:text-blue-600 after:bg-blue-600">View
                        data <i class="uil uil-arrow-right "></i></a>
                </div>
            </div><!--end-->


            <div class="card">
                <div class="p-5 flex items-center justify-between">
                    <span>
                        <span class="text-slate-400 font-semibold block">Medicines Expiring in 5 Days or Less</span>
                        <span class="text-xl font-semibold"><span></span>{{ $expire_date_medicines ?? '0'  }}</span>
                    </span>

                    <span
                        class="flex justify-center items-center h-14 w-14 bg-blue-600/5 shadow shadow-blue-600/5 text-blue-600">
                        <i class="uil uil-exclamation-triangle text-xl text-red-500"></i>

                    </span>
                </div>

                <div class="px-5 py-4 bg-slate-50">
                    <a href="{{ route('medicine.index')  }}"
                       class="relative inline-block font-semibold tracking-wide align-middle text-base text-center border-none after:content-[''] after:absolute after:h-px after:w-0 hover:after:w-full after:end-0 hover:after:end-auto after:bottom-0 after:start-0 after:transition-all after:duration-500 text-blue-600 hover:text-blue-600 after:bg-blue-600">View
                        data <i class="uil uil-arrow-right "></i></a>
                </div>
            </div><!--end-->
        </div>

        <!--Order progress-->
        <div class="grid xl:grid-cols-5 md:grid-cols-2 gap-4 mb-6 ">
            <div class="row-span-2">
                <div class="card mb-3">
                    <div class="card-header">Order Statistics</div>
                    <div class="card-body">
                        <canvas id="orderChart"></canvas>
                    </div>
                </div>
            </div>
            <!--today order-->
            <div class="row-span-2">
                <div class="card mb-3 p-4 text-center">
                    <h4 class="card-title mb-4">Orders</h4>

                    <!-- Today's Orders -->
                    <div class="d-flex justify-content-center">
                        <input data-plugin="knob" data-width="175" data-height="175" data-linecap="round"
                               data-fgColor="#7a08c2" value="{{ $today_orders ?? '0' }}" data-skin="tron"
                               data-angleOffset="180" data-readOnly="true" data-thickness=".15" />
                    </div>
                    <h5 class="text-gray-400 mt-3">Total orders today</h5>

                    <!-- Yesterday's Orders -->
                    <p class="text-gray-400 mt-3">Yesterday's Orders: {{ $yesterday_orders ?? '0' }}</p>

                    <!-- Order Difference -->
                    <div class="mt-3">
                        <p class="text-gray-400 text-xl mb-1">Difference</p>
                        <h4>
                            @if($order_difference > 0)
                                <i class="fas fa-arrow-up text-success mr-1"></i>
                            @elseif($order_difference < 0)
                                <i class="fas fa-arrow-down text-danger mr-1"></i>
                            @else
                                <i class="fas fa-minus text-gray-500 mr-1"></i>
                            @endif
                            {{ abs($order_difference) }}
                        </h4>
                    </div>
                </div>
            </div>

        </div>


        <!--filter-->
        <div class=" p-6 bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">Order History</h2>
                <button id="downloadPDF"  class="bg-green-600 hover:bg-green-700 text-black font-medium px-5 py-2 rounded-lg shadow-md transition-all duration-300 ease-in-out">
                    <i class="fas fa-file-pdf mr-2"></i> Download PDF
                </button>
            </div>


            <!-- Filters -->
            <div class="flex gap-4 mb-6">
                <select id="filter-orders" class="border rounded-lg p-2 w-full text-gray-600">
                    <option value="">Select Filter</option>
                    <option value="today">Today</option>
                    <option value="this_month">This Month</option>
                    <option value="this_year">This Year</option>
                    <option value="last_year">Last Year</option>
                </select>

                <input type="date" id="startDate" class="border rounded-lg p-2 w-full text-gray-600" />
                <input type="date" id="endDate" class="border rounded-lg p-2 w-full text-gray-600" />

                <button id="filterButton" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Filter
                </button>
            </div>

            <div id="orderSummary">
                <!-- Totals -->
                <p class="text-lg font-medium">Total Sale: <span id="totalSaleAmount" class="font-semibold text-green-600">$0</span></p>
                <p class="text-lg font-medium">Buying Amount: <span id="buyingAmount" class="font-semibold text-red-600">$0</span></p>
                <p class="text-lg font-medium">Selling Amount: <span id="sellingAmount" class="font-semibold text-blue-600">$0</span></p>
                <p class="text-lg font-medium">Total Profit: <span id="totalProfit" class="font-semibold text-purple-600">$0</span></p>
                <p class="text-lg font-medium">Total Tax: <span id="totalTax" class="font-semibold text-gray-600">$0</span></p>
                <p class="text-lg font-medium">Total Shipping Charge: <span id="totalShippingCharge" class="font-semibold text-gray-600">$0</span></p>
                <p class="text-lg font-medium">Total Royal Mail Charge : <span id="totalRoyalMailCharge" class="font-semibold text-gray-600">$0</span></p>


                <!-- Orders Table -->
                <table id="ordersTable" class="w-full border-collapse shadow-md rounded-lg mt-2">
                    <thead class="bg-gray-100 text-gray-700">
                    <tr class="border-b">
                        <th class="p-4">Order UUID</th>
                        <th class="p-4">Summery</th>
                        <th class="p-4">Date</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>


    </main>
@endsection


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            $('#filter-orders').change(function() {
                let filter = $(this).val();

                $.ajax({
                    url: "{{ route('dashboard') }}",
                    type: "GET",
                    data: { filter: filter },
                    success: function(response) {
                        $('#orders-trend').text(response.orders_trend);
                        $('#total-sales').text('$' + parseFloat(response.total_sales).toFixed(2));
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });


        $(document).ready(function () {
            let ordersRoute = "{{ route('orders.filter') }}"; // Get AJAX route
            fetchOrders();

            $("#filterButton").click(function () {
                let startDate = $("#startDate").val();
                let endDate = $("#endDate").val();
                fetchOrders(startDate, endDate);
            });

            $('#filter-orders').on("change", function () {
                let filter = $(this).val();
                let startDate = null, endDate = null;
                let today = new Date();

                switch (filter) {
                    case "today":
                        startDate = formatDate(today);
                        endDate = formatDate(today);
                        break;
                    case "this_month":
                        startDate = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
                        endDate = formatDate(today);
                        break;
                    case "this_year":
                        startDate = formatDate(new Date(today.getFullYear(), 0, 1));
                        endDate = formatDate(today);
                        break;
                    case "last_year":
                        startDate = formatDate(new Date(today.getFullYear() - 1, 0, 1));
                        endDate = formatDate(new Date(today.getFullYear() - 1, 11, 31));
                        break;
                }

                fetchOrders(startDate, endDate);
            });

            function fetchOrders(startDate = null, endDate = null) {
                $.ajax({
                    url: ordersRoute,
                    type: "GET",
                    data: { start_date: startDate, end_date: endDate },
                    success: function (response) {
                        console.log("Orders received:", response);

                        let orders = response.orders ? response.orders : response;
                        $("#ordersTable tbody").empty();

                        if (!orders || orders.length === 0) {
                            $("#ordersTable tbody").append(`
                        <tr class="border-b">
                            <td class="p-4 text-center" colspan="3">No Orders Found</td>
                        </tr>
                    `);
                        } else {
                            $.each(orders, function (index, order) {
                                let orderRow = `
                            <tr class="border-b">
                                <td class="p-4">${order.uuid}</td>
                                <td class="p-4">
                                    <span>Sub Total : ${order.sub_total} </span>
                                    <span>Discount Amount : ${order.discount} </span>
                                    <span>Tax : ${order.tax} </span>
                                    <span>Shipping Charge : ${order.shipping_charge} </span>
                                    <span>Total Price : ${order.total_price} </span>
                                </td>
                                <td class="p-4">${formatDate(order.created_at)}</td>
                            </tr>`;
                                $("#ordersTable tbody").append(orderRow);
                            });
                        }

                        $("#totalSaleAmount").text(`$${response.total_sale_amount}`);
                        $("#buyingAmount").text(`$${response.buying_amount}`);
                        $("#sellingAmount").text(`$${response.selling_amount}`);
                        $("#totalProfit").text(`$${response.total_profit}`);
                        $("#totalTax").text(`$${response.total_tax}`);
                        $("#totalDiscount").text(`$${response.total_discount}`);
                        $("#totalShippingCharge").text(`$${response.total_shipping_charge}`);
                        $("#totalRoyalMailCharge").text(`$${response.total_royal_mail_charge}`);
                    },
                    error: function (xhr) {
                        console.log("AJAX Error:", xhr.responseText);
                    }
                });
            }

            function formatDate(date) {
                let d = new Date(date);
                return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
            }
        });


        document.getElementById("downloadPDF").addEventListener("click", function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF("p", "mm", "a4");

            let orderSummary = document.getElementById("orderSummary");

            html2canvas(orderSummary, {
                scale: 2, // Improve quality
                useCORS: true
            }).then(canvas => {
                let imgData = canvas.toDataURL("image/png");
                let imgWidth = 190; // A4 width in mm
                let imgHeight = (canvas.height * imgWidth) / canvas.width; // Maintain aspect ratio

                doc.addImage(imgData, "PNG", 10, 10, imgWidth, imgHeight);
                doc.save("Order_Summary.pdf"); // Download the PDF
            }).catch(error => {
                console.error("Error generating PDF:", error);
            });
        });






        var ctx = document.getElementById('orderChart').getContext('2d');
        var orderChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($orders_by_month->toArray())) !!},
                datasets: [{
                    label: 'Orders This Year',
                    data: {!! json_encode(array_values($orders_by_month->toArray())) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                    {
                        label: 'Orders Last Year',
                        data: {!! json_encode(array_values($previous_orders_by_month->toArray())) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
