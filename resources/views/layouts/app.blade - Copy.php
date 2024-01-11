<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Favicon-->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/Ionicons/css/ionicons.min.css') }}">

    @yield('style')

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('themes/backend/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('themes/backend/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/backend/css/custom.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>AP</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Admin</b>Panel</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <h4 class="pull-left" style="color: white; margin-top: 15px; padding-left: 20px">{{ config('app.name') }}</h4>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('img/avatar.png') }}" class="user-image" alt="Avatar">
                            <span class="hidden-xs">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>

                <?php
                $subMenu = ['unit', 'unit.add', 'unit.edit'];
                ?>

                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-info"></i> <span>Administrator</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                        <li class="{{ Route::currentRouteName() == 'unit' ? 'active' : '' }}">
                            <a href="{{ route('unit') }}"><i class="fa fa-circle-o"></i> Unit</a>
                        </li>
                    </ul>
                </li>

                <?php
                $subMenu = ['bank', 'bank.add', 'bank.edit', 'branch', 'branch.add', 'branch.edit',
                    'bank_account', 'bank_account.add', 'bank_account.edit'];
                ?>

                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-info"></i> <span>Bank & Account</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                        <li class="{{ Route::currentRouteName() == 'bank' ? 'active' : '' }}">
                            <a href="{{ route('bank') }}"><i class="fa fa-circle-o"></i> Bank</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'branch' ? 'active' : '' }}">
                            <a href="{{ route('branch') }}"><i class="fa fa-circle-o"></i> Branch</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'bank_account' ? 'active' : '' }}">
                            <a href="{{ route('bank_account') }}"><i class="fa fa-circle-o"></i> Account</a>
                        </li>
                    </ul>
                </li>

                <?php
                $subMenu = ['supplier', 'supplier.add', 'supplier.edit', 'purchase_product',
                    'purchase_product.add', 'purchase_product.edit', 'purchase_order.create',
                    'purchase_receipt.all', 'purchase_receipt.details', 'purchase_inventory.all',
                    'supplier_payment.all', 'purchase_receipt.payment_details', 'purchase_product.utilize.all',
                    'purchase_product.utilize.add', 'purchase_inventory.details'];
                ?>

                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-info"></i> <span>Purchase</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                        <li class="{{ Route::currentRouteName() == 'supplier' ? 'active' : '' }}">
                            <a href="{{ route('supplier') }}"><i class="fa fa-circle-o"></i> Supplier</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'purchase_product' ? 'active' : '' }}">
                            <a href="{{ route('purchase_product') }}"><i class="fa fa-circle-o"></i> Product</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'purchase_order.create' ? 'active' : '' }}">
                            <a href="{{ route('purchase_order.create') }}"><i class="fa fa-circle-o"></i> Purchase Order</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'purchase_receipt.all' ? 'active' : '' }}">
                            <a href="{{ route('purchase_receipt.all') }}"><i class="fa fa-circle-o"></i> Receipt</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'supplier_payment.all' ? 'active' : '' }}">
                            <a href="{{ route('supplier_payment.all') }}"><i class="fa fa-circle-o"></i> Supplier Payment</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'purchase_inventory.all' ? 'active' : '' }}">
                            <a href="{{ route('purchase_inventory.all') }}"><i class="fa fa-circle-o"></i> Inventory</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'purchase_product.utilize.all' ? 'active' : '' }}">
                            <a href="{{ route('purchase_product.utilize.all') }}"><i class="fa fa-circle-o"></i> Utilize</a>
                        </li>
                    </ul>
                </li>

                <?php
                $subMenu = ['client', 'client.add', 'client.edit', 'sale_product', 'sale_product.add',
                    'sale_product.edit', 'sales_order.create', 'sale_receipt.all', 'sale_receipt.details',
                    'sale_receipt.payment_details', 'client_payment.all', 'sale_product.stock.all',
                    'sale_product.stock.add', 'sale_inventory.all', 'sale_inventory.details'];
                ?>

                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-info"></i> <span>Sale</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                        <li class="{{ Route::currentRouteName() == 'client' ? 'active' : '' }}">
                            <a href="{{ route('client') }}"><i class="fa fa-circle-o"></i> Client</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'sale_product' ? 'active' : '' }}">
                            <a href="{{ route('sale_product') }}"><i class="fa fa-circle-o"></i> Product</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'sales_order.create' ? 'active' : '' }}">
                            <a href="{{ route('sales_order.create') }}"><i class="fa fa-circle-o"></i> Sales Order</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'sale_receipt.all' ? 'active' : '' }}">
                            <a href="{{ route('sale_receipt.all') }}"><i class="fa fa-circle-o"></i> Receipt</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'client_payment.all' ? 'active' : '' }}">
                            <a href="{{ route('client_payment.all') }}"><i class="fa fa-circle-o"></i> Client Payment</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'sale_inventory.all' ? 'active' : '' }}">
                            <a href="{{ route('sale_inventory.all') }}"><i class="fa fa-circle-o"></i> Inventory</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'sale_product.stock.all' ? 'active' : '' }}">
                            <a href="{{ route('sale_product.stock.all') }}"><i class="fa fa-circle-o"></i> Add To Stock</a>
                        </li>
                    </ul>
                </li>

                <?php
                $subMenu = ['account_head.type', 'account_head.type.add', 'account_head.type.edit',
                    'account_head.sub_type', 'account_head.sub_type.add', 'account_head.sub_type.edit',
                    'transaction.all', 'transaction.add', 'transaction.details', 'balance_transfer.add', 'balance_transfer.edit'];
                ?>

                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-info"></i> <span>Accounts</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                        <li class="{{ Route::currentRouteName() == 'account_head.type' ? 'active' : '' }}">
                            <a href="{{ route('account_head.type') }}"><i class="fa fa-circle-o"></i> Account Head Type</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'account_head.sub_type' ? 'active' : '' }}">
                            <a href="{{ route('account_head.sub_type') }}"><i class="fa fa-circle-o"></i> Account Head Sub Type</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'transaction.all' ? 'active' : '' }}">
                            <a href="{{ route('transaction.all') }}"><i class="fa fa-circle-o"></i> Transaction</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'balance_transfer.add' ? 'active' : '' }}">
                            <a href="{{ route('balance_transfer.add') }}"><i class="fa fa-circle-o"></i> Balance Transfer</a>
                        </li>
                    </ul>
                </li>

                <?php
                $subMenu = ['report.purchase_stock', 'report.cashbook', 'report.monthly_expenditure',
                    'report.bank_statement'];
                ?>

                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-info"></i> <span>Report</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                        <li class="{{ Route::currentRouteName() == 'report.purchase_stock' ? 'active' : '' }}">
                            <a href="{{ route('report.purchase_stock') }}"><i class="fa fa-circle-o"></i> Purchase Stock</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'report.cashbook' ? 'active' : '' }}">
                            <a href="{{ route('report.cashbook') }}"><i class="fa fa-circle-o"></i> Cashbook</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'report.monthly_expenditure' ? 'active' : '' }}">
                            <a href="{{ route('report.monthly_expenditure') }}"><i class="fa fa-circle-o"></i> Monthly Expenditure</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'report.bank_statement' ? 'active' : '' }}">
                            <a href="{{ route('report.bank_statement') }}"><i class="fa fa-circle-o"></i> Bank Statement</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('title')
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Design & Developed By <a target="_blank" href="http://2aitbd.com">2A IT</a></b>
        </div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ route('dashboard') }}">{{ config('app.name') }}</a>.</strong> All rights
        reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('themes/backend/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('themes/backend/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>

@yield('script')
<!-- AdminLTE App -->
<script src="{{ asset('themes/backend/js/adminlte.min.js') }}"></script>
</body>
</html>
