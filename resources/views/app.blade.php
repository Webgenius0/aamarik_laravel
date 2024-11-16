<!DOCTYPE html>
<html lang="en" data-sidebar-color="light" data-topbar-color="light" data-sidebar-view="default">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="MyraStudio" name="author">

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/theme.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="assets/libs/%40iconscout/unicons/css/line.css" type="text/css" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

    <!-- Head Js -->
    <script src="assets/js/head.js"></script>


</head>

<body>

    <div class="app-wrapper">

        <!-- Sidebar Menu Start -->
        <div class="app-menu">

            <!-- Brand Logo -->
            <a class='logo-box' href='index.html'>
                <img src="assets/images/logo-light.png" class="logo-light h-6" alt="Light logo">
                <img src="assets/images/logo-dark.png" class="logo-dark h-6" alt="Dark logo">
            </a>

            <!--- Menu -->
            <div data-simplebar>
                <ul class="menu">
                    <li class="menu-title">Menu</li>

                    <li class="menu-item">
                        <a class='menu-link' href='index.html'>
                            <span class="menu-icon"><i class="uil uil-estate"></i></span>
                            <span class="menu-text"> Dashboard </span>
                            <span class="badge bg-primary rounded ms-auto">01</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class='menu-link' href='chat.html'>
                            <span class="menu-icon"><i class="uil uil-hipchat"></i></span>
                            <span class="menu-text"> AI Chat </span>
                        </a>
                    </li>

                    <li class="menu-title">Custom</li>

                    <li class="menu-item">
                        <a href="javascript:void(0)" data-hs-collapse="#sidenavExtraPages" class="menu-link">
                            <span class="menu-icon"><i class="uil uil-file-plus"></i></span>
                            <span class="menu-text"> Extra Pages </span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul id="sidenavExtraPages" class="sub-menu hidden">
                            <li class="menu-item">
                                <a class='menu-link' href='pages-starter.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Starter</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='pages-invoice.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Invoice</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='pages-maintenance.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Maintenance</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0)" data-hs-collapse="#sidenavAuthPages" class="menu-link">
                            <span class="menu-icon"><i class="uil uil-sign-in-alt"></i></span>
                            <span class="menu-text"> Auth Pages </span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul id="sidenavAuthPages" class="sub-menu hidden">
                            <li class="menu-item">
                                <a class='menu-link' href='auth-login.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Log In</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='auth-register.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Register</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='auth-recoverpw.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Recover Password</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='auth-lock-screen.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Lock Screen</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0)" data-hs-collapse="#sidenavErrorPages" class="menu-link">
                            <span class="menu-icon"><i class="uil uil-sync-exclamation"></i></span>
                            <span class="menu-text"> Error Pages </span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul id="sidenavErrorPages" class="sub-menu hidden">
                            <li class="menu-item">
                                <a class='menu-link' href='pages-404.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Error 404</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='pages-500.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Error 500</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-title">Elements</li>

                    <li class="menu-item">
                        <a class='menu-link' href='ui-components.html'>
                            <span class="menu-icon"><i class="uil uil-apps"></i></span>
                            <span class="menu-text"> Components </span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0)" data-hs-collapse="#sidenavForms" class="menu-link">
                            <span class="menu-icon"><i class="uil uil-file-alt"></i></span>
                            <span class="menu-text"> Forms </span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul id="sidenavForms" class="sub-menu hidden">
                            <li class="menu-item">
                                <a class='menu-link' href='forms-elements.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Form Elements</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='forms-masks.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Masks</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='forms-editor.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Editor</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='forms-validation.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Validation</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='forms-layout.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Form Layout</span>
                                </a>
                            </li>
                        </ul>
                    </li>            

                    <li class="menu-item">
                        <a href="javascript:void(0)" data-hs-collapse="#sidenavMaps" class="menu-link">
                            <span class="menu-icon"><i class="uil uil-map-marker"></i></span>
                            <span class="menu-text"> Maps </span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul id="sidenavMaps" class="sub-menu hidden">
                            <li class="menu-item">
                                <a class='menu-link' href='maps-vector.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Vector Maps</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class='menu-link' href='maps-google.html'>
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Google Maps</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a class='menu-link' href='tables-basic.html'>
                            <span class="menu-icon"><i class="uil uil-th"></i></span>
                            <span class="menu-text"> Tables </span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class='menu-link' href='charts-apex.html'>
                            <span class="menu-icon"><i class="uil uil-chart"></i></i></span>
                            <span class="menu-text"> Chart </span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0)" data-hs-collapse="#sidenavLevel" class="menu-link">
                            <span class="menu-icon">
                                <i class="uil uil-share-alt"></i>
                            </span>
                            <span class="menu-text"> Level </span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul id="sidenavLevel" class="sub-menu hidden">
                            <li class="menu-item">
                                <a href="javascript: void(0)" class="menu-link">
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Item 1</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript: void(0)" class="menu-link">
                                    <span class="menu-dot"></span>
                                    <span class="menu-text">Item 2</span>
                                    <span class="badge bg-info rounded ms-auto">New</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0)" class="menu-link">
                            <span class="menu-icon">
                                <i class="uil uil-arrow-circle-right"></i>
                            </span>
                            <span class="menu-text"> Badge Items </span>
                            <span class="badge bg-danger rounded ms-auto">Hot</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Sidebar Menu End  -->

        <!-- Start Page Content here -->
        <div class="app-content">

            <!-- Topbar Start -->
            <header class="app-header flex items-center px-5 gap-4">

                <!-- Brand Logo -->
                <a class='logo-box' href='index.html'>
                    <img src="assets/images/logo-sm.png" class="h-6" alt="Small logo">
                </a>

                <!-- Sidenav Menu Toggle Button -->
                <button id="button-toggle-menu" class="nav-link p-2">
                    <span class="sr-only">Menu Toggle Button</span>
                    <span class="flex items-center justify-center h-6 w-6">
                        <i class="uil uil-bars text-2xl"></i>
                    </span>
                </button>

                <!-- Page Title -->
                <h4 class="text-slate-900 text-lg font-medium">Dashboard</h4>

                <button id="button-toggle-profile" class="nav-link p-2 ms-auto">
                    <span class="sr-only">Profile Menu Offcanvas Button</span>
                    <span class="flex items-center justify-center h-6 w-6">
                        <i class="uil uil-heart-rate text-2xl"></i>
                    </span>
                </button>
            </header>
            <!-- Topbar End -->

            <main class="p-6">

                <div class="grid xl:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
                    <div class="card">
                        <div class="p-5 flex items-center justify-between">
                            <span>
                                <span class="text-slate-400 font-semibold block">Total Visitors</span>
                                <span class="text-xl font-semibold"><span>4564</span></span>
                            </span>

                            <span
                                class="flex justify-center items-center h-14 w-14 bg-blue-600/5 shadow shadow-blue-600/5 text-blue-600">
                                <i class="uil uil-user text-xl"></i>
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
                                <span class="text-slate-400 font-semibold block">Orders</span>
                                <span class="text-xl font-semibold"><span>7891</span>+</span>
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
                                <span class="text-slate-400 font-semibold block">Items</span>
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
                                                <button class="hs-dropdown-toggle text-lg text-gray-600 p-2"
                                                    type="button">
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
                                            <input data-plugin="knob" data-width="175" data-height="175"
                                                data-linecap=round data-fgColor="#7a08c2" value="45" data-skin="tron"
                                                data-angleOffset="180" data-readOnly=true data-thickness=".15" />
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

                <div class="grid xl:grid-cols-2 gap-6">
                    <div class="card overflow-hidden">
                        <div class="card-header flex justify-between items-center">
                            <h4 class="card-title">Recent Buyers</h4>
                            <a href="javascript:void(0);" class="btn btn-sm bg-light !text-sm text-gray-800 ">Export</a>
                        </div>

                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle whitespace-nowrap">
                                <div class="overflow-hidden">
                                    <table class="min-w-full">
                                        <thead class="bg-light/40 border-b border-gray-200">
                                            <tr>
                                                <th class="px-6 py-3 text-start">Product</th>
                                                <th class="px-6 py-3 text-start">Customers</th>
                                                <th class="px-6 py-3 text-start">Categories</th>
                                                <th class="px-6 py-3 text-start">Popularity</th>
                                                <th class="px-6 py-3 text-start">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b border-gray-200">
                                                <td class="px-6 py-3">iPhone X</td>
                                                <td class="px-6 py-3">Tiffany W. Yang</td>
                                                <td class="px-6 py-3">
                                                    <span
                                                        class="px-2 py-1 bg-success/10 text-success text-xs rounded">Mobile</span>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div
                                                        class="flex w-full h-1.5 bg-light rounded-full overflow-hidden">
                                                        <div class="progress-bar progress-bar-striped bg-success"
                                                            role="progressbar" aria-valuenow="85" aria-valuemin="20"
                                                            aria-valuemax="100" style="width:85%"></div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3">$ 1200.00</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="px-6 py-3">iPad</td>
                                                <td class="px-6 py-3">Dale P. Warman</td>
                                                <td class="px-6 py-3">
                                                    <span
                                                        class="px-2 py-1 bg-success/10 text-success text-xs rounded">Tablet</span>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div
                                                        class="flex w-full h-1.5 bg-light rounded-full overflow-hidden">
                                                        <div class="progress-bar progress-bar-striped bg-success"
                                                            role="progressbar" aria-valuenow="72" aria-valuemin="20"
                                                            aria-valuemax="100" style="width:72%"></div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3">$ 1190.00</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="px-6 py-3">OnePlus</td>
                                                <td class="px-6 py-3">Garth J. Terry</td>
                                                <td class="px-6 py-3">
                                                    <span
                                                        class="px-2 py-1 bg-success/10 text-success text-xs rounded">Electronics</span>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div
                                                        class="flex w-full h-1.5 bg-light rounded-full overflow-hidden">
                                                        <div class="progress-bar progress-bar-striped bg-success"
                                                            role="progressbar" aria-valuenow="43" aria-valuemin="20"
                                                            aria-valuemax="100" style="width:43%"></div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3">$ 999.00</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="px-6 py-3">ZenPad</td>
                                                <td class="px-6 py-3">Marilyn D. Bailey</td>
                                                <td class="px-6 py-3">
                                                    <span
                                                        class="px-2 py-1 bg-success/10 text-success text-xs rounded">Cosmetics</span>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div
                                                        class="flex w-full h-1.5 bg-light rounded-full overflow-hidden">
                                                        <div class="progress-bar progress-bar-striped bg-success"
                                                            role="progressbar" aria-valuenow="37" aria-valuemin="20"
                                                            aria-valuemax="100" style="width:37%"></div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3">$ 1150.00</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="px-6 py-3">Pixel 2</td>
                                                <td class="px-6 py-3">Denise R. Vaughan</td>
                                                <td class="px-6 py-3">
                                                    <span
                                                        class="px-2 py-1 bg-success/10 text-success text-xs rounded">Appliences</span>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div
                                                        class="flex w-full h-1.5 bg-light rounded-full overflow-hidden">
                                                        <div class="progress-bar progress-bar-striped bg-success"
                                                            role="progressbar" aria-valuenow="82" aria-valuemin="20"
                                                            aria-valuemax="100" style="width:82%"></div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3">$ 1180.00</td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-3">Pixel 2</td>
                                                <td class="px-6 py-3">Jeffery R. Wilson</td>
                                                <td class="px-6 py-3">
                                                    <span
                                                        class="px-2 py-1 bg-success/10 text-success text-xs rounded">Mobile</span>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div
                                                        class="flex w-full h-1.5 bg-light rounded-full overflow-hidden">
                                                        <div class="progress-bar progress-bar-striped bg-success"
                                                            role="progressbar" aria-valuenow="36" aria-valuemin="20"
                                                            aria-valuemax="100" style="width:36%"></div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3">$ 1180.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->

                    <div class="card overflow-hidden">
                        <div class="card-header flex justify-between items-center">
                            <h4 class="card-title">Account Transactions</h4>
                            <a href="javascript:void(0);" class="btn btn-sm bg-light !text-sm text-gray-800 ">Export</a>
                        </div>

                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle whitespace-nowrap">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-sm">
                                        <tbody>
                                            <tr class="border-b border-gray-200">
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">4257 **** **** 7852</div>
                                                    <div class="text-xs">11 April 2023</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">$79.49</div>
                                                    <div class="text-xs">Amount</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">Visa</div>
                                                    <div class="text-xs">Card</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">Helen Warren</div>
                                                    <div class="text-xs">Pay</div>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">4427 **** **** 4568</div>
                                                    <div class="text-xs">28 Jan 2023</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">$1254.00</div>
                                                    <div class="text-xs">Amount</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">Visa</div>
                                                    <div class="text-xs">Card</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">Kayla Lambie</div>
                                                    <div class="text-xs">Pay</div>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">4265 **** **** 0025</div>
                                                    <div class="text-xs">08 Dec 2022</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">$784.25</div>
                                                    <div class="text-xs">Amount</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">Master</div>
                                                    <div class="text-xs">Card</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">Hugo Lavarack</div>
                                                    <div class="text-xs">Pay</div>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">7845 **** **** 5214</div>
                                                    <div class="text-xs">03 Dec 2022</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">$485.24</div>
                                                    <div class="text-xs">Amount</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">Stripe</div>
                                                    <div class="text-xs">Card</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">Amber Scurry</div>
                                                    <div class="text-xs">Pay</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">4257 **** **** 7852</div>
                                                    <div class="text-xs">12 Nov 2022</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">$8964.04</div>
                                                    <div class="text-xs">Amount</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">Maestro</div>
                                                    <div class="text-xs">Card</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-medium">Caitlyn Gibney</div>
                                                    <div class="text-xs">Pay</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->
                </div>

            </main>

            <!-- Footer Start -->
            <footer class="footer h-16 flex items-center px-6 border-t border-gray-200 mt-auto">
                <div class="flex md:justify-between justify-center w-full gap-4">
                    <div>
                        <p class="text-sm font-medium"><script>document.write(new Date().getFullYear())</script> © {{ $setting->title }}</p>
                    </div>
                    <div class="md:flex hidden gap-2 item-center md:justify-end">
                        <p class="text-sm font-medium">Design &amp; Develop by <a href="#" class="text-primary">{{ $setting->title }}</a></p>
                    </div>
                </div>
            </footer>
            <!-- Footer End -->

        </div>
        <!-- End Page content -->

        <div class="profile-menu">
            <div class="flex flex-col items-center h-full gap-4 py-10 px-3">
                <!-- Profile Link -->
                <a href="#" type="button" class="flex flex-col items-center gap-1">
                    <img src="assets/images/users/avatar-6.jpg" alt="user-image" class="rounded-full h-8 w-8">
                    <span class="font-medium text-base">{{ auth()->user()->name}}</span>
                    <span class="text-sm">Admin</span>
                </a>

                <!-- Search Modal Button -->
                <button type="button" data-hs-overlay="#search-modal" class="bg-white rounded-full shadow-md p-2">
                    <span class="sr-only">Search</span>
                    <span class="flex items-center justify-center h-6 w-6">
                        <i class="uil uil-search text-2xl"></i>
                    </span>
                </button>

                <!-- Fullscreen Toggle Button -->
                <div class="flex">
                    <button data-toggle="fullscreen" type="button" class="bg-white rounded-full shadow-md p-2">
                        <span class="sr-only">Fullscreen Mode</span>
                        <span class="flex items-center justify-center h-6 w-6">
                            <i class="uil uil-focus text-2xl"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>



        <div id="search-modal"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[60] overflow-x-hidden overflow-y-auto pointer-events-none">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="flex flex-col bg-white shadow-sm rounded-xl pointer-events-auto overflow-hidden">
                    <div class="relative z-[60]">
                        <input type="search" id="search-input" class="form-input ps-10">
                        <span class="absolute start-3 top-1.5">
                            <i class="uil uil-search text-lg"></i>
                        </span>
    
                        <span class="absolute end-3 top-1.5">
                            <button data-hs-overlay="#search-modal">
                                <i class="uil uil-times text-lg"></i>
                            </button>
                        </span>
                    </div>
            
                </div>
            </div>
        </div>

    </div>

    <!-- Plugin Js -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/preline/preline.js"></script>

    <!-- App Js -->
    <script src="assets/js/app.js"></script>

    <!-- Apexcharts js -->
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Knob charts js -->
    <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>

    <!-- Morris Js-->
    <script src="assets/libs/morris.js/morris.min.js"></script>

    <!-- Raphael Js-->
    <script src="assets/libs/raphael/raphael.min.js"></script>

    <!-- Dashboard Project Page js -->
    <script src="assets/js/pages/dashboard.js"></script>

</body>


<!-- Mirrored from myrathemes.com/taildash/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 03 Aug 2024 09:21:47 GMT -->
</html>