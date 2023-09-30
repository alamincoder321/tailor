@php
$access = \App\Models\UserAccess::where('user_id', Auth::guard('web')->user()->id)
->pluck('permissions')
->toArray();
$user = Auth::guard('web')->user();
@endphp

<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            @if(in_array("dashboardView", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{url('/')}}">
                <div class="sb-nav-link-icon"><i class="fa-light fa-house text-black text-xl"></i></div>
                হোম
            </a>
            @endif
            @if(in_array("orderEntry", $access) || in_array("orderList", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('order.create') || Route::is('order.manage') ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="{{ Route::is('order.create') || Route::is('order.manage') ? true : false }}" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-cart-arrow-down"></i></div>
                অর্ডার
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse {{ Route::is('order.create') || Route::is('order.manage') ? 'show' : '' }}" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    @if(in_array("orderEntry", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('order.create') ? 'active' : '' }}" href="{{url('/order')}}">
                        <div class="sb-nav-link-icon"><i class="fas fa-cart-plus"></i></div>
                        অ্যাড অর্ডার
                    </a>
                    @endif
                    @if(in_array("orderList", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('order.manage') ? 'active' : '' }}" href="{{url('/manage-order')}}">
                        <div class="sb-nav-link-icon"><i class="fa-light fa-list-timeline text-black text-xl"></i></div>
                        অর্ডার লিস্ট
                    </a>
                    @endif
                </nav>
            </div>
            @endif
            @if(in_array("clothingEntry", $access) || in_array("clothingList", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('clothing.create') || Route::is('clothing.manage') ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="{{ Route::is('clothing.create') || Route::is('clothing.manage') ? true : false }}" aria-controls="collapseLayouts1">
                <div class="sb-nav-link-icon"><i class="fas fa-tshirt"></i></div>
                ক্লোথিং
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse {{ Route::is('clothing.create') || Route::is('clothing.manage') ? 'show' : '' }}" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    @if(in_array("clothingEntry", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('clothing.create') ? 'active' : '' }}" href="{{url('/clothing')}}">
                        <div class="sb-nav-link-icon"><i class="fa-light fa-clothes-hanger text-black text-xl"></i></div>
                        ক্লোথিং অ্যাড
                    </a>
                    @endif
                    @if(in_array("clothingList", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('clothing.manage') ? 'active' : '' }}" href="{{url('/manage-clothing')}}">
                        <div class="sb-nav-link-icon"><i class="fa-light fa-list-timeline text-black text-xl"></i></div>
                        ক্লোথিং লিস্ট
                    </a>
                    @endif
                </nav>
            </div>
            @endif
            @if(in_array("customerPayment", $access) || in_array("tailorPayment", $access) || in_array("expenseEntry", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('customerpayment.create') || Route::is('tailorpayment.create') || Route::is('expense.create') ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="{{ Route::is('customerpayment.create') || Route::is('tailorpayment.create') || Route::is('expense.create') ? true : false }}" aria-controls="collapseLayouts2">
                <div class="sb-nav-link-icon"><i class="far fa-money-bill-alt"></i></div>
                অ্যাকাউন্ট
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse {{ Route::is('customerpayment.create') || Route::is('tailorpayment.create') || Route::is('expense.create') ? 'show' : '' }}" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    @if(in_array("customerPayment", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('customerpayment.create') ? 'active' : '' }}" href="{{url('/customerpayment')}}">
                        <div class="sb-nav-link-icon"><i class="fa-light fa-clothes-hanger text-black text-xl"></i></div>
                        কাস্টমার পেমেন্ট
                    </a>
                    @endif
                    @if(in_array("tailorPayment", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('tailorpayment.create') ? 'active' : '' }}" href="{{url('/tailorpayment')}}">
                        <div class="sb-nav-link-icon"><i class="fa-light fa-list-timeline text-black text-xl"></i></div>
                        কারিগর পেমেন্ট
                    </a>
                    @endif
                    @if(in_array("expenseEntry", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('expense.create') ? 'active' : '' }}" href="{{url('/expense')}}">
                        <div class="sb-nav-link-icon"><i class="fa-light fa-money-bill-transfer text-black text-xl"></i></div>
                        খরচ
                    </a>
                    @endif
                </nav>
            </div>
            @endif
            @if(in_array("customerLedger", $access) || in_array("tailorLedger", $access) || in_array("customerDueList", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('customer.customerLedger') || Route::is('tailor.tailorLedger') || Route::is('customer.dueList') ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts3" aria-expanded="{{ Route::is('customer.customerLedger') || Route::is('tailor.tailorLedger') || Route::is('customer.dueList') ? true : false }}" aria-controls="collapseLayouts3">
                <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                রিপোর্ট
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse {{ Route::is('customer.customerLedger') || Route::is('tailor.tailorLedger') || Route::is('customer.dueList') ? 'show' : '' }}" id="collapseLayouts3" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    @if(in_array("customerLedger", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('customer.customerLedger') ? 'active' : '' }}" href="{{url('/customer-ledger')}}">
                        <div class="sb-nav-link-icon"><i class="fa-light fa-list-timeline text-black text-xl"></i></div>
                        কাস্টমার লেজার
                    </a>
                    @endif
                    @if(in_array("tailorLedger", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('tailor.tailorLedger') ? 'active' : '' }}" href="{{url('/tailor-ledger')}}">
                        <div class="sb-nav-link-icon"><i class="fa-light fa-list-timeline text-black text-xl"></i></div>
                        কারিগর লেজার
                    </a>
                    @endif
                    @if(in_array("customerDueList", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('customer.dueList') ? 'active' : '' }}" href="{{url('/customer-dueList')}}">
                        <div class="sb-nav-link-icon"><i class="fa-light fa-list-timeline text-black text-xl"></i></div>
                        কাস্টমার ডিউ লিস্ট
                    </a>
                    @endif
                </nav>
            </div>
            @endif
            @if(in_array("employeeEntry", $access) || in_array("employeeList", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('employee.create') || Route::is('employee.manage') ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts4" aria-expanded="{{ Route::is('employee.create') || Route::is('employee.manage') ? true : false }}" aria-controls="collapseLayouts4">
                <div class="sb-nav-link-icon"><i class="fa-light fa-user-helmet-safety text-black text-xl"></i></div>
                এমপ্লয়ী
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse {{ Route::is('employee.create') || Route::is('employee.manage') ? 'show' : '' }}" id="collapseLayouts4" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    @if(in_array("employeeEntry", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('employee.create') ? 'active' : '' }}" href="{{url('/employee')}}">
                        <div class="sb-nav-link-icon"><i class="fa fa-user-plus text-black text-xl"></i></div>
                        এমপ্লয়ী অ্যাড
                    </a>
                    @endif
                    @if(in_array("employeeList", $access) || $user->role->name == 'SuperAdmin')
                    <a class="nav-link {{ Route::is('employee.manage') ? 'active' : '' }}" href="{{url('/manage-employee')}}">
                        <div class="sb-nav-link-icon"><i class="fa-light fa-list-timeline text-black text-xl"></i></div>
                        এমপ্লয়ী ম্যানেজ
                    </a>
                    @endif
                </nav>
            </div>
            @endif
            @if(in_array("customerEntry", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link" href="{{url('/customer')}}">
                <div class="sb-nav-link-icon"><i class="fa-light fa-people-carry-box text-black text-xl"></i></div>
                কাস্টমার
            </a>
            @endif
            @if(in_array("customerSMS", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link" href="{{url('/')}}">
                <div class="sb-nav-link-icon"><i class="fa-light fa-comment-sms text-black text-xl"></i></div>
                কাস্টমার এসএমএস
            </a>
            @endif

            @if(in_array("tailorEntry", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('tailor.create') ? 'active' : '' }}" href="{{url('/tailor')}}">
                <div class="sb-nav-link-icon"><i class="fa-light fa-bars-progress text-black text-xl"></i></div>
                কারিগর ম্যানেজ
            </a>
            @endif
            @if(in_array("productEntry", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('product.create') ? 'active' : '' }}" href="{{url('/product')}}">
                <div class="sb-nav-link-icon"><i class="fa-light fa-bags-shopping text-black text-xl"></i></div>
                প্রোডাক্ট
            </a>
            @endif
            @if(in_array("brandEntry", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('brand.create') ? 'active' : '' }}" href="{{url('/brand')}}">
                <div class="sb-nav-link-icon"><i class="fa-light fa-layer-group text-black text-xl"></i></div>
                ব্র্যান্ড
            </a>
            @endif
            @if(in_array("categoryEntry", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('category.create') ? 'active' : '' }}" href="{{url('/category')}}">
                <div class="sb-nav-link-icon"><i class="fa-light fa-receipt text-black text-xl"></i></div>
                ক্যাটাগরিজ
            </a>
            @endif
            @if(in_array("userEntry", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('user.create') ? 'active' : '' }}" href="{{url('/user')}}">
                <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                ইউজার অ্যাড
            </a>
            @endif
            @if(in_array("updateSetting", $access) || $user->role->name == 'SuperAdmin')
            <a class="nav-link {{ Route::is('setting.create') ? 'active' : '' }}" href="{{url('/setting')}}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-wrench"></i></div>
                সেটিংস
            </a>
            @endif
        </div>
    </div>
</nav>