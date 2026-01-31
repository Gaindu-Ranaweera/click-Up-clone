<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        
        <div>
            <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
                <img src="{{ asset('backend/assets/images/logo.png') }}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
                <img src="{{ asset('backend/assets/images/logo.png') }}" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text">Good {{ now()->format('A') === 'AM' ? 'Morning' : 'Evening' }}, 
                    <span class="text-black fw-bold">{{ auth()->user()->name }}</span>
                </h1>
                <h3 class="welcome-sub-text">Your performance summary this week</h3>
            </li>
        </ul>
        
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <!-- Browser-style Navigation Buttons -->
                <div class="cus_gk_nav_buttons d-flex align-items-center me-3">
                    <button type="button" class="btn cus_gk_btn_nav_arrow" onclick="window.history.back()" title="Go Back">
                        <i class="mdi mdi-arrow-left"></i>
                    </button>
                    <button type="button" class="btn cus_gk_btn_nav_arrow" onclick="window.history.forward()" title="Go Forward">
                        <i class="mdi mdi-arrow-right"></i>
                    </button>
                </div>
            </li>
            <li class="nav-item d-none d-lg-block">
                <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                    <span class="input-group-addon input-group-prepend border-right">
                        <span class="icon-calendar input-group-text calendar-icon"></span>
                    </span>
                    <input type="text" class="form-control">
                </div>
            </li>
            <li class="nav-item">
                <form class="search-form" action="#">
                    <i class="icon-search"></i>
                    <input type="search" class="form-control" placeholder="Search Here" title="Search here">
                </form>
            </li>
            @php
                $pendingRequests = \App\Models\FeatureRequest::where('status', 'pending')->count();
            @endphp
            @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="icon-bell"></i>
                    @if($pendingRequests > 0)
                    <span class="count"></span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
                    <a class="dropdown-item py-3 border-bottom" href="{{ route('admin.feature-requests.index') }}">
                        <p class="mb-0 fw-medium float-start">You have {{ $pendingRequests }} pending requests</p>
                        <span class="badge badge-pill badge-primary float-end">View all</span>
                    </a>
                </div>
            </li>
            @endif
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(auth()->user()->profile_picture)
                        <img class="img-xs rounded-circle" src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile image">
                    @else
                        <img class="img-xs rounded-circle" src="{{ asset('backend/assets/images/faces/face8.jpg') }}" alt="Profile image">
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        @if(auth()->user()->profile_picture)
                            <img class="img-md rounded-circle" src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile image">
                        @else
                            <img class="img-md rounded-circle" src="{{ asset('backend/assets/images/faces/face8.jpg') }}" alt="Profile image">
                        @endif
                        <p class="mb-1 mt-3 fw-semibold">{{ auth()->user()->name }}</p>
                        <p class="fw-light text-muted mb-0">{{ auth()->user()->email }}</p>
                    </div>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile
                    </a>
                    @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                        <i class="dropdown-item-icon mdi mdi-account-multiple-outline text-primary me-2"></i> User Management
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out
                        </button>
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
