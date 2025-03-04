<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('admin-assets/img/skatelogo.jpg')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">MMS</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
								with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('admin.dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if(auth('admin')->user()->role == 1 || auth('admin')->user()->role == 3)
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Player
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('players.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Full-Time Players</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('players.half') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Half-Time Players</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('players.create') }}" class="nav-link">
                                <i class="fa fa-plus"></i>
                                <p>Add New Player</p>
                            </a>
                        </li>
                        @if(auth('admin')->user()->role == 1)
                        <li class="nav-item">
                            <a href="{{ route('player.listAdditional') }}" class="nav-link">
                            <i class="fa fa-child"></i>
                                <p>Additional Player</p>
                            </a>
                        </li>
                        @endif
                        <!-- Add more sub-items as needed -->
                    </ul>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{route('summary.list')}}" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        <p>Day Summary</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('report.list')}}" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <p>All Daily Report</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dailyincomes.index')}}" class="nav-link">
                        <i class="fab fa-bitcoin"></i>
                        <p>Day Incomes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dailyexpenses.index')}}" class="nav-link">
                        <i class="fas fa-chart-pie"></i>
                        <p>Day Expenses</p>
                    </a>
                </li>



                @if(auth('admin')->user()->role == 1 || auth('admin')->user()->role == 3)
                <li class="nav-item">
                    <a href="{{route('brands.index')}}" class="nav-link">
                        <svg class="h-6 nav-icon w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p>Brands</p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{route('products.index')}}" class="nav-link">
                        <i class="fas fa-shopping-bag"></i>
                        <p>Buy Products</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('productsells.index')}}" class="nav-link">
                        <i class="fas fa-shipping-fast"></i>
                        <p>Sell Products</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('products.report')}}" class="nav-link">
                        <i class="fas fa-store"></i>
                        <p>Products Inventory</p>
                    </a>
                </li>




                <li class="nav-item">
                    <a href="{{route('summary.index')}}" class="nav-link">
                        <i class="fas fa-file"></i>
                        <p>Whole Summary</p>
                    </a>
                </li>



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>