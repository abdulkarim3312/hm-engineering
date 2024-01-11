@extends('layouts.app')

@section('style')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/iCheck/square/blue.css') }}">
@endsection

@section('title')
    User Edit
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">User Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('user.edit', ['user' => $user->id]) }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $user->name) : old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Email *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Email"
                                       name="email" value="{{ empty(old('email')) ? ($errors->has('email') ? '' : $user->email) : old('email') }}">

                                @error('email')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('password') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Password *</label>

                            <div class="col-sm-10">
                                <input type="password" class="form-control" placeholder="Enter Password"
                                       name="password">

                                @error('password')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Confirm Password *</label>

                            <div class="col-sm-10">
                                <input type="password" class="form-control" placeholder="Enter Confirm Password"
                                       name="password_confirmation">
                            </div>
                        </div>

                        <table class="table table-bordered table-responsive">
                            <tr>
                                <td colspan="2">
                                    <input type="checkbox" id="checkAll"> Check All
                                </td>
                            </tr>

                            <tr >
                                <td rowspan="3" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="administrator" id="administrator" {{ ($user->can('administrator') ? 'checked' : '') }}> Administrator
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="dashboard" id="dashboard" {{ ($user->can('dashboard') ? 'checked' : '') }}> Dashboard
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="warehouses" id="warehouses" {{ ($user->can('warehouses') ? 'checked' : '') }}> Warehouses
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="unit" id="unit" {{ ($user->can('unit') ? 'checked' : '') }}> Unit
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="10" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="accounts_control" id="accounts_control" {{ ($user->can('accounts_control') ? 'checked' : '') }}> Accounts
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="type" id="type"{{ ($user->can('type') ? 'checked' : '') }}> Type
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="account_head" id="account_head" {{ ($user->can('account_head') ? 'checked' : '') }}> Account Head
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="create_payment_voucher" id="create_payment_voucher" {{ ($user->can('create_payment_voucher') ? 'checked' : '') }}> Create Payment Voucher
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="payment_voucher" id="payment_voucher" {{ ($user->can('payment_voucher') ? 'checked' : '') }}> Payment Voucher
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="create_receipt_voucher" id="create_receipt_voucher" {{ ($user->can('create_receipt_voucher') ? 'checked' : '') }}> Create Receipt Voucher
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="receipt_voucher" id="receipt_voucher" {{ ($user->can('receipt_voucher') ? 'checked' : '') }}> Receipt Voucher
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="balance_transfer" id="balance_transfer" {{ ($user->can('balance_transfer') ? 'checked' : '') }}> Balance Transfer
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="ledger" id="ledger" {{ ($user->can('ledger') ? 'checked' : '') }}> Ledger
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="trial_balance" id="trial_balance" {{ ($user->can('trial_balance') ? 'checked' : '') }}> Trial Balance
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="receive_and_payment" id="receive_and_payment" {{ ($user->can('receive_and_payment') ? 'checked' : '') }}> Receive and Payment
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="26" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="estimation_and_costing" id="estimation_and_costing" {{ ($user->can('estimation_and_costing') ? 'checked' : '') }}>Estimation & Costing
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="estimate_project" id="estimate_project" {{ ($user->can('estimate_project') ? 'checked' : '') }}> Estimate Project
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="estimate_floor" id="estimate_floor" {{ ($user->can('estimate_floor') ? 'checked' : '') }}> Estimate Floor
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="floor_unit" id="floor_unit" {{ ($user->can('floor_unit') ? 'checked' : '') }}> Floor Unit
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="unit_section" id="unit_section" {{ ($user->can('unit_section') ? 'checked' : '') }}> Unit Section
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="beam_type" id="beam_type" {{ ($user->can('beam_type') ? 'checked' : '') }}> Beam Type
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="column_type" id="column_type" {{ ($user->can('column_type') ? 'checked' : '') }}> Column Type
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="batch" id="batch" {{ ($user->can('batch') ? 'checked' : '') }}> Batch
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="grade_of_concrete_type" id="grade_of_concrete_type" {{ ($user->can('grade_of_concrete_type') ? 'checked' : '') }}> Grade Of Concrete Type
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="costing_segment" id="costing_segment" {{ ($user->can('costing_segment') ? 'checked' : '') }}> Costing Segment
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="estimate_product" id="estimate_product" {{ ($user->can('estimate_product') ? 'checked' : '') }}> Estimate Product
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="pile_configure" id="pile_configure" {{ ($user->can('pile_configure') ? 'checked' : '') }}> Pile Configure
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="beam_configure" id="beam_configure" {{ ($user->can('beam_configure') ? 'checked' : '') }}> Beam Configure
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="column_configure" id="column_configure" {{ ($user->can('column_configure') ? 'checked' : '') }}> Column Configure
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="slab_cap_wall_configure" id="slab_cap_wall_configure" {{ ($user->can('slab_cap_wall_configure') ? 'checked' : '') }}> Slab/P.Cap/Mat/R.wall Configure
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="bricks_configure" id="bricks_configure" {{ ($user->can('bricks_configure') ? 'checked' : '') }}> Bricks Configure
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="plaster_configure" id="plaster_configure" {{ ($user->can('plaster_configure') ? 'checked' : '') }}> Plaster Configure
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="grill_glass_tiles_configure" id="grill_glass_tiles_configure" {{ ($user->can('grill_glass_tiles_configure') ? 'checked' : '') }}> Grill/Glass/Tiles Configure
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="paint_configure" id="paint_configure" {{ ($user->can('paint_configure') ? 'checked' : '') }}> Paint Configure
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="earth_work_configure" id="earth_work_configure" {{ ($user->can('earth_work_configure') ? 'checked' : '') }}> Earth Work Configure
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="extra_costing" id="extra_costing" {{ ($user->can('extra_costing') ? 'checked' : '') }}> Extra Costing
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="mobilization_product" id="mobilization_product" {{ ($user->can('mobilization_product') ? 'checked' : '') }}> Mobilization Product
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="mobilization_work" id="mobilization_work" {{ ($user->can('mobilization_work') ? 'checked' : '') }}> Mobilization Work
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="grade_of_concrete" id="grade_of_concrete" {{ ($user->can('grade_of_concrete') ? 'checked' : '') }}> Grade Of Concrete
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="estimate_report" id="estimate_report" {{ ($user->can('estimate_report') ? 'checked' : '') }}> Estimate Report
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="costing_report" id="costing_report" {{ ($user->can('costing_report') ? 'checked' : '') }}> Costing Report
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="estimation_costing_summary" id="estimation_costing_summary" {{ ($user->can('estimation_costing_summary') ? 'checked' : '') }}> Estimation & Costing Summary
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="5" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="hr_department" id="hr_department" {{ ($user->can('hr_department') ? 'checked' : '') }}> HR Department
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="department" id="department" {{ ($user->can('department') ? 'checked' : '') }}> Department
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="designation" id="designation" {{ ($user->can('designation') ? 'checked' : '') }}> Designation
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="employee" id="employee" {{ ($user->can('employee') ? 'checked' : '') }}> employee
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="employee_list" id="employee_list" {{ ($user->can('employee_list') ? 'checked' : '') }}> employee list
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="employee_attendance" id="employee_attendance" {{ ($user->can('employee_attendance') ? 'checked' : '') }}> employee attendance
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="7" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="labour_department" id="labour_department" {{ ($user->can('labour_department') ? 'checked' : '') }}> Labour Department
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="labour_designation" id="labour_designation" {{ ($user->can('labour_designation') ? 'checked' : '') }}> labour designation
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="labour" id="labour" {{ ($user->can('labour') ? 'checked' : '') }}> Labour
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="labour_list" id="labour_list" {{ ($user->can('labour_list') ? 'checked' : '') }}> Labour list
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="labour_attendance" id="labour_attendance" {{ ($user->can('labour_attendance') ? 'checked' : '') }}> Labour attendance
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="labour_attendance_report" id="labour_attendance_report" {{ ($user->can('labour_attendance_report') ? 'checked' : '') }}> Labour attendance report
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="labour_advance_amount" id="labour_advance_amount" {{ ($user->can('labour_advance_amount') ? 'checked' : '') }}> Labour advance amount
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="labour_bill_process" id="labour_bill_process" {{ ($user->can('labour_bill_process') ? 'checked' : '') }}> Labour bill process
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="4" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="payroll_department" id="payroll_department" {{ ($user->can('payroll_department') ? 'checked' : '') }}> Payroll Department
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="salary_update" id="salary_update" {{ ($user->can('salary_update') ? 'checked' : '') }}> Salary update
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="salary_process" id="salary_process" {{ ($user->can('salary_process') ? 'checked' : '') }}> Salary process
                                </td>
                            </tr>
{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    <input type="checkbox" name="permission[]" value="leave_request" id="leave_request" {{ ($user->can('leave_request') ? 'checked' : '') }}> Leave Request--}}
{{--                                </td>--}}
{{--                            </tr>--}}

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="leave" id="leave" {{ ($user->can('leave') ? 'checked' : '') }}> Leave
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="holiday" id="holiday" {{ ($user->can('holiday') ? 'checked' : '') }}> Holiday
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="project_control" id="project_control" {{ ($user->can('project_control') ? 'checked' : '') }}> Project Control
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="all_project" id="all_project" {{ ($user->can('all_project') ? 'checked' : '') }}> All Project
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="requisition_area" id="requisition_area" {{ ($user->can('requisition_area') ? 'checked' : '') }}> Requisition Area
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="create_requisition" id="create_requisition" {{ ($user->can('create_requisition') ? 'checked' : '') }}> Create Requisition
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="requisition_lists" id="requisition_lists" {{ ($user->can('requisition_lists') ? 'checked' : '') }}> Requisition Lists
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="4" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="procurement_and_purchase" id="procurement_and_purchase" {{ ($user->can('procurement_and_purchase') ? 'checked' : '') }}> Procurement & Purchase
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="supplier" id="supplier" {{ ($user->can('supplier') ? 'checked' : '') }}> Supplier
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="product" id="product" {{ ($user->can('product') ? 'checked' : '') }}> Product
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="realstate_purchase" id="realstate_purchase" {{ ($user->can('realstate_purchase') ? 'checked' : '') }}> Real Estate Purchase
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="normal_purchase" id="normal_purchase" {{ ($user->can('normal_purchase') ? 'checked' : '') }}> Normal Purchase
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="14" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="sale_system" id="sale_system" {{ ($user->can('sale_system') ? 'checked' : '') }}> Sale System
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="floor" id="floor" {{ ($user->can('floor') ? 'checked' : '') }}> Floor
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="apartment_shop" id="apartment_shop" {{ ($user->can('apartment_shop') ? 'checked' : '') }}> Apartment Shop
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="client_profile" id="client_profile" {{ ($user->can('client_profile') ? 'checked' : '') }}> Client Profile
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="create_sales_order" id="create_sales_order" {{ ($user->can('create_sales_order') ? 'checked' : '') }}> Create Sales Order
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="trading_sales_order" id="trading_sales_order" {{ ($user->can('trading_sales_order') ? 'checked' : '') }}> Trading Sales Order
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="trading_sales_receipt" id="trading_sales_receipt" {{ ($user->can('trading_sales_receipt') ? 'checked' : '') }}> Trading Sales Receipt
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="sales_system_receipt" id="sales_system_receipt" {{ ($user->can('sales_system_receipt') ? 'checked' : '') }}> Receipt
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="client_payment_record" id="client_payment_record" {{ ($user->can('client_payment_record') ? 'checked' : '') }}> Client Payment Record
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="sale_inventory" id="sale_inventory" {{ ($user->can('sale_inventory') ? 'checked' : '') }}> Sale Inventory
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="sale_inventory_view" id="sale_inventory_view" {{ ($user->can('sale_inventory_view') ? 'checked' : '') }}> Sale Inventory View
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="scrap_product" id="scrap_product" {{ ($user->can('scrap_product') ? 'checked' : '') }}> Scrap Product
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="scrap_sale" id="scrap_sale" {{ ($user->can('scrap_sale') ? 'checked' : '') }}> Scrap Sale
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="scrap_sales_receipt" id="scrap_sales_receipt" {{ ($user->can('scrap_sales_receipt') ? 'checked' : '') }}> Scrap Sales Receipt
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="project_wise_client" id="project_wise_client" {{ ($user->can('project_wise_client') ? 'checked' : '') }}> Project Wise Client
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="11" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="report_information" id="report_information" {{ ($user->can('report_information') ? 'checked' : '') }}> Report information
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="project_wise_report" id="project_wise_report" {{ ($user->can('project_wise_report') ? 'checked' : '') }}> Project wise report
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="employee_attendance_report" id="employee_attendance_report" {{ ($user->can('employee_attendance_report') ? 'checked' : '') }}> Employee Attendance Report
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="salary_sheet" id="salary_sheet" {{ ($user->can('salary_sheet') ? 'checked' : '') }}> Salary Sheet
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="purchase_report" id="purchase_report" {{ ($user->can('purchase_report') ? 'checked' : '') }}> Purchase Report
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="sale_report" id="sale_report" {{ ($user->can('sale_report') ? 'checked' : '') }}> Sale Report
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="employee_advance_report" id="employee_advance_report" {{ ($user->can('employee_advance_report') ? 'checked' : '') }}> Employee Advance Report
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="employee_in_out_report" id="employee_in_out_report" {{ ($user->can('employee_in_out_report') ? 'checked' : '') }}> Employee in Out Report
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="labour_advance_report" id="labour_advance_report" {{ ($user->can('labour_advance_report') ? 'checked' : '') }}> labour advance report
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="labour_bill_report" id="labour_bill_report" {{ ($user->can('labour_bill_report') ? 'checked' : '') }}> labour bill report
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="project_summary_report" id="project_summary_report" {{ ($user->can('project_summary_report') ? 'checked' : '') }}> Project Summary Report
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="year_wise_report" id="year_wise_report" {{ ($user->can('year_wise_report') ? 'checked' : '') }}> Year Wise Report
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="user_management" id="user_management" {{ ($user->can('user_management') ? 'checked' : '') }}> User Management
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="users" id="users" {{ ($user->can('users') ? 'checked' : '') }}> Users
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- iCheck -->
    <script src="{{ asset('themes/backend/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $("#checkAll").click(function () {
                $('input:checkbox').not(this).prop('disabled', this.disabled);
                $('input:checkbox').not(this).prop('checked', this.checked);

                init();
            });

            // Administrator
            $('#administrator').click(function () {
                if ($(this).prop('checked')) {
                    $('#dashboard').attr("disabled", false);
                    $('#warehouses').attr("disabled", false);
                    $('#unit').attr("disabled", false);
                } else {
                    $('#dashboard').attr("disabled", true);
                    $('#warehouses').attr("disabled", true);
                    $('#unit').attr("disabled", true);
                }
            });




            // Hr department
            $('#hr_department').click(function () {
                if ($(this).prop('checked')) {
                    $('#employee').attr("disabled", false);
                    $('#employee_list').attr("disabled", false);
                    $('#department').attr("disabled", false);
                    $('#designation').attr("disabled", false);
                    $('#employee_attendance').attr("disabled", false);
                } else {
                    $('#employee').attr("disabled", true);
                    $('#employee_list').attr("disabled", true);
                    $('#department').attr("disabled", true);
                    $('#designation').attr("disabled", true);
                    $('#employee_attendance').attr("disabled", true);
                }
            });

            // Labour department
            $('#labour_department').click(function () {
                if ($(this).prop('checked')) {
                    $('#labour_designation').attr("disabled", false);
                    $('#labour').attr("disabled", false);
                    $('#labour_list').attr("disabled", false);
                    $('#labour_attendance').attr("disabled", false);
                    $('#labour_attendance_report').attr("disabled", false);
                    $('#labour_advance_amount').attr("disabled", false);
                    $('#labour_bill_process').attr("disabled", false);
                } else {
                    $('#labour_designation').attr("disabled", true);
                    $('#labour').attr("disabled", true);
                    $('#labour_list').attr("disabled", true);
                    $('#labour_attendance').attr("disabled", true);
                    $('#labour_attendance_report').attr("disabled", true);
                    $('#labour_advance_amount').attr("disabled", true);
                    $('#labour_bill_process').attr("disabled", true);
                }
            });

            //payroll_department
            $('#payroll_department').click(function () {
                if ($(this).prop('checked')) {
                    $('#salary_update').attr("disabled", false);
                    $('#salary_process').attr("disabled", false);
                    // $('#leave_request').attr("disabled", false);
                    $('#leave').attr("disabled", false);
                    $('#holiday').attr("disabled", false);
                } else {
                    $('#salary_update').attr("disabled", true);
                    $('#salary_process').attr("disabled", true);
                    // $('#leave_request').attr("disabled", true);
                    $('#leave').attr("disabled", true);
                    $('#holiday').attr("disabled", true);
                }
            });

            //project_control
            $('#project_control').click(function () {
                if ($(this).prop('checked')) {
                    $('#all_project').attr("disabled", false);
                } else {
                    $('#all_project').attr("disabled", true);
                }
            });
            //Requisition_area
            $('#requisition_area').click(function () {
                if ($(this).prop('checked')) {
                    $('#create_requisition').attr("disabled", false);
                    $('#requisition_lists').attr("disabled", false);
                } else {
                    $('#create_requisition').attr("disabled", true);
                    $('#requisition_lists').attr("disabled", true);
                }
            });

            // Procurement_and_purchase
            $('#procurement_and_purchase').click(function () {
                if ($(this).prop('checked')) {
                    $('#product').attr("disabled", false);
                    $('#realstate_purchase').attr("disabled", false);
                    $('#normal_purchase').attr("disabled", false);
                    $('#supplier').attr("disabled", false);
                } else {
                    $('#product').attr("disabled", true);
                    $('#realstate_purchase').attr("disabled", true);
                    $('#normal_purchase').attr("disabled", true);
                    $('#supplier').attr("disabled", true);

                }
            });

            // Accounts
            $('#accounts_control').click(function () {
                if ($(this).prop('checked')) {
                    $('#type').attr("disabled", false);
                    $('#account_head').attr("disabled", false);
                    $('#create_payment_voucher').attr("disabled", false);
                    $('#payment_voucher').attr("disabled", false);
                    $('#create_receipt_voucher').attr("disabled", false);
                    $('#receipt_voucher').attr("disabled", false);
                    $('#trial_balance').attr("disabled", false);
                    $('#receive_and_payment').attr("disabled", false);
                    $('#balance_transfer').attr("disabled", false);
                    $('#ledger').attr("disabled", false);
                } else {
                    $('#type').attr("disabled", true);
                    $('#account_head').attr("disabled", true);
                    $('#create_payment_voucher').attr("disabled", true);
                    $('#payment_voucher').attr("disabled", true);
                    $('#create_receipt_voucher').attr("disabled", true);
                    $('#receipt_voucher').attr("disabled", true);
                    $('#trial_balance').attr("disabled", true);
                    $('#receive_and_payment').attr("disabled", true);
                    $('#balance_transfer').attr("disabled", true);
                    $('#ledger').attr("disabled", true);
                }
            });

            // Notice Board
            $('#sale_system').click(function () {
                if ($(this).prop('checked')) {
                    $('#floor').attr("disabled", false);
                    $('#apartment_shop').attr("disabled", false);
                    $('#client_profile').attr("disabled", false);
                    $('#create_sales_order').attr("disabled", false);
                    $('#sales_system_receipt').attr("disabled", false);
                    $('#client_payment_record').attr("disabled", false);
                    $('#trading_sales_order').attr("disabled", false);
                    $('#trading_sales_receipt').attr("disabled", false);
                    $('#sale_inventory').attr("disabled", false);
                    $('#sale_inventory_view').attr("disabled", false);
                    $('#scrap_product').attr("disabled", false);
                    $('#scrap_sale').attr("disabled", false);
                    $('#scrap_sales_receipt').attr("disabled", false);
                    $('#project_wise_client').attr("disabled", false);

                } else {
                    $('#floor').attr("disabled", true);
                    $('#apartment_shop').attr("disabled", true);
                    $('#client_profile').attr("disabled", true);
                    $('#create_sales_order').attr("disabled", true);
                    $('#sales_system_receipt').attr("disabled", true);
                    $('#client_payment_record').attr("disabled", true);
                    $('#trading_sales_order').attr("disabled", true);
                    $('#trading_sales_receipt').attr("disabled", true);
                    $('#sale_inventory').attr("disabled", true);
                    $('#sale_inventory_view').attr("disabled", true);
                    $('#scrap_product').attr("disabled", true);
                    $('#scrap_sale').attr("disabled", true);
                    $('#scrap_sales_receipt').attr("disabled", true);
                    $('#project_wise_client').attr("disabled", true);

                }
            });

            // estimation and costing
            $('#estimation_and_costing').click(function () {
                if ($(this).prop('checked')) {
                    $('#estimate_project').attr("disabled", false);
                    $('#estimate_floor').attr("disabled", false);
                    $('#floor_unit').attr("disabled", false);
                    $('#unit_section').attr("disabled", false);
                    $('#beam_type').attr("disabled", false);
                    $('#column_type').attr("disabled", false);
                    $('#batch').attr("disabled", false);
                    $('#grade_of_concrete_type').attr("disabled", false);
                    $('#costing_segment').attr("disabled", false);
                    $('#estimate_product').attr("disabled", false);
                    $('#pile_configure').attr("disabled", false);
                    $('#beam_configure').attr("disabled", false);
                    $('#column_configure').attr("disabled", false);
                    $('#slab_cap_wall_configure').attr("disabled", false);
                    $('#bricks_configure').attr("disabled", false);
                    $('#plaster_configure').attr("disabled", false);
                    $('#grill_glass_tiles_configure').attr("disabled", false);
                    $('#paint_configure').attr("disabled", false);
                    $('#earth_work_configure').attr("disabled", false);
                    $('#extra_costing').attr("disabled", false);
                    $('#mobilization_product').attr("disabled", false);
                    $('#mobilization_work').attr("disabled", false);
                    $('#grade_of_concrete').attr("disabled", false);
                    $('#estimate_report').attr("disabled", false);
                    $('#costing_report').attr("disabled", false);
                    $('#estimation_costing_summary').attr("disabled", false);
                } else {
                    $('#estimate_project').attr("disabled", true);
                    $('#estimate_floor').attr("disabled", true);
                    $('#floor_unit').attr("disabled", true);
                    $('#unit_section').attr("disabled", true);
                    $('#beam_type').attr("disabled", true);
                    $('#column_type').attr("disabled", true);
                    $('#batch').attr("disabled", true);
                    $('#grade_of_concrete_type').attr("disabled", true);
                    $('#costing_segment').attr("disabled", true);
                    $('#estimate_product').attr("disabled", true);
                    $('#pile_configure').attr("disabled", true);
                    $('#beam_configure').attr("disabled", true);
                    $('#column_configure').attr("disabled", true);
                    $('#slab_cap_wall_configure').attr("disabled", true);
                    $('#bricks_configure').attr("disabled", true);
                    $('#plaster_configure').attr("disabled", true);
                    $('#grill_glass_tiles_configure').attr("disabled", true);
                    $('#paint_configure').attr("disabled", true);
                    $('#earth_work_configure').attr("disabled", true);
                    $('#extra_costing').attr("disabled", true);
                    $('#mobilization_product').attr("disabled", true);
                    $('#mobilization_work').attr("disabled", true);
                    $('#grade_of_concrete').attr("disabled", true);
                    $('#estimate_report').attr("disabled", true);
                    $('#costing_report').attr("disabled", true);
                    $('#estimation_costing_summary').attr("disabled", true);
                }
            });

            // Report
            $('#report_information').click(function () {
                if ($(this).prop('checked')) {
                    $('#project_wise_report').attr("disabled", false);
                    $('#employee_attendance_report').attr("disabled", false);
                    $('#salary_sheet').attr("disabled", false);
                    $('#purchase_report').attr("disabled", false);
                    $('#sale_report').attr("disabled", false);
                    $('#employee_advance_report').attr("disabled", false);
                    $('#employee_in_out_report').attr("disabled", false);
                    $('#labour_advance_report').attr("disabled", false);
                    $('#labour_bill_report').attr("disabled", false);
                    $('#project_summary_report').attr("disabled", false);
                    $('#year_wise_report').attr("disabled", false);
                } else {
                    $('#project_wise_report').attr("disabled", true);
                    $('#employee_attendance_report').attr("disabled", true);
                    $('#salary_sheet').attr("disabled", true);
                    $('#purchase_report').attr("disabled", true);
                    $('#sale_report').attr("disabled", true);
                    $('#employee_advance_report').attr("disabled", true);
                    $('#employee_in_out_report').attr("disabled", true);
                    $('#labour_advance_report').attr("disabled", true);
                    $('#labour_bill_report').attr("disabled", true);
                    $('#project_summary_report').attr("disabled", true);
                    $('#year_wise_report').attr("disabled", true);
                }
            });

            //User Management
            $('#user_management').click(function () {
                if ($(this).prop('checked')) {
                    $('#users').attr("disabled", false);
                } else {
                    $('#users').attr("disabled", true);
                }
            });

            init();
        });

        function init() {
            if (!$('#administrator').prop('checked')) {
                $('#dashboard').attr("disabled", true);
                $('#warehouses').attr("disabled", true);
                $('#unit').attr("disabled", true);
            }

            if (!$('#hr_department').prop('checked')) {
                $('#employee').attr("disabled", true);
                $('#employee_list').attr("disabled", true);
                $('#department').attr("disabled", true);
                $('#designation').attr("disabled", true);
                $('#employee_attendance').attr("disabled", true);
            }
            if (!$('#labour_department').prop('checked')) {
                $('#labour_designation').attr("disabled", true);
                $('#labour').attr("disabled", true);
                $('#labour_list').attr("disabled", true);
                $('#labour_attendance').attr("disabled", true);
                $('#labour_attendance_report').attr("disabled", true);
                $('#labour_advance_amount').attr("disabled", true);
                $('#labour_bill_process').attr("disabled", true);
            }

            if (!$('#payroll_department').prop('checked')) {
                $('#salary_update').attr("disabled", true);
                $('#salary_process').attr("disabled", true);
                // $('#leave_request').attr("disabled", true);
                $('#leave').attr("disabled", true);
                $('#holiday').attr("disabled", true);
            }
            if (!$('#requisition_area').prop('checked')) {
                $('#create_requisition').attr("disabled", true);
                $('#requisition_lists').attr("disabled", true);
            }
            if (!$('#project_control').prop('checked')) {
                $('#all_project').attr("disabled", true);
            }

            if (!$('#procurement_and_purchase').prop('checked')) {
                $('#product').attr("disabled", true);
                $('#realstate_purchase').attr("disabled", true);
                $('#normal_purchase').attr("disabled", true);
                $('#supplier').attr("disabled", true);
            }

            if (!$('#accounts_control').prop('checked')) {
                $('#type').attr("disabled", true);
                $('#account_head').attr("disabled", true);
                $('#create_payment_voucher').attr("disabled", true);
                $('#payment_voucher').attr("disabled", true);
                $('#create_receipt_voucher').attr("disabled", true);
                $('#receipt_voucher').attr("disabled", true);
                $('#trial_balance').attr("disabled", true);
                $('#receive_and_payment').attr("disabled", true);
                $('#balance_transfer').attr("disabled", true);
                $('#ledger').attr("disabled", true);
            }
            if (!$('#sale_system').prop('checked')) {
                $('#floor').attr("disabled", true);
                $('#apartment_shop').attr("disabled", true);
                $('#client_profile').attr("disabled", true);
                $('#create_sales_order').attr("disabled", true);
                $('#sales_system_receipt').attr("disabled", true);
                $('#client_payment_record').attr("disabled", true);
                $('#trading_sales_order').attr("disabled", true);
                $('#trading_sales_receipt').attr("disabled", true);
                $('#sale_inventory').attr("disabled", true);
                $('#sale_inventory_view').attr("disabled", true);
                $('#scrap_product').attr("disabled", true);
                $('#scrap_sale').attr("disabled", true);
                $('#scrap_sales_receipt').attr("disabled", true);
                $('#project_wise_client').attr("disabled", true);
            }
            if (!$('#estimation_and_costing').prop('checked')) {
                $('#estimate_project').attr("disabled", true);
                $('#estimate_floor').attr("disabled", true);
                $('#floor_unit').attr("disabled", true);
                $('#unit_section').attr("disabled", true);
                $('#beam_type').attr("disabled", true);
                $('#column_type').attr("disabled", true);
                $('#batch').attr("disabled", true);
                $('#grade_of_concrete_type').attr("disabled", true);
                $('#costing_segment').attr("disabled", true);
                $('#estimate_product').attr("disabled", true);
                $('#pile_configure').attr("disabled", true);
                $('#beam_configure').attr("disabled", true);
                $('#column_configure').attr("disabled", true);
                $('#slab_cap_wall_configure').attr("disabled", true);
                $('#bricks_configure').attr("disabled", true);
                $('#plaster_configure').attr("disabled", true);
                $('#grill_glass_tiles_configure').attr("disabled", true);
                $('#paint_configure').attr("disabled", true);
                $('#earth_work_configure').attr("disabled", true);
                $('#extra_costing').attr("disabled", true);
                $('#mobilization_product').attr("disabled", true);
                $('#mobilization_work').attr("disabled", true);
                $('#grade_of_concrete').attr("disabled", true);
                $('#estimate_report').attr("disabled", true);
                $('#costing_report').attr("disabled", true);
                $('#estimation_costing_summary').attr("disabled", true);
            }

            if (!$('#report_information').prop('checked')) {
                $('#project_wise_report').attr("disabled", true);
                $('#employee_attendance_report').attr("disabled", true);
                $('#salary_sheet').attr("disabled", true);
                $('#purchase_report').attr("disabled", true);
                $('#sale_report').attr("disabled", true);
                $('#employee_advance_report').attr("disabled", true);
                $('#employee_in_out_report').attr("disabled", true);
                $('#labour_advance_report').attr("disabled", true);
                $('#labour_bill_report').attr("disabled", true);
                $('#project_summary_report').attr("disabled", true);
                $('#year_wise_report').attr("disabled", true);
            }
            if (!$('#user_management').prop('checked')) {
                $('#users').attr("disabled", true);
            }

        }
    </script>
@endsection
