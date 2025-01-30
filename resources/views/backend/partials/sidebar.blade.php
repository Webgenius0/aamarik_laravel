
@push('styles')
.menu-link.active {
    background-color: #007bff;
    color: white;
}
@endpush
<!-- Sidebar Menu Start -->

<div class="app-menu">

    <!-- Brand Logo -->
    <a class='logo-box' href="{{route('dashboard')}}">
        <img src="{{ asset($setting->logo ?? 'uploads/defult-image/logo.png') }}" class="logo-light h-16" alt="Light logo">
        <img src="{{ asset($setting->logo ?? 'uploads/defult-image/logo.png') }}" class="logo-dark h-16" alt="Dark logo">
    </a>

    <!--- Menu -->
    <div data-simplebar>
        <ul class="menu">
            <li class="menu-title">Menu</li>

            <li class="menu-item">
                <a class='menu-link' href="{{route('dashboard')}}">
                    <span class="menu-icon"><i class="uil uil-estate"></i></span>
                    <span class="menu-text"> Dashboard </span>
                    <span class="badge bg-primary rounded ms-auto">01</span>
                </a>
            </li>
            <li class="menu-title">Management</li>
            <li class="menu-item">
                <a class="menu-link {{Request::RouteIs('') ? 'bg-blue-500 text-white' : '' }}" href="{{route('coupon.index')}}">
                    <span class="menu-icon"><i class="uil uil-percentage"></i></span>
                    <span class="menu-text"> Create Coupon </span>
                </a>
            </li>

            <li class="menu-item">
                <a class="menu-link {{Request::RouteIs('orders') ? 'bg-blue-500 text-white' : '' }}" href="{{route('orders.index')}}">
                    <span class="menu-icon"><i class="uil uil-shopping-cart-alt"></i></span>
                    <span class="menu-text">  Order Management </span>
                </a>
            </li>

            <li class="menu-item">
                <a class="menu-link {{ Request::RouteIs('customers*')  ? 'bg-blue-500 text-white' : '' }}" href="{{route('customer.index')}}">
                    <span class="menu-icon"><i class="uil uil-users-alt"></i></span>
                    <span class="menu-text">  Customers Management </span>
                </a>
            </li>

            <li class="menu-item">
                <a class="menu-link {{ Request::RouteIs('employees*')  ? 'bg-blue-500 text-white' : '' }}" href="{{route('employees.index')}}">
                    <span class="menu-icon"><i class="uil uil-user-md"></i></span>
                    <span class="menu-text"> Employee Management </span>
                </a>
            </li>

            <li class="menu-title">Frontend</li>

            <li class="menu-item">
                <a class="menu-link {{Request::RouteIs('faq.index') ? 'bg-blue-500 text-white' : '' }}" href="{{route('faq.index')}}">
                    <span class="menu-icon"><i class="uil uil-compass"></i></span>
                    <span class="menu-text"> FAQ </span>
                </a>
            </li>
            <!-- Doctor -->
            <li class="menu-item">
                <a href="javascript:void(0)" data-hs-collapse="#doctorlevel"  class="menu-link  {{ (Request::RouteIs('doctors.department') || Request::RouteIs('doctor.index')) ? 'active' : '' }}"
                >
                    <span class="menu-icon">
                    <i class="fa fa-stethoscope" style="font-size: 24px;"></i>

                    </span>
                    <span class="menu-text"> Doctor </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul id="doctorlevel" class="sub-menu hidden">
                <li class="menu-item">
                        <a href="{{route('doctors.department')}}" class="menu-link {{Request::RouteIs('doctors.department') ? 'active' : ''}}">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Create Department</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{route('doctor.index')}}" class="menu-link {{Request::RouteIs('doctor.index') ? 'active' : ''}}">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Doctor List</span>
                        </a>
                    </li>


                </ul>
            </li>
            <!-- End Doctor -->

            <!-- Medicine start -->
            <li class="menu-item">
                <a class="menu-link {{Request::RouteIs('medicine.index') ? 'bg-blue-500 text-white' : '' }}" href="{{route('medicine.index')}}">
                    <span class="menu-icon"><i class="fa-solid fa-pills"></i>

                    </span>
                    <span class="menu-text"> Medicine </span>
                </a>
            </li>
            <!-- End Medicine -->
             <!-- Treatment start -->
             <li class="menu-item">
                <a class="menu-link {{Request::RouteIs('treatment.index') ? 'bg-blue-500 text-white' : '' }}" href="{{route('treatment.list')}}">
                    <span class="menu-icon"><i class="fa-solid fa-hospital"></i>



                    </span>
                    <span class="menu-text"> Treatment </span>
                </a>
            </li>
             <!-- End Treatment -->
            <!-- CMS -->
            <li class="menu-item">
                <a href="javascript:void(0)" data-hs-collapse="#sidenavLevel1" class="menu-link {{Request::RouteIs('banner')||Request::RouteIs('home.section') ? 'active' : ''}}">
                    <span class="menu-icon">
                        <i class="uil uil-share-alt"></i>
                    </span>
                    <span class="menu-text"> CMS </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul id="sidenavLevel1" class="sub-menu hidden">
                    <li class="menu-item">
                        <a href="{{route('banner')}}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Banner</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{route('home.section')}}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Home Banner</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{route('cms.personalized')}}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Personalized Helthcare</span>
                        </a>
                    </li>



                </ul>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0)" data-hs-collapse="#sidenavLevel" class="menu-link">
                    <span class="menu-icon">
                        <i class="uil uil-share-alt"></i>
                    </span>
                    <span class="menu-text"> Settings </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul id="sidenavLevel" class="sub-menu hidden">
                    <li class="menu-item">
                        <a href="{{route('admin.edit.profile')}}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Profile</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.setting')}}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Application Settings</span>
                            <span class="badge bg-danger rounded ms-auto">Hot</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('social.setting')}}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Social Media Settings</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{route('mail.setting')}}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Mail Settings</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('stripe.setting')}}" class="menu-link">
                            <span class="menu-dot"></span>
                            <span class="menu-text">Stripe Settings</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item">
                <form method="POST" action="{{ route('logout') }}">
                    <a class='menu-link' href='javascript:void(0)'>
                        <span class="menu-icon"><i class="uil uil-arrow-circle-right"></i></i></span>
                        <span class="menu-text">
                            @csrf
                            <button type="submit" class="">
                                Log Out
                            </button>
                        </span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</div>
<!-- Sidebar Menu End  -->
