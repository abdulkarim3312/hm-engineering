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
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">

    <style>
        body {
            font-size: 16px;
        }
        .treeview-menu>li>a {
            padding: 5px 5px 5px 15px;
            display: block;
            font-size: 16px;
        }
        .img-overlay {
            position: absolute;
            left: 0;
            top: 80px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            text-align: center;
            z-index: 9;
            opacity: 0.2;
        }

        .img-overlay img {
            width: 400px;
            height: auto;
        }
        button, html input[type=button], input[type=reset], input[type=submit] {
            background: #367FA9;
            color: #fff;
        }
        i.fa.fa-bell-o {
            font-size: 20px;
        }
        .main-header .navbar .nav>li>a>.label {
            font-size: 15px!important;
        }
        .sidebar-menu li>a>.pull-right-container {
        right: 0px;
        }
        .bg-green-custom{
           background: #b9e79b !important
        }
        .bg-white-custom {
            background: #fff;
        }
    </style>

@yield('style')

<!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('themes/backend/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <!--folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('themes/backend/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/backend/css/custom.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
</head>

<body class="hold-transition skin-purple fixed sidebar-mini">
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
                    <!-- Notifications: style can be found in dropdown.less -->


                    <!-- User Account: style can be found in dropdown.less -->

                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('img/avatar.png') }}" class="user-image" alt="Avatar">
                            <span class="hidden-xs">{{ auth()->user()->name ?? '' }}</span>
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
                @if(auth()->user()->role == 2)
                    <?php
                    $subMenu = ['employees.attendance', 'employee_password_change'];
                    ?>

                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-users text-info"></i> <span>Attendance</span>
                            <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">

                            <li class="{{ Route::currentRouteName() == 'employees.attendance' ? 'active' : '' }}">
                                <a href="{{ route('employees.attendance') }}"><i class="fa fa-circle-o"></i>Attendance</a>
                            </li>

                            <li class="{{ Route::currentRouteName() == 'employee_password_change' ? 'active' : '' }}">
                                <a href="{{ route('employee_password_change') }}"><i class="fa fa-circle-o"></i>Change Password</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @can('dashboard')
                <li class="{{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                </li>
                @endcan
                <?php
                $subMenu = ['unit', 'unit.add', 'unit.edit','warehouse.all','warehouse.add','warehouse.edit','country','country.all','country.add','country.edit'];
                ?>
                @can('administrator')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-user text-info"></i> <span>Administrator</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                            @can('warehouses')
                                <li class="{{ Route::currentRouteName() == 'warehouse.all' ? 'active' : '' }}">
                                    <a href="{{ route('warehouse.all') }}"><i class="fa fa-circle-o"></i> Warehouse</a>
                                </li>
                            @endcan
                            @can('unit')
                                    <li class="{{ Route::currentRouteName() == 'unit' ? 'active' : '' }}">
                                        <a href="{{ route('unit') }}"><i class="fa fa-circle-o"></i> Unit</a>
                                    </li>
                            @endcan
                        </ul>
                    </li>

                @endcan

                <?php
                $subMenu = ['estimate_product','estimate_product.add','estimate_product.edit',
                    'estimate_project','estimate_project.add','estimate_project.edit','costing',
                    'costing.add','costing.edit','costing.details','costing_report.details',
                    'costing_type.add','costing_type.edit','costing_type','costing_report',
                    'costing_segment','costing_segment.add','costing_segment.edit','estimate_product_type',
                    'estimate_product_type.add','estimate_product_type.edit','segment_configure',
                    'segment_configure.add','segment_configure.details','assign_segment.all',
                    'assign_segment.add','assign_segment.details','cost_calculation',
                    'pile_configure','pile_configure.add','pile_configure.details','short_column_type', 'short_column_type.add',
                    'common_configure','common_configure.add','common_configure.details', 'short_column_type.edit',
                    'beam_configure','beam_configure.add','beam_configure.details', 'short_column_configure.add', 'short_column_configure',
                    'column_configure','column_configure.add','column_configure.details', 'short_column_configure.details',
                    'bricks_configure','bricks_configure.add','bricks_configure.details', 'water_tank_configure', 'water_tank_configure.add',
                    'plaster_configure','plaster_configure.add','plaster_configure.details', 'water_tank_configure.details',
                    'earth_work_configure','earth_work_configure.add','earth_work_configure.details',
                    'grill_glass_tiles_configure','grill_glass_tiles_configure.add','grill_glass_tiles_configure.details',
                    'paint_configure','paint_configure.add','paint_configure.details',
                    'estimate_report','estimate_floor','estimate_floor.add','estimate_floor.edit',
                    'column_type','column_type.add','column_type.edit', 'grade_beam_type', 'grade_beam_type.add', 'grade_beam_type.edit',
                    'estimate_floor_unit','estimate_floor_unit.add','estimate_floor_unit.edit',
                    'unit_section','unit_section.add','unit_section.edit', 'grade_beam_type_configure.add', 'grade_beam_configure.details', 'mat_configure.add', 'mat_configure.details', 'pile_cap_configure.add', 'pile_cap_configure.details',
                    'unit_section','unit_section.add','unit_section.edit','footing_configure.add', 'footing_configure.details',
                    'beam_type','beam_type.add','beam_type.edit','grade_of_concrete_type','grade_of_concrete_type.add',
                    'grade_of_concrete_type.edit','extra_costing','extra_costing.add','extra_costing.details','tiles_configure.details','tiles_configure.add',
                    'batch','batch.add','batch.edit','grade_of_concrete','grade_of_concrete.add','grade_of_concrete.edit',
                    'costing_report','estimation_costing_summary','mobilization_work','mobilization_work.add','mobilization_work.details','mobilization_work_product', 'returning_wall_configure.add', 'returning_wall_configure.details',
                    'mobilization_work_product.add','mobilization_work_product.edit','mobilization_work.edit','report.employee_attendance_in_out','footing_configure','grade_beam_type_configure', 'estimate_floor_configure', 'extra_cost_product', 'sand_filling_configure', 'bricks_soling_configure', 'pile_cap_configure', 'mat_configure', 'returning_wall_configure', 'glass_configure','glass_configure.add','glass_configure.details', 'tiles_configure', 'bricks_soling_configure.add', 'bricks_soling_configure.details', 'sand_filling_configure.add', 'sand_filling_configure.details', 'electric_product', 'electric_product.add', 'electric_product.edit', 'electric_product.datatable', 'electric_costing', 'electric_costing.add', 'sanitary_product','sanitary_product.add','sanitary_product.edit', 'sanitary_costing','sanitary_costing.add','sanitary_costing.details','facing_bricks_work','facing_bricks_work.add','facing_bricks_work.details'];
                ?>

                @can('estimation_and_costing')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-calculator text-info"></i> <span>Estimation & Costing</span>
                            <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                            @can('estimate_project')
                                <li class="{{ Route::currentRouteName() == 'estimate_project' ? 'active' : '' }}">
                                    <a href="{{ route('estimate_project') }}"><i class="fa fa-circle-o"></i>Estimate Project</a>
                                </li>
                            @endcan
                            @can('estimate_floor')
                            <li class="{{ Route::currentRouteName() == 'estimate_floor' ? 'active' : '' }}">
                                <a href="{{ route('estimate_floor') }}"><i class="fa fa-circle-o"></i>Estimate Floor</a>
                            </li>
                            @endcan

                            @can('floor_unit')
                            <li class="{{ Route::currentRouteName() == 'estimate_floor_unit' ? 'active' : '' }}">
                                <a href="{{ route('estimate_floor_unit') }}"><i class="fa fa-circle-o"></i>Floor Unit</a>
                            </li>
                            @endcan
                            @can('unit_section')
                            <li class="{{ Route::currentRouteName() == 'unit_section' ? 'active' : '' }}">
                                <a href="{{ route('unit_section') }}"><i class="fa fa-circle-o"></i>Unit Section</a>
                            </li>
                            @endcan
                            @can('unit_section')
                            <li class="{{ Route::currentRouteName() == 'electric_product' ? 'active' : '' }}">
                                <a href="{{ route('electric_product') }}"><i class="fa fa-circle-o"></i>Electric Product</a>
                            </li>
                            @endcan
                            @can('unit_section')
                            <li class="{{ Route::currentRouteName() == 'electric_costing' ? 'active' : '' }}">
                                <a href="{{ route('electric_costing') }}"><i class="fa fa-circle-o"></i>Electric Product Costing</a>
                            </li>
                            @endcan
                            @can('unit_section')
                            <li class="{{ Route::currentRouteName() == 'sanitary_product' ? 'active' : '' }}">
                                <a href="{{ route('sanitary_product') }}"><i class="fa fa-circle-o"></i>Sanitary Product</a>
                            </li>
                            @endcan
                            @can('unit_section')
                            <li class="{{ Route::currentRouteName() == 'sanitary_costing' ? 'active' : '' }}">
                                <a href="{{ route('sanitary_costing') }}"><i class="fa fa-circle-o"></i>Sanitary Product Costing</a>
                            </li>
                            @endcan
                            @can('beam_type')
                            <li class="{{ Route::currentRouteName() == 'beam_type' ? 'active' : '' }}">
                                <a href="{{ route('beam_type') }}"><i class="fa fa-circle-o"></i> Beam Type</a>
                            </li>
                            @endcan
                            @can('column_type')
                            <li class="{{ Route::currentRouteName() == 'column_type' ? 'active' : '' }}">
                                <a href="{{ route('column_type') }}"><i class="fa fa-circle-o"></i> Column Type</a>
                            </li>
                            @endcan
                            @can('column_type')
                            <li class="{{ Route::currentRouteName() == 'short_column_type' ? 'active' : '' }}">
                                <a href="{{ route('short_column_type') }}"><i class="fa fa-circle-o"></i>Short Column Type</a>
                            </li>
                            @endcan
                            @can('batch')
                             <li class="{{ Route::currentRouteName() == 'batch' ? 'active' : '' }}">
                                 <a href="{{ route('batch') }}"><i class="fa fa-circle-o"></i>Footing Type</a>
                             </li>
                             @endcan

                            <li class="{{ Route::currentRouteName() == 'footing_configure' ? 'active' : '' }}">
                                <a href="{{ route('footing_configure') }}"><i class="fa fa-circle-o"></i>Footing Type Configure</a>
                            </li>

                            <li class="{{ Route::currentRouteName() == 'water_tank_configure' ? 'active' : '' }}">
                                <a href="{{ route('water_tank_configure') }}"><i class="fa fa-circle-o"></i>Water Tank Configure</a>
                            </li>

                            {{-- @can('grade_of_concrete_type')
                             <li class="{{ Route::currentRouteName() == 'grade_of_concrete_type' ? 'active' : '' }}">
                                <a href="{{ route('grade_of_concrete_type') }}"><i class="fa fa-circle-o"></i>Grade Concrete Type</a>
                            </li>
                            @endcan --}}
                              @can('grade_of_concrete_type')
                             <li class="{{ Route::currentRouteName() == 'grade_beam_type' ? 'active' : '' }}">
                                <a href="{{ route('grade_beam_type') }}"><i class="fa fa-circle-o"></i>Grade Beam Type</a>
                            </li>
                            @endcan
                            <li class="{{ Route::currentRouteName() == 'grade_beam_type_configure' ? 'active' : '' }}">
                                <a href="{{ route('grade_beam_type_configure') }}"><i class="fa fa-circle-o"></i>Grade Beam Type Configure</a>
                            </li>
                            @can('costing_segment')
                            <li class="{{ Route::currentRouteName() == 'costing_segment' ? 'active' : '' }}">
                                <a href="{{ route('costing_segment') }}"><i class="fa fa-circle-o"></i>Costing Segment</a>
                            </li>
                            @endcan
                            @can('estimate_product')
                                <li class="{{ Route::currentRouteName() == 'estimate_product' ? 'active' : '' }}">
                                    <a href="{{ route('estimate_product') }}"><i class="fa fa-circle-o"></i>Estimate Product</a>
                                </li>
                            @endcan
                            @can('pile_configure')
                            <li class="{{ Route::currentRouteName() == 'pile_configure' ? 'active' : '' }}">
                                <a href="{{ route('pile_configure') }}"><i class="fa fa-circle-o"></i>Pile Configure</a>
                            </li>
                            @endcan
                                @can('beam_configure')
                            <li class="{{ Route::currentRouteName() == 'beam_configure' ? 'active' : '' }}">
                                <a href="{{ route('beam_configure') }}"><i class="fa fa-circle-o"></i>Beam Configure</a>
                            </li>
                                @endcan
                            @can('column_configure')
                            <li class="{{ Route::currentRouteName() == 'column_configure' ? 'active' : '' }}">
                                <a href="{{ route('column_configure') }}"><i class="fa fa-circle-o"></i>Column Configure</a>
                            </li>
                             @endcan
                            @can('column_configure')
                            <li class="{{ Route::currentRouteName() == 'short_column_configure' ? 'active' : '' }}">
                                <a href="{{ route('short_column_configure') }}"><i class="fa fa-circle-o"></i>Short Column Configure</a>
                            </li>
                             @endcan
                             @can('slab_cap_wall_configure')
                            <li class="{{ Route::currentRouteName() == 'common_configure' ? 'active' : '' }}">
                                <a href="{{ route('common_configure') }}"><i class="fa fa-circle-o"></i>Slab Configure</a>
                            </li>
                                @endcan
                             @can('slab_cap_wall_configure')
                            <li class="{{ Route::currentRouteName() == 'pile_cap_configure' ? 'active' : '' }}">
                                <a href="{{ route('pile_cap_configure') }}"><i class="fa fa-circle-o"></i>Pile Cap Configure</a>
                            </li>
                            @endcan
                             @can('slab_cap_wall_configure')
                            <li class="{{ Route::currentRouteName() == 'mat_configure' ? 'active' : '' }}">
                                <a href="{{ route('mat_configure') }}"><i class="fa fa-circle-o"></i>Mat Configure</a>
                            </li>
                            @endcan
                             @can('slab_cap_wall_configure')
                            <li class="{{ Route::currentRouteName() == 'returning_wall_configure' ? 'active' : '' }}">
                                <a href="{{ route('returning_wall_configure') }}"><i class="fa fa-circle-o"></i>Returning Wall Configure</a>
                            </li>
                                @endcan
                                @can('bricks_configure')
                            <li class="{{ Route::currentRouteName() == 'bricks_configure' ? 'active' : '' }}">
                                <a href="{{ route('bricks_configure') }}"><i class="fa fa-circle-o"></i>Bricks Configure</a>
                            </li>
                                @endcan
                                @can('plaster_configure')
                            <li class="{{ Route::currentRouteName() == 'plaster_configure' ? 'active' : '' }}">
                                <a href="{{ route('plaster_configure') }}"><i class="fa fa-circle-o"></i>Plaster Configure</a>
                            </li>
                                @endcan
                                @can('grill_glass_tiles_configure')
                            <li class="{{ Route::currentRouteName() == 'grill_glass_tiles_configure' ? 'active' : '' }}">
                                <a href="{{ route('grill_glass_tiles_configure') }}"><i class="fa fa-circle-o"></i>Grill Configure</a>
                            </li>
                                @endcan
                                {{-- @can('grill_glass_tiles_configure') --}}
                            <li class="{{ Route::currentRouteName() == 'glass_configure' ? 'active' : '' }}">
                                <a href="{{ route('glass_configure') }}"><i class="fa fa-circle-o"></i>Glass Configure</a>
                            </li>
                                {{-- @endcan --}}
                                {{-- @can('grill_glass_tiles_configure') --}}
                            <li class="{{ Route::currentRouteName() == 'tiles_configure' ? 'active' : '' }}">
                                <a href="{{ route('tiles_configure') }}"><i class="fa fa-circle-o"></i>Tiles Configure</a>
                            </li>
                                {{-- @endcan --}}
                                @can('paint_configure')
                            <li class="{{ Route::currentRouteName() == 'paint_configure' ? 'active' : '' }}">
                                <a href="{{ route('paint_configure') }}"><i class="fa fa-circle-o"></i>Paint Configure</a>
                            </li>
                                @endcan
                                @can('paint_configure')
                            <li class="{{ Route::currentRouteName() == 'facing_bricks_work' ? 'active' : '' }}">
                                <a href="{{ route('facing_bricks_work') }}"><i class="fa fa-circle-o"></i>Facing Bricks Work</a>
                            </li>
                                @endcan
                                @can('earth_work_configure')
                            <li class="{{ Route::currentRouteName() == 'earth_work_configure' ? 'active' : '' }}">
                                <a href="{{ route('earth_work_configure') }}"><i class="fa fa-circle-o"></i>Earth Work Configure</a>
                            </li>
                                @endcan
                                @can('earth_work_configure')
                            <li class="{{ Route::currentRouteName() == 'sand_filling_configure' ? 'active' : '' }}">
                                <a href="{{ route('sand_filling_configure') }}"><i class="fa fa-circle-o"></i>Sand Filling Configure</a>
                            </li>
                                @endcan
                                @can('earth_work_configure')
                            <li class="{{ Route::currentRouteName() == 'bricks_soling_configure' ? 'active' : '' }}">
                                <a href="{{ route('bricks_soling_configure') }}"><i class="fa fa-circle-o"></i>Bricks Soling Configure</a>
                            </li>
                                @endcan
                                @can('extra_costing')
                             <li class="{{ Route::currentRouteName() == 'extra_costing' ? 'active' : '' }}">
                                 <a href="{{ route('extra_costing') }}"><i class="fa fa-circle-o"></i>Extra Costing</a>
                             </li>
                                @endcan
                                @can('mobilization_product')
                                 <li class="{{ Route::currentRouteName() == 'mobilization_work_product' ? 'active' : '' }}">
                                    <a href="{{ route('mobilization_work_product') }}"><i class="fa fa-circle-o"></i>Mobilization Product</a>
                                </li>
                                <li class="{{ Route::currentRouteName() == 'extra_cost_product' ? 'active' : '' }}">
                                    <a href="{{ route('extra_cost_product') }}"><i class="fa fa-circle-o"></i>Extra Cost Product</a>
                                </li>
                                @endcan
                                @can('mobilization_work')
                                <li class="{{ Route::currentRouteName() == 'mobilization_work' ? 'active' : '' }}">
                                    <a href="{{ route('mobilization_work') }}"><i class="fa fa-circle-o"></i>Mobilization Work</a>
                                </li>
                                @endcan
                                {{-- @can('grade_of_concrete')
                             <li class="{{ Route::currentRouteName() == 'grade_of_concrete' ? 'active' : '' }}">
                                 <a href="{{ route('grade_of_concrete') }}"><i class="fa fa-circle-o"></i>Grade Of Concrete</a>
                             </li>
                                @endcan --}}
                                @can('estimate_report')
                            <li class="{{ Route::currentRouteName() == 'estimate_report' ? 'active' : '' }}">
                                <a href="{{ route('estimate_report') }}"><i class="fa fa-circle-o"></i>Estimate Report</a>
                            </li>
                                @endcan
                                @can('costing_report')
                            <li class="{{ Route::currentRouteName() == 'costing_report' ? 'active' : '' }}">
                                <a href="{{ route('costing_report') }}"><i class="fa fa-circle-o"></i>Costing Report</a>
                            </li>
                                @endcan
                                @can('estimation_costing_summary')
                            <li class="{{ Route::currentRouteName() == 'estimation_costing_summary' ? 'active' : '' }}">
                                <a href="{{ route('estimation_costing_summary') }}"><i class="fa fa-circle-o"></i>Estimation Costing Summary</a>
                            </li>
                               @endcan
                        </ul>
                    </li>
                @endcan

                <?php
                $subMenu = ['department', 'department.add', 'department.edit', 'vendor.all','vendor.add','vendor.edit','vendor.payment','vendor.list'];
                ?>

                @can('hr_department')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-users text-info"></i> <span>Vendor</span>
                            <span class="pull-right-container">
                         <i class="fa fa-angle-left pull-right"></i>
                       </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                            @can('designation')
                                <li class="{{ Route::currentRouteName() == 'vendor.all' ? 'active' : '' }}">
                                    <a href="{{ route('vendor.all') }}"><i class="fa fa-circle-o"></i> Vendor</a>
                                </li>
                            @endcan
                            @can('department')
                                <li class="{{ Route::currentRouteName() == 'vendor.list' ? 'active' : '' }}">
                                    <a href="{{ route('vendor.list') }}"><i class="fa fa-circle-o"></i> Vendor List</a>
                                </li>
                            @endcan
                            @can('department')
                                <li class="{{ Route::currentRouteName() == 'vendor.payment' ? 'active' : '' }}">
                                    <a href="{{ route('vendor.payment') }}"><i class="fa fa-circle-o"></i> Vendor Payment</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                <?php
                $subMenu = ['department', 'department.add', 'department.edit', 'designation',
                    'designation.add', 'designation.edit','employee.all', 'employee.add', 'employee.edit', 'employee.details','employee.attendance','report.employee_list', 'job_confirm_letter', 'job_letter', 'job_promotion_letter'];
                ?>

                @can('hr_department')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-users text-info"></i> <span>HR Department</span>
                            <span class="pull-right-container">
                         <i class="fa fa-angle-left pull-right"></i>
                       </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                            @can('department')
                                <li class="{{ Route::currentRouteName() == 'department' ? 'active' : '' }}">
                                    <a href="{{ route('department') }}"><i class="fa fa-circle-o"></i> Department</a>
                                </li>
                            @endcan

                            @can('designation')
                                <li class="{{ Route::currentRouteName() == 'designation' ? 'active' : '' }}">
                                    <a href="{{ route('designation') }}"><i class="fa fa-circle-o"></i> Designation</a>
                                </li>
                            @endcan
                            @can('employee')
                                <li class="{{ Route::currentRouteName() == 'employee.all' ? 'active' : '' }}">
                                    <a href="{{ route('employee.all') }}"><i class="fa fa-circle-o"></i> Employee</a>
                                </li>
                            @endcan
                            @can('employee_list')
                                    <li class="{{ Route::currentRouteName() == 'report.employee_list' ? 'active' : '' }}">
                                        <a href="{{ route('report.employee_list') }}"><i class="fa fa-circle-o"></i> Employee List</a>
                                    </li>
                            @endcan
                            @can('employee_attendance')
                                 <li class="{{ Route::currentRouteName() == 'employee.attendance' ? 'active' : '' }}">
                                     <a href="{{ route('employee.attendance') }}"><i class="fa fa-circle-o"></i> Employee Attendance</a>
                                 </li>
                            @endcan
                            <li class="{{ Route::currentRouteName() == 'job_letter' ? 'active' : '' }}">
                                <a href="{{ route('job_letter') }}"><i class="fa fa-circle-o"></i> Employee Job Letter</a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'job_confirm_letter' ? 'active' : '' }}">
                                <a href="{{ route('job_confirm_letter') }}"><i class="fa fa-circle-o"></i> Employee Confirmation Letter</a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'job_promotion_letter' ? 'active' : '' }}">
                                <a href="{{ route('job_promotion_letter') }}"><i class="fa fa-circle-o"></i> Employee Promotion Letter</a>
                            </li>
                        </ul>
                    </li>
                @endcan

                <?php
                $subMenu = ['labour.all', 'labour.add', 'labour.edit', 'labour.details',
                    'labour.attendance','labour_list','labour_designation.all','labour_designation.add',
                    'labour_designation.edit','labour_employee_attendance.report','labour.food_cost',
                    'labour.food_cost.add','labour.food_cost.edit','food_cost.details','labour.bill',
                    'labour.bill.add','labour.bill.details','contractor.all','contractor.list','contractor.add','contractor.edit','contractor.payment'];
                ?>

                @can('labour_department')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-users text-info"></i> <span>Labour Department</span>
                            <span class="pull-right-container">
                         <i class="fa fa-angle-left pull-right"></i>
                       </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                            @can('labour_designation')
                                <li class="{{ Route::currentRouteName() == 'contractor.all' ? 'active' : '' }}">
                                    <a href="{{ route('contractor.all') }}"><i class="fa fa-circle-o"></i>Contractor</a>
                                </li>
                            @endcan
                            @can('labour_designation')
                                <li class="{{ Route::currentRouteName() == 'contractor.list' ? 'active' : '' }}">
                                    <a href="{{ route('contractor.list') }}"><i class="fa fa-circle-o"></i>Contractor List</a>
                                </li>
                            @endcan
                            @can('labour_designation')
                                <li class="{{ Route::currentRouteName() == 'contractor.payment' ? 'active' : '' }}">
                                    <a href="{{ route('contractor.payment') }}"><i class="fa fa-circle-o"></i>Contractor Payment</a>
                                </li>
                            @endcan
                            @can('labour_designation')
                                <li class="{{ Route::currentRouteName() == 'labour_designation.all' ? 'active' : '' }}">
                                    <a href="{{ route('labour_designation.all') }}"><i class="fa fa-circle-o"></i> Labour Designation</a>
                                </li>
                            @endcan
                            @can('labour')
                                 <li class="{{ Route::currentRouteName() == 'labour.all' ? 'active' : '' }}">
                                     <a href="{{ route('labour.all') }}"><i class="fa fa-circle-o"></i> Labour</a>
                                 </li>
                            @endcan
                            @can('labour_list')
                                <li class="{{ Route::currentRouteName() == 'labour_list' ? 'active' : '' }}">
                                    <a href="{{ route('labour_list') }}"><i class="fa fa-circle-o"></i> Labour List</a>
                                </li>
                            @endcan
                            @can('labour_attendance')
                                <li class="{{ Route::currentRouteName() == 'labour.attendance' ? 'active' : '' }}">
                                     <a href="{{ route('labour.attendance') }}"><i class="fa fa-circle-o"></i> Labour Attendance</a>
                                </li>
                            @endcan
                            @can('labour_attendance_report')
                                 <li class="{{ Route::currentRouteName() == 'labour_employee_attendance.report' ? 'active' : '' }}">
                                      <a href="{{ route('labour_employee_attendance.report') }}"><i class="fa fa-circle-o"></i> Labour Attendance Report</a>
                                 </li>
                            @endcan
                                @can('labour_advance_amount')
                                    <li class="{{ Route::currentRouteName() == 'labour.food_cost' ? 'active' : '' }}">
                                        <a href="{{ route('labour.food_cost') }}"><i class="fa fa-circle-o"></i>Labour Advance Amount</a>
                                    </li>
                                @endcan

                            @can('labour_bill_process')
                                    <li class="{{ Route::currentRouteName() == 'labour.bill' ? 'active' : '' }}">
                                        <a href="{{ route('labour.bill') }}"><i class="fa fa-circle-o"></i>Labour Bill Process</a>
                                    </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                <?php
                $subMenu = ['payroll.salary_update.index', 'payroll.salary_process.index',
                    'payroll.leave.index','payroll.holiday.index','payroll.holiday_add','payroll.holiday_edit',
                    'payroll.leave.all'];
                ?>

               @can('payroll_department')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-credit-card text-info"></i> <span>Payroll Department</span>
                            <span class="pull-right-container">
                         <i class="fa fa-angle-left pull-right"></i>
                       </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                            @can('salary_update')
                                <li class="{{ Route::currentRouteName() == 'payroll.salary_update.index' ? 'active' : '' }}">
                                    <a href="{{ route('payroll.salary_update.index') }}"><i class="fa fa-circle-o"></i> Salary Update</a>
                                </li>
                            @endcan

                            @can('salary_process')
                                 <li class="{{ Route::currentRouteName() == 'payroll.salary_process.index' ? 'active' : '' }}">
                                      <a href="{{ route('payroll.salary_process.index') }}"><i class="fa fa-circle-o"></i> Salary Process</a>
                                 </li>
                            @endcan

                            <!--@can('leave')-->
                            <!--        <li class="{{ Route::currentRouteName() == 'payroll.leave.index' ? 'active' : '' }}">-->
                            <!--            <a href="{{ route('payroll.leave.index') }}"><i class="fa fa-circle-o"></i> Leave</a>-->
                            <!--        </li>-->
                            <!--@endcan-->
                            @can('leave')
                                <li class="{{ Route::currentRouteName() == 'payroll.leave.all' ? 'active' : '' }}">
                                    <a href="{{ route('payroll.leave.all') }}"><i class="fa fa-circle-o"></i>Leave</a>
                                </li>
                            @endcan
                            @can('holiday')
                                    <li class="{{ Route::currentRouteName() == 'payroll.holiday.index' ? 'active' : '' }}">
                                        <a href="{{ route('payroll.holiday.index') }}"><i class="fa fa-circle-o"></i> Holiday</a>
                                    </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

               <?php
               $subMenu = ['project', 'project.add', 'project.edit',
                   'segment','segment.add','segment.edit'];
               ?>

              @can('project_control')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-product-hunt text-info"></i> <span>Project Control</span>
                            <span class="pull-right-container">
                         <i class="fa fa-angle-left pull-right"></i>
                       </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">

                            @can('all_project')
                                <li class="{{ Route::currentRouteName() == 'project' ? 'active' : '' }}">
                                    <a href="{{ route('project') }}"><i class="fa fa-circle-o"></i>All Project</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
              @endcan

                <?php
                $subMenu = ['requisition', 'requisition.add', 'requisition.edit',
                    'requisition.details','requisition.approved'];
                ?>
                @can('requisition_area')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-plug text-info"></i> <span>Requisition Area</span>
                            <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                            @can('create_requisition')
                            <li class="{{ Route::currentRouteName() == 'requisition.add' ? 'active' : '' }}">
                                <a href="{{ route('requisition.add') }}"><i class="fa fa-circle-o"></i>Create Requisition</a>
                            </li>
                            @endcan
                            @can('requisition_lists')
                            <li class="{{ Route::currentRouteName() == 'requisition' ? 'active' : '' }}">
                                <a href="{{ route('requisition') }}"><i class="fa fa-circle-o"></i>Requisition Lists</a>
                            </li>
                                @endcan
                        </ul>
                    </li>
                @endcan
            <?php
                $subMenu = ['supplier', 'supplier.add', 'supplier.edit', 'purchase_product',
                    'purchase_product.add', 'purchase_product.edit', 'purchase_order.create',
                    'purchase_receipt.all', 'purchase_receipt.details', 'purchase_inventory.all','purchase_order_edit',
                    'supplier_payment.all', 'purchase_receipt.payment_details', 'purchase_product.utilize.all',
                    'purchase_product.utilize.add', 'purchase_inventory.details','supplier_payment_details'];
                ?>

                @can('procurement_and_purchase')
                        <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                            <a href="#">
                                <i class="fa fa-shopping-cart text-info"></i> <span>Procurement & Purchase </span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" style="display: block;">
                                @can('supplier')
                                    <li class="{{ Route::currentRouteName() == 'supplier' ? 'active' : '' }}">
                                        <a href="{{ route('supplier') }}"><i class="fa fa-circle-o"></i> Supplier</a>
                                    </li>
                                @endcan

                                @can('product')
                                    <li class="{{ Route::currentRouteName() == 'purchase_product' ? 'active' : '' }}">
                                        <a href="{{ route('purchase_product') }}"><i class="fa fa-circle-o"></i> Product</a>
                                    </li>
                                @endcan

                                <li class="treeview" style="height: auto;">
                                    <a href="#"><i class="fa fa-circle-o"></i>Real Estate Purchase
                                        <span class="pull-right-container">
                                              <i class="fa fa-angle-left pull-right"></i>
                                         </span>
                                    </a>
                                    <ul class="treeview-menu" style="display: none;">
                                        @can('realstate_purchase')
                                            <li class="{{ Route::currentRouteName() == 'purchase_order.create' ? 'active' : '' }}">
                                                <a href="{{ route('purchase_order.create') }}"><i class="fa fa-circle-o"></i> Purchase Order</a>
                                            </li>
                                        @endcan

                                        @can('realstate_purchase')
                                            <li class="{{ Route::currentRouteName() == 'purchase_receipt.all' ? 'active' : '' }}">
                                                <a href="{{ route('purchase_receipt.all') }}"><i class="fa fa-circle-o"></i> Receipt</a>
                                            </li>
                                        @endcan

                                        @can('realstate_purchase')
                                            <li class="{{ Route::currentRouteName() == 'supplier_payment.all' ? 'active' : '' }}">
                                                <a href="{{ route('supplier_payment.all') }}"><i class="fa fa-circle-o"></i> Supplier Payment</a>
                                            </li>
                                        @endcan

                                        @can('realstate_purchase')
                                            <li class="{{ Route::currentRouteName() == 'purchase_inventory.all' ? 'active' : '' }}">
                                                <a href="{{ route('purchase_inventory.all') }}"><i class="fa fa-circle-o"></i>Stock Inventory</a>
                                            </li>
                                        @endcan

                                        @can('realstate_purchase')
                                            <li class="{{ Route::currentRouteName() == 'purchase_product.utilize.all' ? 'active' : '' }}">
                                                <a href="{{ route('purchase_product.utilize.all') }}"><i class="fa fa-circle-o"></i> Utilize</a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>

                                <li class="treeview" style="height: auto;">
                                    <a href="#"><i class="fa fa-circle-o"></i>Normal Purchase
                                        <span class="pull-right-container">
                                              <i class="fa fa-angle-left pull-right"></i>
                                         </span>
                                    </a>
                                    <ul class="treeview-menu" style="display: none;">
                                        @can('normal_purchase')
                                            <li class="{{ Route::currentRouteName() == 'trading_purchase_order.create' ? 'active' : '' }}">
                                                <a href="{{ route('trading_purchase_order.create') }}"><i class="fa fa-circle-o"></i>Purchase Order</a>
                                            </li>
                                        @endcan

                                        @can('normal_purchase')
                                            <li class="{{ Route::currentRouteName() == 'trading_purchase_receipt.all' ? 'active' : '' }}">
                                                <a href="{{ route('trading_purchase_receipt.all') }}"><i class="fa fa-circle-o"></i> Receipt</a>
                                            </li>
                                        @endcan

                                        @can('normal_purchase')
                                            <li class="{{ Route::currentRouteName() == 'trading_supplier_payment.all' ? 'active' : '' }}">
                                                <a href="{{ route('trading_supplier_payment.all') }}"><i class="fa fa-circle-o"></i> Supplier Payment</a>
                                            </li>
                                        @endcan

                                        @can('normal_purchase')
                                            <li class="{{ Route::currentRouteName() == 'trading_purchase_inventory.all' ? 'active' : '' }}">
                                                <a href="{{ route('trading_purchase_inventory.all') }}"><i class="fa fa-circle-o"></i>Stock Inventory</a>
                                            </li>
                                        @endcan

{{--                                        @can('utilize')--}}
{{--                                            <li class="{{ Route::currentRouteName() == 'purchase_product.utilize.all' ? 'active' : '' }}">--}}
{{--                                                <a href="{{ route('purchase_product.utilize.all') }}"><i class="fa fa-circle-o"></i> Utilize</a>--}}
{{--                                            </li>--}}
{{--                                        @endcan--}}
                                    </ul>
                                </li>
                            </ul>
                        </li>


{{--                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">--}}
{{--                        <a href="#">--}}
{{--                            <i class="fa fa-shopping-cart text-info"></i> <span>Procurement & Purchase </span>--}}
{{--                            <span class="pull-right-container">--}}
{{--                          <i class="fa fa-angle-left pull-right"></i>--}}
{{--                        </span>--}}
{{--                        </a>--}}
{{--                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">--}}
{{--                            @can('supplier')--}}
{{--                                <li class="{{ Route::currentRouteName() == 'supplier' ? 'active' : '' }}">--}}
{{--                                    <a href="{{ route('supplier') }}"><i class="fa fa-circle-o"></i> Supplier</a>--}}
{{--                                </li>--}}
{{--                            @endcan--}}

{{--                            @can('product')--}}
{{--                                    <li class="{{ Route::currentRouteName() == 'purchase_product' ? 'active' : '' }}">--}}
{{--                                        <a href="{{ route('purchase_product') }}"><i class="fa fa-circle-o"></i> Product</a>--}}
{{--                                    </li>--}}
{{--                            @endcan--}}

{{--                            @can('purchase_order')--}}
{{--                                    <li class="{{ Route::currentRouteName() == 'purchase_order.create' ? 'active' : '' }}">--}}
{{--                                        <a href="{{ route('purchase_order.create') }}"><i class="fa fa-circle-o"></i> Purchase Order</a>--}}
{{--                                    </li>--}}
{{--                            @endcan--}}

{{--                            @can('receipt')--}}
{{--                                 <li class="{{ Route::currentRouteName() == 'purchase_receipt.all' ? 'active' : '' }}">--}}
{{--                                      <a href="{{ route('purchase_receipt.all') }}"><i class="fa fa-circle-o"></i> Receipt</a>--}}
{{--                                 </li>--}}
{{--                            @endcan--}}

{{--                            @can('supplier_payment')--}}
{{--                                    <li class="{{ Route::currentRouteName() == 'supplier_payment.all' ? 'active' : '' }}">--}}
{{--                                        <a href="{{ route('supplier_payment.all') }}"><i class="fa fa-circle-o"></i> Supplier Payment</a>--}}
{{--                                    </li>--}}
{{--                            @endcan--}}

{{--                            @can('stock_inventory')--}}
{{--                                    <li class="{{ Route::currentRouteName() == 'purchase_inventory.all' ? 'active' : '' }}">--}}
{{--                                        <a href="{{ route('purchase_inventory.all') }}"><i class="fa fa-circle-o"></i>Stock Inventory</a>--}}
{{--                                    </li>--}}
{{--                            @endcan--}}

{{--                            @can('utilize')--}}
{{--                                    <li class="{{ Route::currentRouteName() == 'purchase_product.utilize.all' ? 'active' : '' }}">--}}
{{--                                        <a href="{{ route('purchase_product.utilize.all') }}"><i class="fa fa-circle-o"></i> Utilize</a>--}}
{{--                                    </li>--}}
{{--                            @endcan--}}
{{--                        </ul>--}}
{{--                    </li>--}}
                @endcan
                <?php
                $subMenu = ['client', 'client.add', 'client.edit', 'sale_product', 'sale_product.add',
                    'sale_product.edit', 'sales_order.create', 'sale_receipt.all', 'sale_receipt.details','trading_sales_order',
                    'sale_receipt.payment_details', 'client_payment.all', 'sale_product.stock.all','client_payment_edit',
                    'sale_product.stock.add', 'sale_inventory.all', 'sale_inventory.details','client_payment_details','sales_order_edit',
                    'flat', 'flat.add','apartment.add', 'flat.edit', 'floor', 'floor.add','floor.edit','scrap_product.all','scrap_product.add',
                    'scrap_sale.create','scrap_sale_receipt.all','scrap_sale_receipt.details','scrap_sale_receipt.print',
                    'sale_inventory.floor_wise_view','project_wise_client'];
                ?>

                @can('sale_system')

                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-shopping-bag text-info"></i> <span>Sale System</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">

                            @can('floor')
                                <li class="{{ Route::currentRouteName() == 'floor' ? 'active' : '' }}">
                                    <a href="{{ route('floor') }}"><i class="fa fa-circle-o"></i> Floor</a>
                                </li>
                            @endcan

                            @can('apartment_shop')
                                <li class="{{ Route::currentRouteName() == 'flat' ? 'active' : '' }}">
                                    <a href="{{ route('flat') }}"><i class="fa fa-circle-o"></i> Apartment/Shop</a>
                                </li>
                            @endcan

                            @can('client_profile')
                                <li class="{{ Route::currentRouteName() == 'client' ? 'active' : '' }}">
                                    <a href="{{ route('client') }}"><i class="fa fa-circle-o"></i> Client Profile</a>
                                </li>
                            @endcan

                            @can('create_sales_order')
                                    <li class="{{ Route::currentRouteName() == 'sales_order.create' ? 'active' : '' }}">
                                        <a href="{{ route('sales_order.create') }}"><i class="fa fa-circle-o"></i>Create Sales Order</a>
                                    </li>
                            @endcan

                                <li class="{{ Route::currentRouteName() == 'trading_sales_order.create' ? 'active' : '' }}">
                                    <a href="{{ route('trading_sales_order.create') }}"><i class="fa fa-circle-o"></i>Trading Sales Order</a>
                                </li>

                                <li class="{{ Route::currentRouteName() == 'trading_sales_receipt' ? 'active' : '' }}">
                                    <a href="{{ route('trading_sales_receipt') }}"><i class="fa fa-circle-o"></i>Trading Sales Receipt</a>
                                </li>

                            @can('sales_system_receipt')
                                    <li class="{{ Route::currentRouteName() == 'sale_receipt.all' ? 'active' : '' }}">
                                        <a href="{{ route('sale_receipt.all') }}"><i class="fa fa-circle-o"></i> Receipt</a>
                                    </li>
                            @endcan

                            @can('client_payment_record')
                                    <li class="{{ Route::currentRouteName() == 'client_payment.all' ? 'active' : '' }}">
                                        <a href="{{ route('client_payment.all') }}"><i class="fa fa-circle-o"></i> Client Payment Record</a>
                                    </li>
                            @endcan

                            @can('sale_inventory')
                                    <li class="{{ Route::currentRouteName() == 'sale_inventory.all' ? 'active' : '' }}">
                                        <a href="{{ route('sale_inventory.all') }}"><i class="fa fa-circle-o"></i>Sale Inventory</a>
                                    </li>
                             @endcan

                             @can('sale_inventory_view')
                                    <li class="{{ Route::currentRouteName() == 'sale_inventory.floor_wise_view' ? 'active' : '' }}">
                                        <a href="{{ route('sale_inventory.floor_wise_view') }}"><i class="fa fa-circle-o"></i>Sale Inventory View</a>
                                    </li>
                             @endcan
                            @can('scrap_product')
                            <li class="{{ Route::currentRouteName() == 'scrap_product.all' ? 'active' : '' }}">
                                <a href="{{ route('scrap_product.all') }}"><i class="fa fa-circle-o"></i>Scrap Product</a>
                            </li>
                            @endcan
                            @can('scrap_sale')
                            <li class="{{ Route::currentRouteName() == 'scrap_sale.create' ? 'active' : '' }}">
                                <a href="{{ route('scrap_sale.create') }}"><i class="fa fa-circle-o"></i>Scrap Sale</a>
                            </li>
                            @endcan
                            @can('scrap_sales_receipt')
                            <li class="{{ Route::currentRouteName() == 'scrap_sale_receipt.all' ? 'active' : '' }}">
                                <a href="{{ route('scrap_sale_receipt.all') }}"><i class="fa fa-circle-o"></i>Scrap Sale Receipt</a>
                            </li>
                                @endcan
                                @can('project_wise_client')
                            <li class="{{ Route::currentRouteName() == 'project_wise_client' ? 'active' : '' }}">
                                <a href="{{ route('project_wise_client') }}"><i class="fa fa-circle-o"></i>Project Wise Client</a>
                            </li>
                             @endcan

                        </ul>
                    </li>
                @endcan

                <?php
                $subMenu = [
                    'account_head.type', 'account_head.type.add', 'account_head.type.edit',
                    'account_head', 'account_head.add', 'account_head.edit',
                    'voucher', 'voucher.create', 'voucher.edit','voucher_details',
                    'receipt', 'receipt.create', 'receipt.edit','receipt_details',
                    'journal_voucher', 'journal_voucher.create', 'journal_voucher.edit','journal_voucher_details',
                    'balance_transfer.add','balance_transfer','balance_transfer',
                    'balance_transfer_voucher_details','balance_transfer_receipt_details',
                    'report.trail_balance','report.ledger','report.receive_and_payment','report.project_wise_ledger','payment_check','payment_check.add','payment_check.edit','receive_cheque','receive_cheque.add','receive_cheque.edit'];
                ?>
                @can('accounts_control')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-book text-info"></i> <span>Accounts</span>
                            <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                            <?php
                            $subSubMenu = ['account_head.type', 'account_head.type.add', 'account_head.type.edit'];
                            ?>
                            @can('type')
                                <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <a href="{{ route('account_head.type') }}"><i class=" fa  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i>Type</a>
                                </li>
                            @endcan
                            <?php
                            $subSubMenu = ['account_head', 'account_head.add', 'account_head.edit'];
                            ?>
                            @can('account_head')
                                <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <a href="{{ route('account_head') }}"><i class=" fa  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i>Account Head</a>
                                </li>
                            @endcan
                            <?php
                            $subSubMenu = ['voucher.create'];
                            ?>
                            @can('create_payment_voucher')
                                <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <a href="{{ route('voucher.create') }}"><i class=" fa  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i>Create Payment Voucher</a>
                                </li>
                            @endcan
                            <?php
                            $subSubMenu = ['voucher','voucher.edit','voucher_details',];
                            ?>
                            @can('payment_voucher')
                                <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <a href="{{ route('voucher') }}"><i class="fa  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i>Payment Vouchers</a>
                                </li>
                            @endcan
                            <?php
                            $subSubMenu = ['receipt.create'];
                            ?>
                            @can('create_receipt_voucher')
                                <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <a href="{{ route('receipt.create') }}"><i class="fa  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i>Create Receipt Voucher</a>
                                </li>
                            @endcan
                            <?php
                            $subSubMenu = ['receipt','receipt.edit','receipt_details',];
                            ?>
                            @can('receipt_voucher')
                                <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <a href="{{ route('receipt') }}"><i class=" fa  {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i>Receipt Vouchers</a>
                                </li>
                            @endcan
                            <?php
                            $subSubMenu = ['balance_transfer.add','balance_transfer','balance_transfer',
                                'balance_transfer_voucher_details','balance_transfer_receipt_details']
                            ?>
                            @can('balance_transfer')
                                <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <a href="{{ route('balance_transfer') }}"><i class="fa {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i> Balance Transfer</a>
                                </li>
                            @endcan
                            <?php
                            $subSubMenu = ['payment_check','payment_check.add','payment_check.edit']
                            ?>
                            @can('balance_transfer')
                                <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <a href="{{ route('payment_check') }}"><i class="fa {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i> Payment Check</a>
                                </li>
                            @endcan
                            <?php
                            $subSubMenu = ['receive_cheque','receive_cheque.add','receive_cheque.edit']
                            ?>
                            @can('balance_transfer')
                                <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                    <a href="{{ route('receive_cheque') }}"><i class="fa {{ in_array(Route::currentRouteName(), $subSubMenu) ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i> Receive Check</a>
                                </li>
                            @endcan
                            @can('ledger')
                                <li class="{{ Route::currentRouteName() == 'report.ledger' ? 'active' : '' }}">
                                    <a href="{{ route('report.ledger') }}"><i class="fa {{ Route::currentRouteName() == 'report.ledger' ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i> Ledger</a>
                                </li>
                            @endcan
                            @can('ledger')
                                <li class="{{ Route::currentRouteName() == 'report.project_wise_ledger' ? 'active' : '' }}">
                                    <a href="{{ route('report.project_wise_ledger') }}"><i class="fa {{ Route::currentRouteName() == 'report.ledger' ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i> Project Wise Ledger</a>
                                </li>
                            @endcan
                            @can('trial_balance')
                                <li class="{{ Route::currentRouteName() == 'report.trail_balance' ? 'active' : '' }}">
                                    <a href="{{ route('report.trail_balance') }}"><i class="fa {{ Route::currentRouteName() == 'report.trail_balance' ? 'fa-check-circle text-purple' : 'fa-circle-o' }}"></i> Trail Balance</a>
                                </li>
                            @endcan
                            @can('receive_and_payment')
                                <li class="{{ Route::currentRouteName() == 'report.receive_and_payment' ? 'active' : '' }}">
                                    <a href="{{ route('report.receive_and_payment') }}"><i class="fa fa-circle-o"></i>Receive Report</a>
                                </li>
                            @endcan
                            @can('receive_and_payment')
                                <li class="{{ Route::currentRouteName() == 'report.payment' ? 'active' : '' }}">
                                    <a href="{{ route('report.payment') }}"><i class="fa fa-circle-o"></i>Payment Report</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan


                <?php
                $subMenu = ['report.salary.sheet','report.purchase', 'report.sale', 'report.balance_summary',
                    'report.ledger','report.trail_balance','report.purchase_stock', 'report.monthly_expenditure',
                    'report.employee_attendance','report.project_statement','report.sale_stock','report.price.with.stock','report.price.without.stock','client_report','project_report',
                    'report.client_report','report.project_report','report.group_summary','report.customer_ledger','report.cash',
                    'report.budget_wise_report','budget_report.details','food_cost.report','labour_bill_process.report','report.employee_advance_salary',
                    'receive_and_payment.report','report.project_summary','report.year_wise_payment'];
                ?>

                @can('report_information')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-file text-info"></i> <span>Report Information</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                            @can('ledger')
                            <li class="{{ Route::currentRouteName() == 'report.ledger' ? 'active' : '' }}">
                                <a href="{{ route('report.ledger') }}"><i class="fa fa-circle-o"></i> Ledger</a>
                            </li>
                            @endcan
                            @can('trail_balance')
                            <li class="{{ Route::currentRouteName() == 'report.trail_balance' ? 'active' : '' }}">
                                <a href="{{ route('report.trail_balance') }}"><i class="fa fa-circle-o"></i> Trail Balance</a>
                            </li>
                            @endcan
                            @can('project_wise_report')
                                    <li class="{{ Route::currentRouteName() == 'report.project_report' ? 'active' : '' }}">
                                        <a href="{{ route('report.project_report') }}"><i class="fa fa-circle-o"></i> Project Wise Report</a>
                                    </li>
                             @endcan
                            @can('employee_attendance_report')
                                    <li class="{{ Route::currentRouteName() == 'report.employee_attendance' ? 'active' : '' }}">
                                        <a href="{{ route('report.employee_attendance') }}"><i class="fa fa-circle-o"></i> Employee Attendance</a>
                                    </li>
                          @endcan
                                @can('employee_in_out_report')
                                 <li class="{{ Route::currentRouteName() == 'report.employee_attendance_in_out' ? 'active' : '' }}">
                                    <a href="{{ route('report.employee_attendance_in_out') }}"><i class="fa fa-circle-o"></i> Employee In Out</a>
                                </li>
                                @endcan

                            @can('salary_sheet')
                                    <li class="{{ Route::currentRouteName() == 'report.salary.sheet' ? 'active' : '' }}">
                                        <a href="{{ route('report.salary.sheet') }}"><i class="fa fa-circle-o"></i> Salary Sheet</a>
                                    </li>
                                @endcan

                            @can('purchase_report')
                                    <li class="{{ Route::currentRouteName() == 'report.purchase' ? 'active' : '' }}">
                                        <a href="{{ route('report.purchase') }}"><i class="fa fa-circle-o"></i> Purchase Report</a>
                                    </li>
                            @endcan

                            @can('sale_report')
                                    <li class="{{ Route::currentRouteName() == 'report.sale' ? 'active' : '' }}">
                                        <a href="{{ route('report.sale') }}"><i class="fa fa-circle-o"></i> Sale Report</a>
                                    </li>
                            @endcan

                            @can('employee_advance_report')
                                    <li class="{{ Route::currentRouteName() == 'report.employee_advance_salary' ? 'active' : '' }}">
                                        <a href="{{ route('report.employee_advance_salary') }}"><i class="fa fa-circle-o"></i> Employee Advance Report</a>
                                    </li>
                                @endcan

                            @can('labour_advance_report')
                                    <li class="{{ Route::currentRouteName() == 'food_cost.report' ? 'active' : '' }}">
                                        <a href="{{ route('food_cost.report') }}"><i class="fa fa-circle-o"></i> Labour Advance Report</a>
                                    </li>
                            @endcan

                           @can('labour_bill_report')
                                <li class="{{ Route::currentRouteName() == 'labour_bill_process.report' ? 'active' : '' }}">
                                    <a href="{{ route('labour_bill_process.report') }}"><i class="fa fa-circle-o"></i> Labour Bill Report</a>
                                </li>
                            @endcan
                            @can('project_summary_report')
                                <li class="{{ Route::currentRouteName() == 'report.project_summary' ? 'active' : '' }}">
                                    <a href="{{ route('report.project_summary') }}"><i class="fa fa-circle-o"></i> Project Summary Report</a>
                                </li>
                                @endcan
                                @can('year_wise_report')
                                <li class="{{ Route::currentRouteName() == 'report.year_wise_payment' ? 'active' : '' }}">
                                    <a href="{{ route('report.year_wise_payment') }}"><i class="fa fa-circle-o"></i>Year Wise Payment Report</a>
                                </li>
                                @endcan
                        </ul>
                    </li>
                @endcan

                <?php
                $subMenu = ['user.all', 'user.edit', 'user.add'];
                ?>
                @can('user_management')
                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-users text-info"></i> <span>User Management</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">

                        @can('users')
                                <li class="{{ Route::currentRouteName() == 'user.all' ? 'active' : '' }}">
                                    <a href="{{ route('user.all') }}"><i class="fa fa-circle-o"></i> Users</a>
                                </li>
                        @endcan
                        </ul>
                    </li>
                @endcan
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
            <b>Design & Developed By <a target="_blank" href="https://binaryrecontech.com/">BR TECH</a></b>
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
<!-- Select2 -->
<script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('themes/backend/bower_components/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('themes/backend/js/adminlte.min.js') }}"></script>
<script src="{{ asset('themes/backend/js/sweetalert2@9.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //Initialize Select2 Elements
        $('.select2').select2();
        //Date picker
        $('#date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        $('.date-picker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        var errorMessage = '{{ session('error') }}';

        if(errorMessage != ''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errorMessage,
            });
        }

    });
    function jsNumberFormat(yourNumber) {
        //Seperates the components of the number
        var n= yourNumber.toString().split(".");
        //Comma-fies the first part
        n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //Combines the two sections
        return n.join(".");
    }
</script>

@yield('script')

</body>
</html>
