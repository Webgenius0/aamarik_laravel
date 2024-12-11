@extends('backend.app')

@section('title', 'Admin Dashboard | ' . ($setting ? $setting->title : 'SIS'))

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
                        <span class="text-xl font-semibold"><span></span></span>
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
                        <span class="text-slate-400 font-semibold block">Revenue</span>
                        <span class="text-xl font-semibold">$<span>7564</span></span>
                    </span>

                    <span
                        class="flex justify-center items-center h-14 w-14 bg-blue-600/5 shadow shadow-blue-600/5 text-blue-600">
                        <i class="uil uil-dollar-sign-alt text-xl"></i>
                    </span>
                </div>

                <div class="px-5 py-4 bg-slate-50">
                    <a href="#"
                        class="relative inline-block font-semibold tracking-wide align-middle text-base text-center border-none after:content-[''] after:absolute after:h-px after:w-0 hover:after:w-full after:end-0 hover:after:end-auto after:bottom-0 after:start-0 after:transition-all after:duration-500 text-blue-600 hover:text-blue-600 after:bg-blue-600">View
                        data <i class="uil uil-arrow-right"></i></a>
                </div>
            </div><!--end-->

            <div class="card">
                <div class="p-5 flex items-center justify-between">
                    <span>
                        <span class="text-slate-400 font-semibold block">Chats</span>
                        <span class="text-xl font-semibold"><span></span>+</span>
                    </span>

                    <span
                        class="flex justify-center items-center h-14 w-14 bg-blue-600/5 shadow shadow-blue-600/5 text-blue-600">
                        <i class="uil uil-shopping-bag text-xl"></i>
                    </span>
                </div>

                <div class="px-5 py-4 bg-slate-50">
                    <a href="#"
                        class="relative inline-block font-semibold tracking-wide align-middle text-base text-center border-none after:content-[''] after:absolute after:h-px after:w-0 hover:after:w-full after:end-0 hover:after:end-auto after:bottom-0 after:start-0 after:transition-all after:duration-500 text-blue-600 hover:text-blue-600 after:bg-blue-600">View
                        data <i class="uil uil-arrow-right"></i></a>
                </div>
            </div><!--end-->

            <div class="card">
                <div class="p-5 flex items-center justify-between">
                    <span>
                        <span class="text-slate-400 font-semibold block">Verse</span>
                        <span class="text-xl font-semibold"><span></span></span>
                    </span>

                    <span
                        class="flex justify-center items-center h-14 w-14 bg-blue-600/5 shadow shadow-blue-600/5 text-blue-600">
                        <i class="uil uil-store text-xl"></i>
                    </span>
                </div>

                <div class="px-5 py-4 bg-slate-50">
                    <a href="#"
                        class="relative inline-block font-semibold tracking-wide align-middle text-base text-center border-none after:content-[''] after:absolute after:h-px after:w-0 hover:after:w-full after:end-0 hover:after:end-auto after:bottom-0 after:start-0 after:transition-all after:duration-500 text-blue-600 hover:text-blue-600 after:bg-blue-600">View
                        data <i class="uil uil-arrow-right"></i></a>
                </div>
            </div><!--end-->
            <div class="card">
                <div class="p-5 flex items-center justify-between">
                    <span>
                        <span class="text-slate-400 font-semibold block">Verse</span>
                        <span class="text-xl font-semibold"><span>486</span></span>
                    </span>

                    <span
                        class="flex justify-center items-center h-14 w-14 bg-blue-600/5 shadow shadow-blue-600/5 text-blue-600">
                        <i class="uil uil-store text-xl"></i>
                    </span>
                </div>

                <div class="px-5 py-4 bg-slate-50">
                    <a href="#"
                        class="relative inline-block font-semibold tracking-wide align-middle text-base text-center border-none after:content-[''] after:absolute after:h-px after:w-0 hover:after:w-full after:end-0 hover:after:end-auto after:bottom-0 after:start-0 after:transition-all after:duration-500 text-blue-600 hover:text-blue-600 after:bg-blue-600">View
                        data <i class="uil uil-arrow-right"></i></a>
                </div>
            </div><!--end-->
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mb-6">

            <div class="lg:col-span-2">
                <div class="card">
                    <div class="p-5">

                        <div class="grid lg:grid-cols-3 gap-5">
                            <div class="lg:col-span-2">
                                <div class="flex justify-between items-center">
                                    <h4 class="card-title mb-4">Statistics</h4>

                                    <div class="relative">
                                        <button class="hs-dropdown-toggle text-lg text-gray-600 p-2" type="button">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>

                                        <div
                                            class="hs-dropdown-menu hidden z-10 bg-white w-44 shadow rounded border p-2 transition-all duration-300 hs-dropdown-open:translate-y-0 translate-y-3">
                                            <a class="flex items-center py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-stone-100"
                                                href="javascript:void(0)">
                                                Action
                                            </a>
                                            <a class="flex items-center py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100"
                                                href="javascript:void(0)">
                                                Another action
                                            </a>
                                            <a class="flex items-center py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100"
                                                href="javascript:void(0)">
                                                Something else here
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div id="morris-bar-example" class="morris-chart" style="height: 330px;"></div>
                            </div>

                            <div>
                                <h4 class="card-title mb-4">Stock</h4>

                                <div class="mx-auto text-center">
                                    <input data-plugin="knob" data-width="175" data-height="175" data-linecap=round
                                        data-fgColor="#7a08c2" value="45" data-skin="tron" data-angleOffset="180"
                                        data-readOnly=true data-thickness=".15" />
                                    <h5 class="text-gray-400 mt-5">Total sales made today</h5>
                                </div>


                                <div class="text-center w-full">
                                    <p class="text-gray-400 w-75 mx-auto line-clamp-2">Traditional heading
                                        elements are
                                        designed to work best in the meat of your page content.</p>
                                </div>

                                <div class="flex gap-4 text-center mt-3">
                                    <div class="w-1/2">
                                        <p class="text-gray-400 text-xl mb-1 truncate">Target</p>
                                        <h4><i class="fas fa-arrow-up text-success mr-1"></i>$7.8k</h4>

                                    </div>
                                    <div class="w-1/2">
                                        <p class="text-gray-400 text-xl mb-1 truncate">Last week</p>
                                        <h4><i class="fas fa-arrow-down text-danger mr-1"></i>$1.4k</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="p-5">
                    <div class="flex justify-between items-center">
                        <h4 class="card-title mb-4">Total Revenue</h4>

                        <div class="relative">
                            <button class="hs-dropdown-toggle text-lg text-gray-600 p-2" type="button">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>

                            <div
                                class="hs-dropdown-menu hidden z-10 bg-white w-44 shadow rounded border p-2 transition-all duration-300 hs-dropdown-open:translate-y-0 translate-y-3">
                                <a class="flex items-center py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-stone-100"
                                    href="javascript:void(0)">
                                    Action
                                </a>
                                <a class="flex items-center py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100"
                                    href="javascript:void(0)">
                                    Another action
                                </a>
                                <a class="flex items-center py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100"
                                    href="javascript:void(0)">
                                    Something else here
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="morris-line-example" class="morris-chart" style="height: 265px;"></div>

                    <div class="flex text-center mt-4">
                        <div class="w-1/2">
                            <h4 class="text-xl">$7841.12</h4>
                            <p class="text-gray-400">Total Revenue</p>
                        </div>
                        <div class="w-1/2">
                            <h4 class="text-xl">17</h4>
                            <p class="text-gray-400">Open Compaign</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection


@push('scripts')
@endpush
