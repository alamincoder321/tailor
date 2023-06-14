<nav class="sb-topnav navbar navbar-expand">
    <!-- Navbar Brand-->
    <!-- <a class="navbar-brand ps-3 text-uppercase" href="{{url('/')}}">Invent Tailors</a> -->
    <a class="navbar-brand ps-3 text-uppercase" id="sidebarToggle"><i class="fas fa-bars"></i></a>
    <!-- Sidebar Toggle-->
    <!-- <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button> -->

    <!-- Navbar-->
    <ul class="navbar-nav form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{asset('nouser.jpg')}}" alt="Admin Image" style="width: 30px;border-radius: 50%;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#!">Settings</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="{{route('logout')}}">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>