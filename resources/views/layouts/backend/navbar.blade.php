<nav class="sb-topnav navbar navbar-expand d-flex justify-content-between">
    <!-- Navbar Brand-->
    <!-- <a class="navbar-brand ps-3 text-uppercase" href="{{url('/')}}">Invent Tailors</a> -->
    <a class="navbar-brand ps-3 text-uppercase" id="sidebarToggle"><i class="fas fa-bars"></i></a>
    <!-- Sidebar Toggle-->
    <!-- <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button> -->
    <!-- Navbar-->
    @php
    $access = \App\Models\UserAccess::where('user_id', Auth::guard('web')->user()->id)
    ->pluck('permissions')
    ->toArray();
    $user = Auth::guard('web')->user();
    @endphp
    <ul class="navbarcustom form-inline me-0 me-md-3 my-2 my-md-0 d-flex">
        @if(in_array("dashboardView", $access) || $user->role->name == 'SuperAdmin')
        <a class="nav-link d-flex flex-column align-items-center" href="{{url('/')}}">
            <i class="fa-light fa-house text-black text-2xl"></i>
            <span class="text-xs">হোম</span>
        </a>
        @endif
        @if(in_array("orderList", $access) || $user->role->name == 'SuperAdmin')
        <a class="nav-link d-flex flex-column align-items-center" href="{{url('/manage-order')}}">
            <i class="fa-light fa-list-dropdown text-black text-2xl"></i>
            <span class="text-xs">রিপোর্ট</span>
        </a>
        @endif
        @if(in_array("orderEntry", $access) || $user->role->name == 'SuperAdmin')
        <a class="nav-link d-flex flex-column align-items-center" href="{{url('/order')}}">
            <i class="fa-light fa-circle-plus text-black text-2xl"></i>
            <span class="text-xs">অ্যাড অর্ডার</span>
        </a>
        @endif
        @if(in_array("customerPayment", $access) || $user->role->name == 'SuperAdmin')
        <a class="nav-link d-flex flex-column align-items-center" href="/customerpayment">
            <i class="fa-light fa-money-bill text-black text-2xl"></i>
            <span class="text-xs">ক্যাশ রিসিভ</span>
        </a>
        @endif
        @if(in_array("dashboardView", $access) || $user->role->name == 'SuperAdmin')
        <a class="nav-link d-flex flex-column align-items-center" href="">
            <i class="fa-light fa-clipboard-user text-black text-2xl"></i>
            <span class="text-xs">অ্যাটেনডেন্স</span>
        </a>
        @endif
    </ul>
    <ul class="navbar-nav form-inline me-0 me-md-3 my-2 my-md-0">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{asset(Auth::user()->image !=null ? Auth::user()->image : 'nouser.jpg')}}" alt="Admin Image" style="width: 30px;border-radius: 50%;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ url('/user-profile') }}">Profile</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="{{route('logout')}}">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>