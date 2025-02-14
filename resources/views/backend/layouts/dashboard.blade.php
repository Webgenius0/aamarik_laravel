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

            <!--filter order-->
            <div class="row-span-2">
                <div class="card mb-3 p-4 text-center">
                    <h4 class="card-title mb-4">Order Trends</h4>

                    <!-- filter -->
                    <div class="col-md-6 text-end">
                        <select id="filter-orders" class="form-select d-inline-block w-auto">
                            <option value="today" {{ $selectedFilter == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="this_month" {{ $selectedFilter == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="this_year" {{ $selectedFilter == 'this_year' ? 'selected' : '' }}>This Year</option>
                            <option value="last_month" {{ $selectedFilter == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        </select>
                    </div>

                    <!-- Display Orders Trend -->
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Orders for Selected Period</h5>
                            <h3 id="orders-trend" class="display-4">{{ $orders_trend }}</h3>

                            <h5 class="text-muted">Total Sales for Selected Period</h5>
                            <h3 id="total-sales" class="display-4">${{ number_format($total_sales, 2) }}</h3>
                        </div>
                    </div>

                </div>
            </div>

        </div>


    </main>
@endsection


@push('scripts')

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
                        console.log(response);
                        $('#orders-trend').text(response.orders_trend);
                        $('#total-sales').text('$' + parseFloat(response.total_sales).toFixed(2));
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
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
