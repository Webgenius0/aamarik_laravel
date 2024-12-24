<!-- Sidebar Menu Start -->
<div class="app-menu">

    <!-- Brand Logo -->
    <a class='logo-box' href='{{route('dashboard')}}'>
        <img src="{{ asset($setting->logo ?? 'backend/assets/images/sis-logo.png') }}" class="logo-light h-16" alt="Light logo">
        <img src="{{ asset($setting->logo ?? 'backend/assets/images/sis-logo.png') }}" class="logo-dark h-16" alt="Dark logo">
    </a>

    <!--- Menu -->
    <div data-simplebar>
        <ul class="menu">
            <li class="menu-title">Menu</li>

            <li class="menu-item">
                <a class='menu-link' href='{{route('dashboard')}}'>
                    <span class="menu-icon"><i class="uil uil-estate"></i></span>
                    <span class="menu-text"> Dashboard </span>
                    <span class="badge bg-primary rounded ms-auto">01</span>
                </a>
            </li>
            <li class="menu-item">
                <a class='menu-link' href='{{route('location.index')}}'>
                    <span class="menu-icon"><i class="uil uil-compass"></i></span>
                    <span class="menu-text"> Locations </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link' href='{{route('group.index')}}'>
                    <span class="menu-icon"><i class="uil uil-compass"></i></span>
                    <span class="menu-text"> Location Groups </span>
                </a>
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
