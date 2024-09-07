<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link text-center"
       href="#">
        <span class="brand-text font-weight-bold text-uppercase"
              style="letter-spacing: 0.15rem;">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-accordion="false"
                data-widget="treeview"
                role="menu">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
                       href="{{ route('admin.home') }}">
                        <i class="nav-icon fa-solid fa-tachometer-alt">
                        </i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="nav-icon fa-solid fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/orders') || request()->is('admin/orders/*') ? 'active' : '' }}"
                       href="{{ route('admin.orders.index') }}">
                        <i class="nav-icon fa-solid fa-shopping-basket">
                        </i>
                        <p>
                            Orders
                        </p>
                    </a>
                </li>

                <li
                    class="nav-item has-treeview {{ request()->is('admin/product-categories*') ? 'menu-open' : '' }} {{ request()->is('admin/product-brands*') ? 'menu-open' : '' }} {{ request()->is('admin/products*') ? 'menu-open' : '' }}">
                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/product-categories*') ? 'active' : '' }} {{ request()->is('admin/product-brands*') ? 'active' : '' }} {{ request()->is('admin/product*') ? 'active' : '' }}"
                       href="#">
                        <i class="nav-icon fa-solid fa-cubes">
                        </i>
                        <p>
                            Product
                            <i class="right fa fa-angle-left nav-icon"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/product-categories') || request()->is('admin/product-categories/*') ? 'active' : '' }}"
                               href="{{ route('admin.product-categories.index') }}">
                                <i class="nav-icon fa-solid fa-folder">
                                </i>
                                <p>
                                    Categories
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/product-brands') || request()->is('admin/product-brands/*') ? 'active' : '' }}"
                               href="{{ route('admin.product-brands.index') }}">
                                <i class="nav-icon fa-solid fa-folder">
                                </i>
                                <p>
                                    Brands
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/products') || request()->is('admin/products/*') ? 'active' : '' }}"
                               href="{{ route('admin.products.index') }}">
                                <i class="nav-icon fa-solid fa-folder">
                                </i>
                                <p>
                                    Products
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview {{ request()->is('admin/banks*') ? 'menu-open' : '' }}">
                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/banks*') ? 'active' : '' }}"
                       href="#">
                        <i class="nav-icon fa-solid fa-money-check-alt">
                        </i>
                        <p>
                            Payment
                            <i class="right fa fa-angle-left nav-icon"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/banks') || request()->is('admin/banks/*') ? 'active' : '' }}"
                               href="{{ route('admin.banks.index') }}">
                                <i class="nav-icon fa-solid fa-folder">
                                </i>
                                <p>
                                    Banks
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link "
                               href="#">
                                <i class="nav-icon fa-solid fa-folder">
                                </i>
                                <p>
                                    E-Wallets
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/banners') || request()->is('admin/banners/*') ? 'active' : '' }}"
                       href="{{ route('admin.banners.index') }}">
                        <i class="nav-icon fa-solid fa-image">
                        </i>
                        <p>
                            Banners
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="#">
                        <i class="nav-icon fa-solid fa-newspaper"></i>
                        <p>
                            Blog
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link"
                               href="">
                                <i class="fa-solid fa-folder nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="">
                                <i class="fa-solid fa-folder nav-icon"></i>
                                <p>Tags</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="">
                                <i class="fa-solid fa-folder nav-icon"></i>
                                <p>Posts</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
