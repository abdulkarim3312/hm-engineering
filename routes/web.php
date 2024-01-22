<?php

use App\Http\Controllers\AccountHeadController;
use App\Http\Controllers\BalanceTransferController;
use App\Http\Controllers\BricksConfigureController;
use App\Http\Controllers\CommonConfigureController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\EarthWorkConfigureController;
use App\Http\Controllers\EstimateFloorController;
use App\Http\Controllers\EstimateTypeController;
use App\Http\Controllers\ExtraCostingController;
use App\Http\Controllers\GradeOfConcreteTypeController;
use App\Http\Controllers\GrillGlassTilesConfigureController;
use App\Http\Controllers\GlassConfigureController;
use App\Http\Controllers\TilesConfigureController;
use App\Http\Controllers\JournalVoucherController;
use App\Http\Controllers\TradingSaleController;
use App\Http\Controllers\PaintConfigureController;
use App\Http\Controllers\PlasterConfigureController;
use App\Http\Controllers\GradeOfConcreteController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VoucherController;
use App\Http\Middleware\CustomMiddleware;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AssignSegmentController;
use App\Http\Controllers\CostCalculationController;
use App\Http\Controllers\CostingSegmentController;
use App\Http\Controllers\EstimateProductController;
use App\Http\Controllers\SegmentConfigureController;
use App\Http\Controllers\ColumnConfigureController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MobilizationWorkController;
use App\Http\Controllers\MobilizationWorkProductController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

//Route::middleware(['auth'])->group(function () {
//    Route::middleware(['custom:2'])->group(function (){
//          Route::get('employees/attendance', 'EmployeeAttendanceController@index')->name('employees.attendance');
//          Route::post('employees/attendance', 'EmployeeAttendanceController@singleEmployeeAttendancePost');
//    });
//});

Route::middleware(['auth'])->group(function () {
//    Route::middleware(['custom:1'])->group(function (){
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('payment-details', 'DashboardController@paymentDetails')->name('payment.details');

    // Unit
    Route::get('unit', 'UnitController@index')->name('unit')->middleware('permission:unit');
    Route::get('unit/add', 'UnitController@add')->name('unit.add')->middleware('permission:unit');
    Route::post('unit/add', 'UnitController@addPost')->middleware('permission:unit');
    Route::get('unit/edit/{unit}', 'UnitController@edit')->name('unit.edit')->middleware('permission:unit');
    Route::post('unit/edit/{unit}', 'UnitController@editPost')->middleware('permission:unit');

    //Country
    Route::get('country', 'CountryController@index')->name('country');
    Route::get('country/add', 'CountryController@add')->name('country.add');
    Route::post('country/add', 'CountryController@addPost');
    Route::get('country/edit/{country}', 'CountryController@edit')->name('country.edit');
    Route::post('country/edit/{country}', 'CountryController@editPost');

    //Employee
    Route::get('job/letter', [EmployeeController::class, 'jobLetterIndex'])->name('job_letter');
    Route::get('job/letter/add', [EmployeeController::class, 'jobLetterAdd'])->name('job_letter_add');
    Route::post('job/letter/add', [EmployeeController::class, 'jobLetterPost']);
    Route::get('job/letter/edit/{id}', [EmployeeController::class, 'jobLetterEdit'])->name('job_letter_edit');
    Route::post('job/letter/update/{id}', [EmployeeController::class, 'jobLetterEditPost'])->name('job_letter_update');
    Route::get('job/letter/view/{id}', [EmployeeController::class, 'jobLetterView'])->name('job_letter_view');
    Route::get('job/letter/print/{id}', [EmployeeController::class, 'jobLetterPrint'])->name('job_letter_print');
    Route::get('job/confirmation/letter', [EmployeeController::class, 'jobConfirmLetterIndex'])->name('job_confirm_letter');
    Route::get('job/confirmation/letter/add', [EmployeeController::class, 'jobConfirmLetterAdd'])->name('job_confirm_letter_add');
    Route::get('job/confirm/letter/edit/{id}', [EmployeeController::class, 'jobConfirmLetterEdit'])->name('job_confirm_letter_edit');
    Route::post('job/confirm/letter/update/{id}', [EmployeeController::class, 'jobConfirmLetterEditPost'])->name('job_confirm_letter_update');
    Route::post('job/confirmation/letter/add', [EmployeeController::class, 'jobConfirmLetterPost']);
    Route::get('job/confirm/letter/view/{id}', [EmployeeController::class, 'jobConfirmLetterView'])->name('job_confirm_letter_view');
    Route::get('job/promotion/letter', [EmployeeController::class, 'jobPromotionLetterIndex'])->name('job_promotion_letter');
    Route::get('job/promotion/letter/add', [EmployeeController::class, 'jobPromotionLetterAdd'])->name('job_promotion_letter_add');
    Route::get('job/promotion/letter/edit/{id}', [EmployeeController::class, 'jobPromotionLetterEdit'])->name('job_promotion_letter_edit');
    Route::post('job/promotion/letter/update/{id}', [EmployeeController::class, 'jobPromotionLetterEditPost'])->name('job_promotion_letter_update');
    Route::post('job/promotion/letter/add', [EmployeeController::class, 'jobPromotionLetterPost']);
    Route::get('job/promotion/letter/view/{id}', [EmployeeController::class, 'jobPromotionLetterView'])->name('job_promotion_letter_view');

    // Project
    Route::get('project', 'ProjectController@index')->name('project')->middleware('permission:all_project');
    Route::get('project/add', 'ProjectController@add')->name('project.add')->middleware('permission:all_project');
    Route::post('project/add', 'ProjectController@addPost')->middleware('permission:all_project');
    Route::get('project/edit/{project}', 'ProjectController@edit')->name('project.edit')->middleware('permission:all_project');
    Route::post('project/edit/{project}', 'ProjectController@editPost')->middleware('permission:all_project');
    Route::get('project-attachment/details/{project}', 'ProjectController@details')->name('project_attachment.details')->middleware('permission:all_project');
    Route::get('project-attachment1/details/{project}', 'ProjectController@details1')->name('project_attachment.details1')->middleware('permission:all_project');
    Route::get('project-attachment2/details/{project}', 'ProjectController@details2')->name('project_attachment.details2')->middleware('permission:all_project');
    Route::get('project-datatable', 'ProjectController@datatable')->name('project.datatable');

    // Segment
    Route::get('segment', 'SegmentController@index')->name('segment')->middleware('permission:all_project');
    Route::get('segment/add', 'SegmentController@add')->name('segment.add')->middleware('permission:all_project');
    Route::post('segment/add', 'SegmentController@addPost')->middleware('permission:all_project');
    Route::get('segment/edit/{segment}', 'SegmentController@edit')->name('segment.edit')->middleware('permission:all_project');
    Route::post('segment/edit/{segment}', 'SegmentController@editPost')->middleware('permission:all_project');
    Route::get('segment-datatable', 'SegmentController@datatable')->name('segment.datatable');

    // Segment
    Route::get('requisition', 'RequisitionController@index')->name('requisition')->middleware('permission:requisition_lists');
    Route::get('requisition/add', 'RequisitionController@add')->name('requisition.add')->middleware('permission:create_requisition');
    Route::post('requisition/add', 'RequisitionController@addPost')->middleware('permission:create_requisition');
    Route::get('requisition/approved/{requisition}', 'RequisitionController@requisitionApproved')->name('requisition.approved')->middleware('permission:requisition_lists');
    Route::post('requisition/approved/{requisition}', 'RequisitionController@requisitionApprovedPost')->middleware('permission:requisition_lists');
    Route::get('requisition/details/{requisition}', 'RequisitionController@requisitionReceiptDetails')->name('requisition.details')->middleware('permission:requisition_lists');
    Route::get('requisition-datatable', 'RequisitionController@datatable')->name('requisition.datatable');

    // Flat
    Route::post('flat_delete', 'FlatController@delete')->name('flat_delete')->middleware('permission:apartment_shop');
    Route::get('flat', 'FlatController@index')->name('flat')->middleware('permission:apartment_shop');
    Route::get('flat/add', 'FlatController@add')->name('flat.add')->middleware('permission:apartment_shop');
    Route::get('apartment/add', 'FlatController@ApartmentAdd')->name('apartment.add')->middleware('permission:apartment_shop');
    Route::post('flat/add', 'FlatController@addPost')->middleware('permission:apartment_shop');
    Route::get('flat/edit/{flat}', 'FlatController@edit')->name('flat.edit')->middleware('permission:apartment_shop');
    Route::post('flat/edit/{flat}', 'FlatController@editPost')->middleware('permission:apartment_shop');
    Route::get('flat-datatable', 'FlatController@datatable')->name('flat.datatable');
    Route::get('flat/get-flats', 'FlatController@getFlats')->name('flat.get_flat')->middleware('permission:apartment_shop');

    // Floor
    Route::get('floor', 'FloorController@index')->name('floor')->middleware('permission:floor');
    Route::get('floor/add', 'FloorController@add')->name('floor.add')->middleware('permission:floor');
    Route::post('floor/add', 'FloorController@addPost')->middleware('permission:floor');
    Route::get('floor/edit/{floor}', 'FloorController@edit')->name('floor.edit')->middleware('permission:floor');
    Route::post('floor/edit/{floor}', 'FloorController@editPost')->middleware('permission:floor');
    Route::post('floor-delete', 'FloorController@delete')->name('floor_delete')->middleware('permission:floor');
    Route::get('floor-datatable', 'FloorController@datatable')->name('floor.datatable');
    Route::get('floor/get-floors', 'FloorController@getFloors')->name('floor.get_floor');

    // Supplier
    Route::get('supplier', 'SupplierController@index')->name('supplier')->middleware('permission:supplier');
    Route::get('supplier/add', 'SupplierController@add')->name('supplier.add')->middleware('permission:supplier');
    Route::post('supplier/add', 'SupplierController@addPost')->middleware('permission:supplier');
    Route::get('supplier/edit/{client}', 'SupplierController@edit')->name('supplier.edit')->middleware('permission:supplier');
    Route::post('supplier/edit/{client}', 'SupplierController@editPost')->middleware('permission:supplier');
    Route::get('supplier/datatable', 'SupplierController@datatable')->name('supplier.datatable');

    // Purchase Product
    Route::get('purchase-product', 'PurchaseProductController@index')->name('purchase_product')->middleware('permission:product');
    Route::get('purchase-product/add', 'PurchaseProductController@add')->name('purchase_product.add')->middleware('permission:product');
    Route::post('purchase-product/add', 'PurchaseProductController@addPost')->middleware('permission:product');
    Route::get('purchase-product/edit/{product}', 'PurchaseProductController@edit')->name('purchase_product.edit')->middleware('permission:product');
    Route::post('purchase-product/edit/{product}', 'PurchaseProductController@editPost')->middleware('permission:product');

    // Purchase Order
    Route::get('purchase-order', 'PurchaseController@purchaseOrder')->name('purchase_order.create')->middleware('permission:realstate_purchase');
    Route::post('purchase-order', 'PurchaseController@purchaseOrderPost')->middleware('permission:realstate_purchase');

    Route::get('purchase-order-edit/{order}', 'PurchaseController@purchaseOrderEdit')->name('purchase_order_edit');
    Route::post('purchase-order-edit/{order}', 'PurchaseController@purchaseOrderEditPost');

    Route::get('purchase-product-json', 'PurchaseController@purchaseProductJson')->name('purchase_product.json');
    Route::get('estimate-product-json', 'PurchaseController@estimateProductJson')->name('estimate_product.json');
    Route::get('mobilization-work-product-json', 'PurchaseController@mobilizationProductJson')->name('mobilization_work_product.json');
    Route::get('costing-work-product-json', 'PurchaseController@costingProductJson')->name('extra_work_product.json');
    Route::post('purchase-order-delete', 'PurchaseController@purchaseOrderDelete')->name('purchase_order_delete'); // Purchase Receipt
    Route::get('purchase-receipt', 'PurchaseController@purchaseReceipt')->name('purchase_receipt.all')->middleware('permission:realstate_purchase');
    Route::get('purchase-receipt/details/{order}', 'PurchaseController@purchaseReceiptDetails')->name('purchase_receipt.details')->middleware('permission:realstate_purchase');
    Route::get('purchase-receipt/print/{order}', 'PurchaseController@purchaseReceiptPrint')->name('purchase_receipt.print')->middleware('permission:realstate_purchase');
    Route::get('purchase-receipt/datatable', 'PurchaseController@purchaseReceiptDatatable')->name('purchase_receipt.datatable');
    Route::post('purchase-receipt/receive', 'PurchaseController@orderReceive')->name('purchase_receipt.receive')->middleware('permission:realstate_purchase');
    Route::get('purchase-receipt/payment/details/{payment}', 'PurchaseController@purchasePaymentDetails')->name('purchase_receipt.payment_details')->middleware('permission:realstate_purchase');
    Route::get('purchase-receipt/payment/print/{payment}', 'PurchaseController@purchasePaymentPrint')->name('purchase_receipt.payment_print')->middleware('permission:realstate_purchase');

    // Supplier Payment
    Route::get('supplier-payment', 'PurchaseController@supplierPayment')->name('supplier_payment.all')->middleware('permission:realstate_purchase');
    Route::get('supplier-payment/get-orders', 'PurchaseController@supplierPaymentGetOrders')->name('supplier_payment.get_orders')->middleware('permission:realstate_purchase');
    Route::get('supplier-payment/get-refund-orders', 'PurchaseController@supplierPaymentGetRefundOrders')->name('supplier_payment.get_refund_orders')->middleware('permission:realstate_purchase');
    Route::get('supplier-payment/order-details', 'PurchaseController@supplierPaymentOrderDetails')->name('supplier_payment.order_details')->middleware('permission:realstate_purchase');
    Route::post('supplier-payment/payment', 'PurchaseController@makePayment')->name('supplier_payment.make_payment')->middleware('permission:realstate_purchase');
    Route::post('supplier-payment/delete', 'PurchaseController@supplierPaymentDelete')->name('supplier_payment_delete')->middleware('permission:realstate_purchase');
    Route::post('supplier-payment/refund', 'PurchaseController@makeRefund')->name('supplier_payment.make_refund')->middleware('permission:realstate_purchase');
    Route::get('supplier-payment-details/{client}', 'PurchaseController@supplierPaymentDetails')->name('supplier_payment_details')->middleware('permission:realstate_purchase');
    Route::get('supplier-payment-datatable', 'PurchaseController@supplierPaymentDatatable')->name('supplier_payment.datatable');
    Route::get('supplier-payment-edit/{payment}', 'PurchaseController@supplierPaymentEdit')->name('supplier_payment_edit')->middleware('permission:realstate_purchase');
    Route::post('supplier-payment-edit/{payment}', 'PurchaseController@supplierPaymentEditPost')->middleware('permission:realstate_purchase');
    // Purchase Inventory
    Route::get('purchase-inventory', 'PurchaseController@purchaseInventory')->name('purchase_inventory.all')->middleware('permission:realstate_purchase');
    Route::get('purchase-inventory/datatable', 'PurchaseController@purchaseInventoryDatatable')->name('purchase_inventory.datatable');
    Route::get('purchase-inventory/details/datatable', 'PurchaseController@purchaseInventoryDetailsDatatable')->name('purchase_inventory.details.datatable');
    Route::get('purchase-inventory/details/{project}/{product}', 'PurchaseController@purchaseInventoryDetails')->name('purchase_inventory.details')->middleware('permission:realstate_purchase');

    // Utilize Purchase Product
    Route::get('utilize/purchase-product', 'PurchaseController@utilizeIndex')->name('purchase_product.utilize.all')->middleware('permission:realstate_purchase');
    Route::get('utilize/purchase-product/datatable', 'PurchaseController@utilizeDatatable')->name('purchase_product.utilize.datatable');
    Route::get('utilize/purchase-product/add', 'PurchaseController@utilizeAdd')->name('purchase_product.utilize.add')->middleware('permission:realstate_purchase');
    Route::post('utilize/purchase-product/add', 'PurchaseController@utilizeAddPost')->middleware('permission:realstate_purchase');
    Route::get('product-get-purchase-order-no', 'PurchaseController@productGetPurchaseOrder')->name('product.get_purchase_order_no');
    Route::get('get-inventory-stock-details', 'PurchaseController@getInventoryStockDetails')->name('get_inventory_stock_details');



    //Normal Purchase Order

    Route::get('trade-purchase-order', 'TradingPurchaseController@tradingPurchaseOrder')->name('trading_purchase_order.create')->middleware('permission:normal_purchase');
    Route::post('trade-purchase-order', 'TradingPurchaseController@tradingPurchaseOrderPost')->middleware('permission:normal_purchase');

    Route::get('trade-purchase-order-edit/{order}', 'TradingPurchaseController@tradingPurchaseOrderEdit')->name('trading_purchase_order_edit');
    Route::post('trade-purchase-order-edit/{order}', 'TradingPurchaseController@tradingPurchaseOrderEditPost');

    Route::get('trade-purchase-product-json', 'TradingPurchaseController@tradingPurchaseProductJson')->name('trading_purchase_product.json');
    Route::get('estimate-product-json', 'TradingPurchaseController@tradingEstimateProductJson')->name('trading_estimate_product.json');
    Route::post('trade-purchase-order-delete', 'TradingPurchaseController@tradingPurchaseOrderDelete')->name('trading_purchase_order_delete');
    Route::get('trade-purchase-receipt', 'TradingPurchaseController@tradingPurchaseReceipt')->name('trading_purchase_receipt.all')->middleware('permission:normal_purchase');
    Route::get('trade-purchase-receipt/details/{order}', 'TradingPurchaseController@tradingPurchaseReceiptDetails')->name('trading_purchase_receipt.details')->middleware('permission:normal_purchase');
    Route::get('trade-purchase-receipt/print/{order}', 'TradingPurchaseController@tradingPurchaseReceiptPrint')->name('trading_purchase_receipt.print')->middleware('permission:normal_purchase');
    Route::get('trade-purchase-receipt/datatable', 'TradingPurchaseController@tradingPurchaseReceiptDatatable')->name('trading_purchase_receipt.datatable');
    Route::post('trade-purchase-receipt/receive', 'TradingPurchaseController@tradingOrderReceive')->name('trading_purchase_receipt.receive')->middleware('permission:normal_purchase');
    Route::get('trade-purchase-receipt/payment/details/{payment}', 'TradingPurchaseController@tradingPurchasePaymentDetails')->name('trading_purchase_receipt.payment_details')->middleware('permission:normal_purchase');
    Route::get('trade-purchase-receipt/payment/print/{payment}', 'TradingPurchaseController@tradingPurchasePaymentPrint')->name('trading_purchase_receipt.payment_print')->middleware('permission:normal_purchase');

    //Trading Sale Order
    Route::get('trading-sale-order-product', 'TradingSaleController@tradingSaleOrder')->name('trading_sales_order.create')->middleware('permission:trading_sales_order');
    Route::post('trading-sale-order-product', 'TradingSaleController@tradingSaleOrderPost')->middleware('permission:trading_sales_order');
    Route::get('stock-inventory-details', 'TradingSaleController@stockInventoryDetails')->name('product_stock_inventory');
    Route::get('trading-sales-receipt', 'TradingSaleController@TradingSalesReceipt')->name('trading_sales_receipt')->middleware('permission:trading_sales_receipt');
    Route::get('trading-sales-receipt-datatable', 'TradingSaleController@TradingSalesReceiptDatatable')->name('trading_sale_receipt.datatable');
    Route::get('trading-sales-receipt-details/{order}', 'TradingSaleController@tradingSaleReceiptDetails')->name('trading_sale_receipt.details')->middleware('permission:trading_sales_receipt');
    Route::get('trading-sales-receipt-details-print/{order}', 'TradingSaleController@tradingSaleReceiptDetailsPrint')->name('trading_sale_receipt.print')->middleware('permission:trading_sales_receipt');

    // Supplier Payment
    Route::get('trade-supplier-payment', 'TradingPurchaseController@supplierPayment')->name('trading_supplier_payment.all')->middleware('permission:normal_purchase');
    Route::get('trade-supplier-payment/get-orders', 'TradingPurchaseController@supplierPaymentGetOrders')->name('trading_supplier_payment.get_orders')->middleware('permission:normal_purchase');
    Route::get('trade-supplier-payment/get-refund-orders', 'TradingPurchaseController@supplierPaymentGetRefundOrders')->name('trading_supplier_payment.get_refund_orders')->middleware('permission:normal_purchase');
    Route::get('trade-supplier-payment/order-details', 'TradingPurchaseController@supplierPaymentOrderDetails')->name('trading_supplier_payment.order_details')->middleware('permission:normal_purchase');
    Route::post('trade-supplier-payment/payment', 'TradingPurchaseController@makePayment')->name('trading_supplier_payment.make_payment')->middleware('permission:normal_purchase');
    Route::post('trade-supplier-payment/delete', 'TradingPurchaseController@supplierPaymentDelete')->name('trading_supplier_payment_delete')->middleware('permission:normal_purchase');
    Route::post('trade-supplier-payment/refund', 'TradingPurchaseController@makeRefund')->name('trading_supplier_payment.make_refund')->middleware('permission:normal_purchase');
    Route::get('trade-supplier-payment-details/{client}', 'TradingPurchaseController@supplierPaymentDetails')->name('trading_supplier_payment_details')->middleware('permission:normal_purchase');
    Route::get('trade-supplier-payment-datatable', 'TradingPurchaseController@supplierPaymentDatatable')->name('trading_supplier_payment.datatable');
    Route::get('trade-supplier-payment-edit/{payment}', 'TradingPurchaseController@supplierPaymentEdit')->name('trading_supplier_payment_edit')->middleware('permission:normal_purchase');
    Route::post('trade-supplier-payment-edit/{payment}', 'TradingPurchaseController@supplierPaymentEditPost')->middleware('permission:normal_purchase');

    // Purchase Inventory
    Route::get('trading-purchase-inventory', 'TradingPurchaseController@purchaseInventory')->name('trading_purchase_inventory.all')->middleware('permission:normal_purchase');
    Route::get('trading-purchase-inventory/datatable', 'TradingPurchaseController@purchaseInventoryDatatable')->name('trading_purchase_inventory.datatable');
    Route::get('trading-purchase-inventory/details/datatable', 'TradingPurchaseController@purchaseInventoryDetailsDatatable')->name('trading_purchase_inventory.details.datatable');
    Route::get('trading-purchase-inventory/details/{project}/{product}', 'TradingPurchaseController@purchaseInventoryDetails')->name('trading_purchase_inventory.details')->middleware('permission:normal_purchase');

    // Utilize Purchase Product
    Route::get('utilize/purchase-product', 'PurchaseController@utilizeIndex')->name('purchase_product.utilize.all')->middleware('permission:realstate_purchase');
    Route::get('utilize/purchase-product/datatable', 'PurchaseController@utilizeDatatable')->name('purchase_product.utilize.datatable');
    Route::get('utilize/purchase-product/add', 'PurchaseController@utilizeAdd')->name('purchase_product.utilize.add')->middleware('permission:realstate_purchase');
    Route::post('utilize/purchase-product/add', 'PurchaseController@utilizeAddPost')->middleware('permission:realstate_purchase');
    Route::get('product-get-purchase-order-no', 'PurchaseController@productGetPurchaseOrder')->name('product.get_purchase_order_no');
    Route::get('get-inventory-stock-details', 'PurchaseController@getInventoryStockDetails')->name('get_inventory_stock_details');

    // Account Head Type
    Route::get('account-head/type', 'AccountsController@accountHeadType')->name('account_head.type')->middleware('permission:type');
    Route::get('account-head/type/add', 'AccountsController@accountHeadTypeAdd')->name('account_head.type.add')->middleware('permission:type');
    Route::post('account-head/type/add', 'AccountsController@accountHeadTypeAddPost')->middleware('permission:type');
    Route::get('account-head/type/edit/{type}', 'AccountsController@accountHeadTypeEdit')->name('account_head.type.edit')->middleware('permission:type');
    Route::post('account-head/type/edit/{type}', 'AccountsController@accountHeadTypeEditPost')->middleware('permission:type');

    // Account Head Type
    Route::get('account-head', [AccountHeadController::class,'accountHead'])->name('account_head')->middleware('permission:account_head');
    Route::get('account-head-datatable', [AccountHeadController::class,'datatable'])->name('account_head.datatable');
    Route::get('account-head/add', [AccountHeadController::class,'accountHeadAdd'])->name('account_head.add')->middleware('permission:account_head');
    Route::post('account-head/add', [AccountHeadController::class,'accountHeadAddPost'])->middleware('permission:account_head');
    Route::get('account-head/edit/{accountHead}', [AccountHeadController::class,'accountHeadEdit'])->name('account_head.edit')->middleware('permission:account_head');
    Route::post('account-head/edit/{accountHead}', [AccountHeadController::class,'accountHeadEditPost'])->middleware('permission:account_head');

    //payment-voucher
    Route::get('payment-voucher/create', [VoucherController::class,'create'])->name('voucher.create')->middleware('permission:create_payment_voucher');
    Route::post('payment-voucher/create', [VoucherController::class,'createPost'])->middleware('permission:create_payment_voucher');
    Route::get('payment-voucher-datatable', [VoucherController::class,'datatable'])->name('voucher.datatable');
    Route::get('payment-voucher', [VoucherController::class,'index'])->name('voucher')->middleware('permission:payment_voucher');
    Route::get('payment-voucher/edit/{receiptPayment}', [VoucherController::class,'edit'])->name('voucher.edit')->middleware('permission:payment_voucher');
    Route::post('payment-voucher/edit/{receiptPayment}', [VoucherController::class,'editPost'])->middleware('permission:payment_voucher');
    Route::get('payment-voucher/details/{receiptPayment}', [VoucherController::class,'details'])->name('voucher_details')->middleware('permission:payment_voucher');
    Route::get('payment-details-voucher/edit/{receiptPayment}', [ReceiptController::class,'voucherDetailsEdit'])->name('voucher_details.edit')->middleware('permission:payment_voucher');
    Route::post('payment-details-voucher/edit/{receiptPayment}', [ReceiptController::class,'voucherDetailsEditPost'])->middleware('permission:payment_voucher');
    Route::get('payment-voucher/print/{receiptPayment}', [VoucherController::class,'print'])->name('voucher_print')->middleware('permission:payment_voucher');

    //receipt-voucher
    Route::get('receipt-voucher/create', [ReceiptController::class,'create'])->name('receipt.create')->middleware('permission:create_receipt_voucher');
    Route::post('receipt-voucher/create', [ReceiptController::class,'createPost'])->middleware('permission:create_receipt_voucher');
    Route::get('receipt-voucher-datatable', [ReceiptController::class,'datatable'])->name('receipt.datatable');
    Route::get('receipt_sale_payment-voucher-datatable', [ReceiptController::class,'salePaymentDatatable'])->name('receipt_sale_payment.datatable');
    Route::get('purchase-voucher-datatable', [ReceiptController::class,'purchasePaymentDatatable'])->name('purchase_payment.datatable');
    Route::get('receipt-voucher', [ReceiptController::class,'index'])->name('receipt')->middleware('permission:receipt_voucher');
    Route::get('receipt-voucher/edit/{receiptPayment}', [ReceiptController::class,'edit'])->name('receipt.edit')->middleware('permission:receipt_voucher');
    Route::post('receipt-voucher/edit/{receiptPayment}', [ReceiptController::class,'editPost'])->middleware('permission:receipt_voucher');
    Route::get('receipt-voucher/details/{receiptPayment}', [ReceiptController::class,'details'])->name('receipt_details')->middleware('permission:receipt_voucher');
    Route::get('receipt-details-voucher/edit/{receiptPayment}', [ReceiptController::class,'receiptDetailsEdit'])->name('receipt_details.edit')->middleware('permission:receipt_voucher');
    Route::post('receipt-details-voucher/edit/{receiptPayment}', [ReceiptController::class,'receiptDetailsEditPost'])->middleware('permission:receipt_voucher');
    Route::post('receipt-details-voucher/delete', [ReceiptController::class,'receiptDetailsDelete'])->name('receipt_details.delete')->middleware('permission:receipt_voucher');
    Route::get('receipt-voucher/print/{receiptPayment}', [ReceiptController::class,'print'])->name('receipt_print')->middleware('permission:receipt_voucher');

    //Journal
    Route::get('journal-voucher/create', [JournalVoucherController::class,'create'])->name('journal_voucher.create');
    Route::post('journal-voucher/create', [JournalVoucherController::class,'createPost']);
    Route::get('journal-voucher-datatable', [JournalVoucherController::class,'datatable'])->name('journal_voucher.datatable');
    Route::get('journal-voucher', [JournalVoucherController::class,'index'])->name('journal_voucher');
    Route::get('journal-voucher/edit/{journalVoucher}', [JournalVoucherController::class,'edit'])->name('journal_voucher.edit');
    Route::post('journal-voucher/edit/{journalVoucher}', [JournalVoucherController::class,'editPost']);
    Route::get('journal-voucher/details/{journalVoucher}', [JournalVoucherController::class,'details'])->name('journal_voucher_details');
    Route::get('journal-voucher/print/{journalVoucher}', [JournalVoucherController::class,'print'])->name('journal_voucher_print');


    //balance-transfer
    Route::get('balance-transfer/edit/{balanceTransfer}', [BalanceTransferController::class,'balanceTransferEdit'])->name('balance_transfer.edit')->middleware('permission:balance_transfer');
    Route::post('balance-transfer/edit/{balanceTransfer}', [BalanceTransferController::class,'balanceTransferEditPost'])->middleware('permission:balance_transfer');
    Route::get('balance-transfer/add', [BalanceTransferController::class,'balanceTransferAdd'])->name('balance_transfer.add')->middleware('permission:balance_transfer');
    Route::post('balance-transfer/add', [BalanceTransferController::class,'balanceTransferAddPost'])->middleware('permission:balance_transfer');
    Route::get('balance-transfer', [BalanceTransferController::class,'balanceTransferIndex'])->name('balance_transfer')->middleware('permission:balance_transfer');
    Route::get('balance-transfer-datatable', [BalanceTransferController::class,'balanceTransferDatatable'])->name('balance_transfer.datatable')->middleware('permission:balance_transfer');
    Route::get('balance-transfer-voucher/details/{balanceTransfer}', [BalanceTransferController::class,'voucherDetails'])->name('balance_transfer_voucher_details')->middleware('permission:balance_transfer');
    Route::get('balance-transfer-voucher/print/{balanceTransfer}', [BalanceTransferController::class,'voucherPrint'])->name('balance_transfer_voucher_print')->middleware('permission:balance_transfer');
    Route::get('balance-transfer-receipt/details/{balanceTransfer}', [BalanceTransferController::class,'receiptDetails'])->name('balance_transfer_receipt_details')->middleware('permission:balance_transfer');
    Route::get('balance-transfer-receipt/print/{balanceTransfer}', [BalanceTransferController::class,'receiptPrint'])->name('balance_transfer_receipt_print')->middleware('permission:balance_transfer');


    //Report
    Route::get('report/ledger', [ReportController::class,'ledger'])->name('report.ledger')->middleware('permission:ledger');
    Route::get('report/trial-balance', [ReportController::class,'trailBalance'])->name('report.trail_balance')->middleware('permission:trial_balance');



    Route::get('payee-json', [CommonController::class,'payeeJson'])->name('payee_json');
    Route::get('account-head-code-json', [CommonController::class,'accountHeadCodeJson'])->name('account_head_code.json');
    Route::get('account-head-code-expense-json', [CommonController::class,'accountHeadCodeExpenseJson'])->name('account_head_code_expense.json');
    Route::get('account-head-code-income-json', [CommonController::class,'accountHeadCodeIncomeJson'])->name('account_head_code_income.json');

    // Client
    Route::post('client-delete', 'ClientController@delete')->name('client_delete')->middleware('permission:client_profile');
    Route::get('client', 'ClientController@index')->name('client')->middleware('permission:client_profile');
    Route::get('client/add', 'ClientController@add')->name('client.add')->middleware('permission:client_profile');
    Route::post('client/add', 'ClientController@addPost')->middleware('permission:client_profile');
    Route::get('client/edit/{client}', 'ClientController@edit')->name('client.edit')->middleware('permission:client_profile');
    Route::post('client/edit/{client}', 'ClientController@editPost')->middleware('permission:client_profile');
    Route::get('client/datatable', 'ClientController@datatable')->name('client.datatable');
    Route::get('client/profile/{profile}', 'ClientController@profile')->name('client.profile')->middleware('permission:client_profile');

    // Sale Product
    Route::get('sale-product', 'SaleProductController@index')->name('sale_product');
    Route::get('sale-product/add', 'SaleProductController@add')->name('sale_product.add');
    Route::post('sale-product/add', 'SaleProductController@addPost');
    Route::get('sale-product/edit/{product}', 'SaleProductController@edit')->name('sale_product.edit');
    Route::post('sale-product/edit/{product}', 'SaleProductController@editPost');

    //Scrap Product
    Route::get('scrap-product-all', 'SaleProductController@scrapProduct')->name('scrap_product.all')->middleware('permission:scrap_product');
    Route::get('scrap-product/add', 'SaleProductController@scrapProductAdd')->name('scrap_product.add')->middleware('permission:scrap_product');
    Route::post('scrap-product/add', 'SaleProductController@scrapProductAddPost')->middleware('permission:scrap_product');
    Route::get('scrap-product/datatable', 'SaleProductController@scrapProductDatatable')->name('scrap_product.datatable');

    //Scrap Sale
    Route::get('scrap-sale/create', 'SaleController@scrapSaleCreate')->name('scrap_sale.create')->middleware('permission:scrap_sale');
    Route::post('scrap-sale/create', 'SaleController@scrapSalePost')->middleware('permission:scrap_sale');

    //Project Wise Client
    Route::get('project-wise-client', 'SaleController@projectWiseClient')->name('project_wise_client')->middleware('permission:project_wise_client');


    // Sale Receipt
    Route::get('scrap-sale-receipt', 'SaleController@scrapSaleReceipt')->name('scrap_sale_receipt.all')->middleware('permission:scrap_sales_receipt');
    Route::get('scrap-sale-receipt/details/{order}', 'SaleController@scrapSaleReceiptDetails')->name('scrap_sale_receipt.details')->middleware('permission:scrap_sales_receipt');
    Route::get('scrap-sale-receipt/print/{order}', 'SaleController@scrapSaleReceiptPrint')->name('scrap_sale_receipt.print')->middleware('permission:scrap_sales_receipt');
    Route::get('scrap-sale-receipt/datatable', 'SaleController@scrapSaleReceiptDatatable')->name('scrap_sale_receipt.datatable');
    Route::get('/get-scrap-data', 'SaleController@getScrapData')->name('get_scrap_data');
    Route::post('/post-scrap-data', 'SaleController@scrapMakePayment')->name('get_scrap_data_post');


    // Sales Order
    Route::get('sales-order', 'SaleController@salesOrder')->name('sales_order.create')->middleware('permission:create_sales_order');
    Route::post('sales-order', 'SaleController@salesOrderPost')->middleware('permission:create_sales_order');
    Route::get('sale-product-json', 'SaleController@saleProductJson')->name('sale_product.json');
    Route::get('sale-order/product/details', 'SaleController@saleProductDetails')->name('sale_product.details');
    Route::get('sale-order/get-flats', 'SaleController@getFlat')->name('sale.get_flat');
    Route::get('sale-order/get-all-flats', 'SaleController@getAllFlat')->name('sale.get_all_flat');
    Route::get('sales-order-edit/{order}', 'SaleController@salesOrderEdit')->name('sales_order_edit')->middleware('permission:create_sales_order');
    Route::post('sales-order-edit/{order}', 'SaleController@salesOrderEditPost')->middleware('permission:create_sales_order');
    Route::post('sales-order-delete', 'SaleController@salesOrderDelete')->name('sales_order_delete')->middleware('permission:create_sales_order');

    // Sale Receipt
    Route::get('sale-receipt', 'SaleController@saleReceipt')->name('sale_receipt.all')->middleware('permission:sales_system_receipt');
    Route::get('sale-receipt/details/{order}', 'SaleController@transactionDetails')->name('sale_receipt.details')->middleware('permission:sales_system_receipt');
    Route::get('sale-receipt/print/{order}', 'SaleController@saleReceiptPrint')->name('sale_receipt.print')->middleware('permission:sales_system_receipt');
    Route::get('sale-receipt/datatable', 'SaleController@saleReceiptDatatable')->name('sale_receipt.datatable');
    Route::post('sale-receipt/delivery', 'SaleController@orderDelivery')->name('sale_receipt.delivery')->middleware('permission:sales_system_receipt');
    Route::get('sale-receipt/payment/details/{payment}', 'SaleController@salePaymentDetails')->name('sale_receipt.payment_details')->middleware('permission:sales_system_receipt');
    Route::get('sale-receipt/payment/print/{payment}', 'SaleController@salePaymentPrint')->name('sale_receipt.payment_print')->middleware('permission:sales_system_receipt');
    Route::get('sale-receipt/money-receipt/print/{payment}', 'SaleController@saleMoneyReceiptPrint')->name('money_receipt.payment_print')->middleware('permission:sales_system_receipt');

    // Client Payment
    Route::get('client-payment', 'SaleController@clientPayment')->name('client_payment.all')->middleware('permission:client_payment_record');
    Route::get('client-payment/datatable', 'SaleController@clientPaymentDatatable')->name('client_payment.datatable');
    Route::get('client-payment/get-orders', 'SaleController@clientPaymentGetOrders')->name('client_payment.get_orders')->middleware('permission:client_payment_record');
    Route::get('customer-payment/get-refund-orders', 'SaleController@customerPaymentGetRefundOrders')->name('customer_payment.get_refund_orders')->middleware('permission:client_payment_record');
    Route::get('client-payment/order-details', 'SaleController@clientPaymentOrderDetails')->name('client_payment.order_details')->middleware('permission:client_payment_record');
    Route::get('client-payment/order-payment-step', 'SaleController@clientOrderPaymentStep')->name('client_payment.payment_step')->middleware('permission:client_payment_record');
    Route::get('client-payment/order-payment-step', 'SaleController@clientOrderPaymentStep')->name('client_payment.payment_step')->middleware('permission:client_payment_record');
    Route::post('client-payment/payment', 'SaleController@makePayment')->name('client_payment.make_payment')->middleware('permission:client_payment_record');
    Route::post('client-payment/delete', 'SaleController@clientPaymentDelete')->name('client_payment_delete')->middleware('permission:client_payment_record');
    Route::get('client-payment-details/{client}', 'SaleController@clientPaymentDetails')->name('client_payment_details')->middleware('permission:client_payment_record');
    Route::get('sale-delivery-info', 'SaleController@saleDeliveryInfo')->name('sale_delivery_info')->middleware('permission:client_payment_record');
    Route::post('sales-delivery-log', 'SaleController@salesDeliveryLog')->name('sales_delivery_log')->middleware('permission:client_payment_record');
    Route::get('client-payment-edit/{payment}', 'SaleController@clientPaymentEdit')->name('client_payment_edit')->middleware('permission:client_payment_record');
    Route::post('client-payment-edit/{payment}', 'SaleController@clientPaymentEditPost')->name('client_payment.make_payment_edit')->middleware('permission:client_payment_record');
    Route::post('customer-payment/refund', 'SaleController@customerMakeRefund')->name('customer_payment.make_refund')->middleware('permission:client_payment_record');

    // Sale Product Add to Stock
    Route::get('sale-product/stock', 'SaleController@stockIndex')->name('sale_product.stock.all');
    Route::get('sale-product/stock/datatable', 'SaleController@stockDatatable')->name('sale_product.stock.datatable');
    Route::get('sale-product/stock/add', 'SaleController@stockAdd')->name('sale_product.stock.add');
    Route::post('sale-product/stock/add', 'SaleController@stockAddPost');

    // Sale Inventory
    Route::get('sale-inventory', 'SaleController@saleInventory')->name('sale_inventory.all')->middleware('permission:sale_inventory');
    Route::get('sale-inventory/datatable', 'SaleController@saleInventoryDatatable')->name('sale_inventory.datatable');
    Route::get('sale-inventory/details/datatable', 'SaleController@saleInventoryDetailsDatatable')->name('sale_inventory.details.datatable')->middleware('permission:sale_inventory');
    Route::get('sale-inventory/details/{product}', 'SaleController@saleInventoryDetails')->name('sale_inventory.details')->middleware('permission:sale_inventory');
    Route::get('sale-inventory/floor-wise/view/details', 'SaleController@saleInventoryFloorWiseView')->name('sale_inventory.floor_wise_view')->middleware('permission:sale_inventory_view');

    // Department
    Route::get('department', 'DepartmentController@index')->name('department')->middleware('permission:department');
    Route::get('department/add', 'DepartmentController@add')->name('department.add')->middleware('permission:department');
    Route::post('department/add', 'DepartmentController@addPost')->middleware('permission:department');
    Route::get('department/edit/{department}', 'DepartmentController@edit')->name('department.edit')->middleware('permission:department');
    Route::post('department/edit/{department}', 'DepartmentController@editPost')->middleware('permission:department');

    // Warehouse
    Route::get('warehouse', 'WarehouseController@index')->name('warehouse.all')->middleware('permission:warehouses');
    Route::get('warehouse/add', 'WarehouseController@add')->name('warehouse.add')->middleware('permission:warehouses');
    Route::post('warehouse/add', 'WarehouseController@addPost')->middleware('permission:warehouses');
    Route::get('warehouse/edit/{id}', 'WarehouseController@edit')->name('warehouse.edit')->middleware('permission:warehouses');
    Route::post('warehouse/edit/{warehouse}', 'WarehouseController@editPost')->middleware('permission:warehouses');
    Route::get('warehouse/delete/{id}', 'WarehouseController@delete')->name('warehoues_delete');

    // Designation
    Route::get('designation', 'DesignationController@index')->name('designation')->middleware('permission:designation');
    Route::get('designation/add', 'DesignationController@add')->name('designation.add')->middleware('permission:designation');
    Route::post('designation/add', 'DesignationController@addPost')->middleware('permission:designation');
    Route::get('designation/edit/{designation}', 'DesignationController@edit')->name('designation.edit')->middleware('permission:designation');
    Route::post('designation/edit/{designation}', 'DesignationController@editPost')->middleware('permission:designation');

    // HR
    Route::get('employee', 'HRController@employeeIndex')->name('employee.all')->middleware('permission:employee');
    Route::get('employee/datatable', 'HRController@employeeDatatable')->name('employee.datatable');
    Route::get('employee/add', 'HRController@employeeAdd')->name('employee.add')->middleware('permission:employee');
    Route::post('employee/add', 'HRController@employeeAddPost')->middleware('permission:employee');
    Route::get('employee/edit/{employee}', 'HRController@employeeEdit')->name('employee.edit')->middleware('permission:employee');
    Route::post('employee/edit/{employee}', 'HRController@employeeEditPost')->middleware('permission:employee');
    Route::get('employee/details/{employee}', 'HRController@employeeDetails')->name('employee.details')->middleware('permission:employee');
    Route::post('employee/designation/update', 'HRController@employeeDesignationUpdate')->name('employee.designation_update')->middleware('permission:employee');
    Route::get('employee/attendance', 'HRController@employeeAttendance')->name('employee.attendance')->middleware('permission:employee_attendance');
    Route::post('employee/attendance', 'HRController@employeeAttendancePost')->middleware('permission:employee_attendance');

    Route::post('payroll/get-leave', 'HRController@getLeave')->name('employee.get_leaves');

    //Employee Attendances
    Route::get('employees/attendance', 'EmployeeAttendanceController@index')->name('employees.attendance');
    Route::post('employees/attendance', 'EmployeeAttendanceController@singleEmployeeAttendancePost');
    Route::get('employees/password-change', 'EmployeeAttendanceController@employeePasswordChange')->name('employee_password_change');
    Route::post('employees/password-change', 'EmployeeAttendanceController@employeePasswordChangePost');

    // Labour Designation
    Route::get('labour-designation', 'LabourDesignationController@index')->name('labour_designation.all')->middleware('permission:labour_designation');
    Route::get('labour-designation/add', 'LabourDesignationController@add')->name('labour_designation.add')->middleware('permission:labour_designation');
    Route::post('labour-designation/add', 'LabourDesignationController@addPost')->middleware('permission:labour_designation');
    Route::get('labour-designation/edit/{labourDesignation}', 'LabourDesignationController@edit')->name('labour_designation.edit')->middleware('permission:labour_designation');
    Route::post('labour-designation/edit/{labourDesignation}', 'LabourDesignationController@editPost')->middleware('permission:labour_designation');


    // Labour Department
    Route::get('labour', 'LabourController@labourIndex')->name('labour.all')->middleware('permission:labour');
    Route::get('labour/datatable', 'LabourController@labourDatatable')->name('labour.datatable');
    Route::get('labour/add', 'LabourController@labourAdd')->name('labour.add')->middleware('permission:labour');
    Route::post('labour/add', 'LabourController@labourAddPost')->middleware('permission:labour');
    Route::get('labour/edit/{labour}', 'LabourController@labourEdit')->name('labour.edit')->middleware('permission:labour');
    Route::post('labour/edit/{labour}', 'LabourController@labourEditPost')->middleware('permission:labour');

    Route::post('labour/bonus/edit', 'LabourController@labourBonusEditPost')->name('labour.bonus.edit.post');
    Route::get('labour/details/{labour}', 'LabourController@labourDetails')->name('labour.details');
    Route::get('labour-list', 'LabourController@labourEmployeeList')->name('labour_list')->middleware('permission:labour_list');
    Route::post('labour/designation/update', 'LabourController@labourDesignationUpdate')->name('labour.designation_update');
    Route::get('labour/attendance', 'LabourController@labourAttendance')->name('labour.attendance')->middleware('permission:labour_attendance');
    Route::post('labour/attendance', 'LabourController@labourAttendancePost');
    Route::get('labour/attendance/report', 'LabourController@LabourEmployeeAttendance')->name('labour_employee_attendance.report')->middleware('permission:labour_attendance_report');
    Route::get('labour/labour-details', 'LabourController@getLabourDetails')->name('get_labour_details');


    //Labour Food Costing
    Route::get('labour/food-cost', 'FoodCostController@index')->name('labour.food_cost')->middleware('permission:labour_advance_amount');
    Route::get('labour/food-cost/add', 'FoodCostController@add')->name('labour.food_cost.add')->middleware('permission:labour_advance_amount');
    Route::post('labour/food-cost/add', 'FoodCostController@addPost')->middleware('permission:labour_advance_amount');
    Route::get('labour/food-cost/edit/{foodCost}', 'FoodCostController@edit')->name('labour.food_cost.edit')->middleware('permission:labour_advance_amount');
    Route::post('labour/food-cost/edit/{foodCost}', 'FoodCostController@editPost')->middleware('permission:labour_advance_amount');
    Route::get('labour-datatable', 'FoodCostController@foodCostDatatable')->name('food_cost.datatable');
    Route::get('labour/food-cost/details/{foodCost}', 'FoodCostController@details')->name('food_cost.details')->middleware('permission:labour_advance_amount');


    //Labour Bill
    Route::get('labour/bill', 'LabourBillController@index')->name('labour.bill')->middleware('permission:labour_bill_process');
    Route::get('labour/bill/add', 'LabourBillController@add')->name('labour.bill.add')->middleware('permission:labour_bill_process');
    Route::post('labour/bill/add', 'LabourBillController@addPost')->middleware('permission:labour_bill_process');
    Route::get('labour/bill/edit/{labourBill}', 'LabourBillController@edit')->name('labour.bill.edit')->middleware('permission:labour_bill_process');
    Route::post('labour/bill/edit/{labourBill}', 'LabourBillController@editPost')->middleware('permission:labour_bill_process');
    Route::get('labour/bill/datatable', 'LabourBillController@labourBillDatatable')->name('labour.bill.datatable');
    Route::get('labour/bill/details/{labourBill}', 'LabourBillController@details')->name('labour.bill.details')->middleware('permission:labour_bill_process');

    // Payroll - Salary Update
    Route::get('payroll/salary-update', 'PayrollController@salaryUpdateIndex')->name('payroll.salary_update.index')->middleware('permission:salary_update');
    Route::post('payroll/salary-update/update', 'PayrollController@salaryUpdatePost')->name('payroll.salary_update.post')->middleware('permission:salary_update');
    Route::get('payroll/salary-update/datatable', 'PayrollController@salaryUpdateDatatable')->name('payroll.salary_update.datatable');
    Route::post('employee/salary-advance', 'PayrollController@employeeSalaryAdvancePost')->name('employee.salary_advance_post')->middleware('permission:salary_update');
    Route::get('employee/salary-advance/receipt/{employeeSalaryAdvance}', 'PayrollController@employeeSalaryAdvanceReceipt')->name('employee.salary_advance_receipt')->middleware('permission:salary_update');

    // Payroll - Salary Process
    Route::get('payroll/salary-process', 'PayrollController@salaryProcessIndex')->name('payroll.salary_process.index')->middleware('permission:salary_process');
    Route::post('payroll/salary-process', 'PayrollController@salaryProcessPost')->middleware('permission:salary_process');

    // Payroll - Leave
    Route::get('payroll/leave', 'PayrollController@leaveIndex')->name('payroll.leave.index')->middleware('permission:leave');
    Route::get('payroll/leave/all', 'PayrollController@leaveAll')->name('payroll.leave.all')->middleware('permission:leave');
    Route::post('payroll/leave', 'PayrollController@leavePost')->middleware('permission:leave');

    // Payroll - holiday
    Route::get('payroll/holiday', 'PayrollController@holidayIndex')->name('payroll.holiday.index')->middleware('permission:holiday');
    Route::get('payroll/holiday/add', 'PayrollController@holidayAdd')->name('payroll.holiday_add')->middleware('permission:holiday');
    Route::post('payroll/holiday/add', 'PayrollController@holidayPost')->middleware('permission:holiday');
    Route::get('payroll/holiday/edit/{holiday}', 'PayrollController@holidayEdit')->name('payroll.holiday_edit')->middleware('permission:holiday');
    Route::post('payroll/holiday/edit/{holiday}', 'PayrollController@holidayEditPost')->middleware('permission:holiday');
    Route::get('payroll/holiday-datatable', 'PayrollController@holidayDatatable')->name('holiday.datatable');

    //Estimation & Costing

    //Estimate Project
    Route::get('estimate-project', 'EstimateProjectController@index')->name('estimate_project')->middleware('permission:estimate_project');
    Route::get('estimate-project/add', 'EstimateProjectController@add')->name('estimate_project.add')->middleware('permission:estimate_project');
    Route::post('estimate-project/add', 'EstimateProjectController@addPost')->middleware('permission:estimate_project');
    Route::get('estimate-project/edit/{project}', 'EstimateProjectController@edit')->name('estimate_project.edit')->middleware('permission:estimate_project');
    Route::post('estimate-project/edit/{project}', 'EstimateProjectController@editPost')->middleware('permission:estimate_project');
    Route::get('estimate-project-datatable', 'EstimateProjectController@datatable')->name('estimate_project.datatable');

    // Estimate Floor
    Route::get('estimate-floor', [EstimateFloorController::class, 'index'])->name('estimate_floor')->middleware('permission:estimate_floor');
    Route::get('estimate-floor/add', [EstimateFloorController::class, 'add'])->name('estimate_floor.add')->middleware('permission:estimate_floor');
    Route::post('estimate-floor/add', [EstimateFloorController::class, 'addPost'])->middleware('permission:estimate_floor');
    Route::get('estimate-floor/edit/{estimateFloor}', [EstimateFloorController::class, 'edit'])->name('estimate_floor.edit')->middleware('permission:estimate_floor');
    Route::post('estimate-floor/edit/{estimateFloor}', [EstimateFloorController::class, 'editPost'])->middleware('permission:estimate_floor');
    Route::get('estimate-floor-datatable', [EstimateFloorController::class, 'datatable'])->name('estimate_floor.datatable');
    Route::get('estimate-floor/get-floors', [EstimateFloorController::class, 'getFloors'])->name('estimate_floor.get_floor')->middleware('permission:estimate_floor');
    // Estimate Floor Configure
    Route::get('estimate-floor-configure', [EstimateFloorController::class, 'floorConfigureIndex'])->name('estimate_floor_configure')->middleware('permission:estimate_floor');
    Route::get('estimate-floor/add', [EstimateFloorController::class, 'add'])->name('estimate_floor.add')->middleware('permission:estimate_floor');
    Route::post('estimate-floor/add', [EstimateFloorController::class, 'addPost'])->middleware('permission:estimate_floor');
    Route::get('estimate-floor/edit/{estimateFloor}', [EstimateFloorController::class, 'edit'])->name('estimate_floor.edit')->middleware('permission:estimate_floor');
    Route::post('estimate-floor/edit/{estimateFloor}', [EstimateFloorController::class, 'editPost'])->middleware('permission:estimate_floor');
    Route::get('estimate-floor-datatable', [EstimateFloorController::class, 'datatable'])->name('estimate_floor.datatable');
    Route::get('estimate-floor/get-floors', [EstimateFloorController::class, 'getFloors'])->name('estimate_floor.get_floor')->middleware('permission:estimate_floor');

    // Estimate Floor Unit
    Route::get('estimate-floor-unit', [EstimateFloorController::class, 'unit'])->name('estimate_floor_unit')->middleware('permission:floor_unit');
    Route::get('estimate-floor-unit/add', [EstimateFloorController::class, 'unitAdd'])->name('estimate_floor_unit.add')->middleware('permission:floor_unit');
    Route::post('estimate-floor-unit/add', [EstimateFloorController::class, 'unitAddPost'])->middleware('permission:floor_unit');
    Route::get('estimate-floor-unit/edit/{estimateFloorUnit}', [EstimateFloorController::class, 'unitEdit'])->name('estimate_floor_unit.edit')->middleware('permission:floor_unit');
    Route::post('estimate-floor-unit/edit/{estimateFloorUnit}', [EstimateFloorController::class, 'unitEditPost'])->middleware('permission:floor_unit');
    Route::get('estimate-floor-unit-datatable', [EstimateFloorController::class, 'unitDatatable'])->name('estimate_floor_unit.datatable');


    // Estimate Floor Unit
    Route::get('unit-section', [EstimateFloorController::class, 'unitSection'])->name('unit_section')->middleware('permission:unit_section');
    Route::get('unit-section/add', [EstimateFloorController::class, 'unitSectionAdd'])->name('unit_section.add')->middleware('permission:unit_section');
    Route::post('unit-section/add', [EstimateFloorController::class, 'unitSectionAddPost'])->middleware('permission:unit_section');
    Route::get('unit-section/edit/{unitSection}', [EstimateFloorController::class, 'unitSectionEdit'])->name('unit_section.edit')->middleware('permission:unit_section');
    Route::post('unit-section/edit/{unitSection}', [EstimateFloorController::class, 'unitSectionEditPost'])->middleware('permission:unit_section');
    Route::get('unit-section-datatable', [EstimateFloorController::class, 'unitSectionDatatable'])->name('unit_section.datatable');

    //Costing Segment
    Route::get('costing-segment', [CostingSegmentController::class, 'index'])->name('costing_segment')->middleware('permission:costing_segment');
    Route::get('costing-segment/add', [CostingSegmentController::class, 'add'])->name('costing_segment.add')->middleware('permission:costing_segment');
    Route::post('costing-segment/add', [CostingSegmentController::class, 'addPost'])->middleware('permission:costing_segment');
    Route::get('costing-segment/edit/{costingSegment}', [CostingSegmentController::class, 'edit'])->name('costing_segment.edit')->middleware('permission:costing_segment');
    Route::post('costing-segment/edit/{costingSegment}', [CostingSegmentController::class, 'editPost'])->middleware('permission:costing_segment');
    Route::get('costing-segment-datatable', [CostingSegmentController::class, 'datatable'])->name('costing_segment.datatable');

    //Estimate Product
    Route::get('estimate-product', 'EstimateProductController@index')->name('estimate_product')->middleware('permission:estimate_product');
    Route::get('estimate-product/add', 'EstimateProductController@add')->name('estimate_product.add')->middleware('permission:estimate_product');
    Route::post('estimate-product/add', 'EstimateProductController@addPost')->middleware('permission:estimate_product');
    Route::get('estimate-product/edit/{product}', 'EstimateProductController@edit')->name('estimate_product.edit')->middleware('permission:estimate_product');
    Route::post('estimate-product/edit/{product}', 'EstimateProductController@editPost')->middleware('permission:estimate_product');


    //Estimate Product
    Route::get('estimate-product-type', [EstimateProductController::class, 'estimateProductType'])->name('estimate_product_type');
    Route::get('estimate-product-type/add', [EstimateProductController::class, 'estimateProductTypeAdd'])->name('estimate_product_type.add');
    Route::post('estimate-product-type/add', [EstimateProductController::class, 'estimateProductTypeAddPost']);
    Route::get('estimate-product-type/edit/{estimateProductType}', [EstimateProductController::class, 'estimateProductTypeAddEdit'])->name('estimate_product_type.edit');
    Route::post('estimate-product-type/edit/{estimateProductType}', [EstimateProductController::class, 'estimateProductTypeAddEditPost']);

    //Beam Type
    Route::get('beam-type', [EstimateTypeController::class, 'beamType'])->name('beam_type')->middleware('permission:beam_type');
    Route::get('beam-type/add', [EstimateTypeController::class, 'beamTypeAdd'])->name('beam_type.add')->middleware('permission:beam_type');
    Route::post('beam-type/add', [EstimateTypeController::class, 'beamTypeAddPost'])->middleware('permission:beam_type');
    Route::get('beam-type/edit/{beamType}', [EstimateTypeController::class, 'beamTypeEdit'])->name('beam_type.edit')->middleware('permission:beam_type');
    Route::post('beam-type/edit/{beamType}', [EstimateTypeController::class, 'beamTypeEditPost'])->middleware('permission:beam_type');

    //Column Type
    Route::get('column-type', [EstimateTypeController::class, 'columnType'])->name('column_type')->middleware('permission:column_type');
    Route::get('column-type/add', [EstimateTypeController::class, 'columnTypeAdd'])->name('column_type.add')->middleware('permission:column_type');
    Route::post('column-type/add', [EstimateTypeController::class, 'columnTypeAddPost'])->middleware('permission:column_type');
    Route::get('column-type/edit/{columnType}', [EstimateTypeController::class, 'columnTypeEdit'])->name('column_type.edit')->middleware('permission:column_type');
    Route::post('column-type/edit/{columnType}', [EstimateTypeController::class, 'columnTypeEditPost'])->middleware('permission:column_type');

    //Grade of Concrete Type
    Route::get('grade-of-concrete-type', [GradeOfConcreteTypeController::class, 'gradeOfConcreteType'])->name('grade_of_concrete_type')->middleware('permission:grade_of_concrete_type');
    Route::get('grade/beam/type/configure', [GradeOfConcreteTypeController::class, 'gradeBeanTypeConfigure'])->name('grade_beam_type_configure')->middleware('permission:grade_of_concrete_type');
    Route::get('grade/beam/type/configure/add', [GradeOfConcreteTypeController::class, 'gradeBeanTypeConfigureAdd'])->name('grade_beam_type_configure_add')->middleware('permission:grade_of_concrete_type');
    Route::post('grade/beam/type/configure/add', [GradeOfConcreteTypeController::class, 'gradeBeamConfigureAddPost'])->middleware('permission:grade_of_concrete_type');
    Route::get('grade-beam-configure-datatable', [GradeOfConcreteTypeController::class, 'gradeBeamConfigureDatatable'])->name('grade_beam_configure.datatable');
    Route::get('grade-beam-configure-details/{beamConfigure}', [GradeOfConcreteTypeController::class, 'gradeBeamConfigureDetails'])->name('grade_beam_configure.details')->middleware('permission:beam_configure');
    Route::get('grade-beam-configure-print/{beamConfigure}', [GradeOfConcreteTypeController::class, 'gradeBeamConfigurePrint'])->name('grade_beam_configure.print')->middleware('permission:beam_configure');

    Route::get('grade-of-concrete-type/add', [GradeOfConcreteTypeController::class, 'gradeOfConcreteTypeAdd'])->name('grade_of_concrete_type.add')->middleware('permission:grade_of_concrete_type');
    Route::post('grade-of-concrete-type/add', [GradeOfConcreteTypeController::class, 'gradeOfConcreteTypeAddPost'])->middleware('permission:grade_of_concrete_type');
    Route::get('grade-of-concrete-type/edit/{gradeOfConcrete}', [GradeOfConcreteTypeController::class, 'gradeOfConcreteTypeEdit'])->name('grade_of_concrete_type.edit')->middleware('permission:grade_of_concrete_type');
    Route::Post('grade-of-concrete-type/edit/{gradeOfConcrete}', [GradeOfConcreteTypeController::class, 'gradeOfConcreteTypeEditPost'])->middleware('permission:grade_of_concrete_type');

    //Batch
    Route::get('batch', [BatchController::class, 'batch'])->name('batch')->middleware('permission:batch');
    Route::get('footing/configure', [BatchController::class, 'footing'])->name('footing_configure');
    Route::get('footing/configure/add', [BatchController::class, 'footingConfigureAdd'])->name('footing_configure.add');
    Route::post('footing/configure/add', [BatchController::class, 'footingConfigureAddPost'])->middleware('permission:batch');
    Route::get('batch/add', [BatchController::class, 'batchAdd'])->name('batch.add')->middleware('permission:batch');
    Route::post('batch/add', [BatchController::class, 'batchAddPost'])->middleware('permission:batch');
    Route::get('batch/edit/{batch}', [BatchController::class, 'batchEdit'])->name('batch.edit')->middleware('permission:batch');
    Route::Post('batch/edit/{batch}', [BatchController::class, 'batchEditPost'])->middleware('permission:batch');
    Route::get('footing-configure-datatable', [BatchController::class, 'footingConfigureDatatable'])->name('footing_configure.datatable');
    Route::get('footing-configure-details/{columnConfigure}', [BatchController::class, 'footingConfigureDetails'])->name('footing_configure.details');
    Route::get('footing-configure-print/{columnConfigure}', [BatchController::class, 'footingConfigurePrint'])->name('footing_configure.print')->middleware('permission:batch');

    //Grade of Concrete
    Route::get('grade-of-concrete', [GradeOfConcreteController::class, 'gradeOfConcrete'])->name('grade_of_concrete')->middleware('permission:grade_of_concrete');
    Route::get('grade-of-concrete/add', [GradeOfConcreteController::class, 'gradeOfConcreteAdd'])->name('grade_of_concrete.add')->middleware('permission:grade_of_concrete');
    Route::post('grade-of-concrete/add', [GradeOfConcreteController::class, 'gradeOfConcreteAddPost'])->middleware('permission:grade_of_concrete');
    Route::get('grade-of-concrete/print', [GradeOfConcreteController::class, 'gradeOfConcretePrint'])->name('grade_of_concrete.print')->middleware('permission:grade_of_concrete');


    //Segment Configure
    Route::get('segment-configure', [SegmentConfigureController::class, 'segmentConfigure'])->name('segment_configure');
    Route::get('segment-configure-datatable', [SegmentConfigureController::class, 'segmentConfigureDatatable'])->name('segment_configure.datatable');
    Route::get('segment-configure/add', [SegmentConfigureController::class, 'segmentConfigureAdd'])->name('segment_configure.add');
    Route::post('segment-configure/add', [SegmentConfigureController::class, 'segmentConfigureAddPost']);
    Route::get('segment-configure-details/{segmentConfigure}', [SegmentConfigureController::class, 'details'])->name('segment_configure.details');
    Route::get('segment-configure/edit/{segmentConfigure}', [SegmentConfigureController::class, 'segmentConfigureEdit'])->name('segment_configure.edit');
    Route::post('segment-configure/edit/{segmentConfigure}', [SegmentConfigureController::class, 'segmentConfigureEditPost']);

    //Bricks Configure
    Route::get('bricks-configure', [BricksConfigureController::class, 'bricksConfigure'])->name('bricks_configure')->middleware('permission:bricks_configure');
    Route::get('bricks-configure-datatable', [BricksConfigureController::class, 'bricksConfigureDatatable'])->name('bricks_configure.datatable');
    Route::get('bricks-configure/add', [BricksConfigureController::class, 'bricksConfigureAdd'])->name('bricks_configure.add')->middleware('permission:bricks_configure');
    Route::post('bricks-configure/add', [BricksConfigureController::class, 'bricksConfigureAddPost'])->middleware('permission:bricks_configure');
    Route::get('bricks-configure-details/{bricksConfigure}', [BricksConfigureController::class, 'bricksConfigureDetails'])->name('bricks_configure.details')->middleware('permission:bricks_configure');
    Route::get('bricks-configure-print/{bricksConfigure}', [BricksConfigureController::class, 'bricksConfigurePrint'])->name('bricks_configure.print')->middleware('permission:bricks_configure');

    //Grill Glass Tiles Configure
    Route::get('grill-glass-tiles-configure', [GrillGlassTilesConfigureController::class, 'grillGlassTilesConfigure'])->name('grill_glass_tiles_configure')->middleware('permission:grill_glass_tiles_configure');
    Route::get('grill-glass-tiles-configure-datatable', [GrillGlassTilesConfigureController::class, 'grillGlassTilesConfigureDatatable'])->name('grill_glass_tiles_configure.datatable');
    Route::get('grill-glass-tiles-configure/add', [GrillGlassTilesConfigureController::class, 'grillGlassTilesConfigureAdd'])->name('grill_glass_tiles_configure.add')->middleware('permission:grill_glass_tiles_configure');
    Route::post('grill-glass-tiles-configure/add', [GrillGlassTilesConfigureController::class, 'grillGlassTilesConfigureAddPost'])->middleware('permission:grill_glass_tiles_configure');
    Route::get('grill-glass-tiles-configure-details/{grillGlassTilesConfigure}', [GrillGlassTilesConfigureController::class, 'grillGlassTilesConfigureDetails'])->name('grill_glass_tiles_configure.details')->middleware('permission:grill_glass_tiles_configure');
    Route::get('grill-glass-tiles-configure-print/{grillGlassTilesConfigure}', [GrillGlassTilesConfigureController::class, 'grillGlassTilesConfigurePrint'])->name('grill_glass_tiles_configure.print')->middleware('permission:grill_glass_tiles_configure');

     //Glass Configure
    Route::get('glass-configure', [GlassConfigureController::class, 'glassConfigure'])->name('glass_configure')->middleware('permission:grill_glass_tiles_configure');
    Route::get('glass-configure/add', [GlassConfigureController::class, 'glassConfigureAdd'])->name('glass_configure.add')->middleware('permission:grill_glass_tiles_configure');
    Route::post('glass-configure/add', [GlassConfigureController::class, 'glassConfigureAddPost'])->middleware('permission:grill_glass_tiles_configure');
    Route::get('glass-configure-details/{glassConfigure}', [GlassConfigureController::class, 'glassConfigureDetails'])->name('glass_configure.details')->middleware('permission:grill_glass_tiles_configure');
    Route::get('glass-configure-print/{glassConfigure}', [GlassConfigureController::class, 'glassConfigurePrint'])->name('glass.print')->middleware('permission:grill_glass_tiles_configure');
    Route::get('glass-configure-datatable', [GlassConfigureController::class, 'glassConfigureDatatable'])->name('glass_configure.datatable');

    //Tiles Configure
    Route::get('tiles-configure', [TilesConfigureController::class, 'tilesConfigure'])->name('tiles_configure')->middleware('permission:grill_glass_tiles_configure');
    Route::get('tiles-configure/add', [TilesConfigureController::class, 'tilesConfigureAdd'])->name('tiles_configure.add')->middleware('permission:grill_glass_tiles_configure');
    Route::post('tiles-configure/add', [TilesConfigureController::class, 'tilesConfigureAddPost'])->middleware('permission:grill_glass_tiles_configure');
    Route::get('tiles-configure-details/{tilesConfigure}', [TilesConfigureController::class, 'tilesConfigureDetails'])->name('tiles_configure.details')->middleware('permission:grill_glass_tiles_configure');
    Route::get('tiles-configure-print/{tilesConfigure}', [TilesConfigureController::class, 'tilesConfigurePrint'])->name('tiles_configure.print')->middleware('permission:grill_glass_tiles_configure');
    Route::get('tiles-configure-datatable', [TilesConfigureController::class, 'tilesConfigureDatatable'])->name('tiles_configure.datatable');

    //Paint Configure
    Route::get('paint-configure', [PaintConfigureController::class, 'paintConfigure'])->name('paint_configure')->middleware('permission:paint_configure');
    Route::get('paint-configure-datatable', [PaintConfigureController::class, 'paintConfigureDatatable'])->name('paint_configure.datatable');
    Route::get('paint-configure/add', [PaintConfigureController::class, 'paintConfigureAdd'])->name('paint_configure.add')->middleware('permission:paint_configure');
    Route::post('paint-configure/add', [PaintConfigureController::class, 'paintConfigureAddPost'])->middleware('permission:paint_configure');
    Route::get('paint-configure-details/{paintConfigure}', [PaintConfigureController::class, 'paintConfigureDetails'])->name('paint_configure.details')->middleware('permission:paint_configure');
    Route::get('paint-configure-print/{paintConfigure}', [PaintConfigureController::class, 'paintConfigurePrint'])->name('paint_configure.print')->middleware('permission:paint_configure');

    //Plaster Configure
    Route::get('plaster-configure', [PlasterConfigureController::class, 'plasterConfigure'])->name('plaster_configure')->middleware('permission:plaster_configure');
    Route::get('plaster-configure-datatable', [PlasterConfigureController::class, 'plasterConfigureDatatable'])->name('plaster_configure.datatable');
    Route::get('plaster-configure/add', [PlasterConfigureController::class, 'plasterConfigureAdd'])->name('plaster_configure.add')->middleware('permission:plaster_configure');
    Route::post('plaster-configure/add', [PlasterConfigureController::class, 'plasterConfigureAddPost'])->middleware('permission:plaster_configure');
    Route::get('plaster-configure-details/{plasterConfigure}', [PlasterConfigureController::class, 'plasterConfigureDetails'])->name('plaster_configure.details')->middleware('permission:plaster_configure');
    Route::get('plaster-configure-print/{plasterConfigure}', [PlasterConfigureController::class, 'plasterConfigurePrint'])->name('plaster_configure.print')->middleware('permission:plaster_configure');

    //Earth Work Configure
    Route::get('earth-work-configure', [EarthWorkConfigureController::class, 'earthWorkConfigure'])->name('earth_work_configure')->middleware('permission:earth_work_configure');
    Route::get('earth-work-configure-datatable', [EarthWorkConfigureController::class, 'earthWorkConfigureDatatable'])->name('earth_work_configure.datatable');
    Route::get('earth-work-configure/add', [EarthWorkConfigureController::class, 'earthWorkConfigureAdd'])->name('earth_work_configure.add')->middleware('permission:earth_work_configure');
    Route::post('earth-work-configure/add', [EarthWorkConfigureController::class, 'earthWorkConfigureAddPost'])->middleware('permission:earth_work_configure');
    Route::get('earth-work-configure-details/{earthWorkConfigure}', [EarthWorkConfigureController::class, 'earthWorkConfigureDetails'])->name('earth_work_configure.details')->middleware('permission:earth_work_configure');
    Route::get('earth-work-configure-print/{earthWorkConfigure}', [EarthWorkConfigureController::class, 'earthWorkConfigurePrint'])->name('earth_work_configure.print')->middleware('permission:earth_work_configure');

    //Pile Configure
    Route::get('pile-configure', [SegmentConfigureController::class, 'pileConfigure'])->name('pile_configure')->middleware('permission:pile_configure');
    Route::get('pile-configure-datatable', [SegmentConfigureController::class, 'pileConfigureDatatable'])->name('pile_configure.datatable')->middleware('permission:pile_configure');
    Route::get('pile-configure/add', [SegmentConfigureController::class, 'pileConfigureAdd'])->name('pile_configure.add')->middleware('permission:pile_configure');
    Route::post('pile-configure/add', [SegmentConfigureController::class, 'pileConfigureAddPost'])->middleware('permission:pile_configure');
    Route::get('pile-configure-details/{pileConfigure}', [SegmentConfigureController::class, 'pileConfigureDetails'])->name('pile_configure.details')->middleware('permission:pile_configure');
    Route::get('pile-configure-print/{pileConfigure}', [SegmentConfigureController::class, 'pileConfigurePrint'])->name('pile_configure.print')->middleware('permission:pile_configure');

    //Beam Configure
    Route::get('beam-configure', [CommonConfigureController::class, 'beamConfigure'])->name('beam_configure')->middleware('permission:beam_configure');
    Route::get('beam-configure-datatable', [CommonConfigureController::class, 'beamConfigureDatatable'])->name('beam_configure.datatable');
    Route::get('beam-configure/add', [CommonConfigureController::class, 'beamConfigureAdd'])->name('beam_configure.add')->middleware('permission:beam_configure');
    Route::post('beam-configure/add', [CommonConfigureController::class, 'beamConfigureAddPost'])->middleware('permission:beam_configure');
    Route::get('beam-configure-details/{beamConfigure}', [CommonConfigureController::class, 'beamConfigureDetails'])->name('beam_configure.details')->middleware('permission:beam_configure');
    Route::get('beam-configure-print/{beamConfigure}', [CommonConfigureController::class, 'beamConfigurePrint'])->name('beam_configure.print')->middleware('permission:beam_configure');

    //Column Configure
    Route::get('column-configure', [ColumnConfigureController::class, 'columnConfigure'])->name('column_configure')->middleware('permission:column_configure');
    Route::get('column-configure-datatable', [ColumnConfigureController::class, 'columnConfigureDatatable'])->name('column_configure.datatable');
    Route::get('column-configure/add', [ColumnConfigureController::class, 'columnConfigureAdd'])->name('column_configure.add')->middleware('permission:column_configure');
    Route::post('column-configure/add', [ColumnConfigureController::class, 'columnConfigureAddPost'])->middleware('permission:column_configure');
    Route::get('column-configure-details/{columnConfigure}', [ColumnConfigureController::class, 'columnConfigureDetails'])->name('column_configure.details')->middleware('permission:column_configure');
    Route::get('column-configure-print/{columnConfigure}', [ColumnConfigureController::class, 'columnConfigurePrint'])->name('column_configure.print')->middleware('permission:column_configure');

    //Common Configure
    Route::get('common-configure', [CommonConfigureController::class, 'configureAll'])->name('common_configure')->middleware('permission:slab_cap_wall_configure');
    Route::get('common-configure-datatable', [CommonConfigureController::class, 'commonConfigureDatatable'])->name('common_configure.datatable');
    Route::get('common-configure/add', [CommonConfigureController::class, 'commonConfigureAdd'])->name('common_configure.add')->middleware('permission:slab_cap_wall_configure');
    Route::post('common-configure/add', [CommonConfigureController::class, 'commonConfigureAddPost'])->middleware('permission:slab_cap_wall_configure');
    Route::get('common-configure-details/{commonConfigure}', [CommonConfigureController::class, 'commonConfigureDetails'])->name('common_configure.details')->middleware('permission:slab_cap_wall_configure');
    Route::get('common-configure-print/{commonConfigure}', [CommonConfigureController::class, 'commonConfigurePrint'])->name('common_configure.print')->middleware('permission:slab_cap_wall_configure');

    //Assign Segment
    Route::get('assign-segment', [AssignSegmentController::class, 'assignSegment'])->name('assign_segment.all');
    Route::get('assign-segment-datatable', [AssignSegmentController::class, 'assignSegmentDatatable'])->name('assign_segment.datatable');
    Route::get('assign-segment/add', [AssignSegmentController::class, 'assignSegmentAdd'])->name('assign_segment.add');
    Route::post('assign-segment/add', [AssignSegmentController::class, 'assignSegmentAddPost']);
    Route::get('assign-segment-details/{assignSegment}', [AssignSegmentController::class, 'assignSegmentDetails'])->name('assign_segment.details');

    // Extra Costing Add
    Route::get('extra-costing', [ExtraCostingController::class, 'index'])->name('extra_costing')->middleware('permission:extra_costing');
    Route::get('extra-costing/add', [ExtraCostingController::class, 'add'])->name('extra_costing.add')->middleware('permission:extra_costing');
    Route::post('extra-costing/add', [ExtraCostingController::class, 'addPost'])->middleware('permission:extra_costing');
    Route::get('extra-costing-datatable', [ExtraCostingController::class, 'extraCostingDatatable'])->name('extra_costing.datatable');
    Route::get('extra-costing-details/{extraCosting}', [ExtraCostingController::class, 'details'])->name('extra_costing.details')->middleware('permission:extra_costing');

    Route::get('extra-cost-product', [ExtraCostingController::class, 'extraCostProductIndex'])->name('extra_cost_product')->middleware('permission:extra_costing');
    Route::get('extra-cost-product/add', [ExtraCostingController::class, 'extraCostProductAdd'])->name('extra_cost_product.add')->middleware('permission:extra_costing');
    Route::post('extra-cost-product/add', [ExtraCostingController::class, 'extraCostProductPost'])->middleware('permission:extra_costing');
    Route::get('extra-cost-product/edit/{product}', [ExtraCostingController::class, 'extraCostProductEdit'])->name('extra_cost_product.edit')->middleware('permission:extra_costing');
    Route::post('extra-cost-product/edit/{product}', [ExtraCostingController::class, 'extraCostProductEditPost'])->middleware('permission:extra_costing');
    Route::get('extra-costing-datatable', [ExtraCostingController::class, 'extraCostingDatatable'])->name('extra_costing.datatable');
    Route::get('extra-costing-details/{extraCosting}', [ExtraCostingController::class, 'details'])->name('extra_costing.details')->middleware('permission:extra_costing');

   //mobilization Product
    Route::get('mobilization-work-product', 'MobilizationWorkProductController@index')->name('mobilization_work_product')->middleware('permission:mobilization_product');
    Route::get('mobilization-work-product/add', 'MobilizationWorkProductController@add')->name('mobilization_work_product.add')->middleware('permission:mobilization_product');
    Route::post('mobilization-work-product/add', 'MobilizationWorkProductController@addPost')->middleware('permission:mobilization_product');
    Route::get('mobilization-work-product/edit/{product}', 'MobilizationWorkProductController@edit')->name('mobilization_work_product.edit')->middleware('permission:mobilization_product');
    Route::post('mobilization-work-product/edit/{product}', 'MobilizationWorkProductController@editPost')->middleware('permission:mobilization_product');


    // mobilization work Costing Add
    Route::get('mobilization-work-costing', [MobilizationWorkController::class, 'index'])->name('mobilization_work')->middleware('permission:mobilization_work');
    Route::get('mobilization-work-costing/add', [MobilizationWorkController::class, 'add'])->name('mobilization_work.add')->middleware('permission:mobilization_work');
    Route::post('mobilization-work-costing/add', [MobilizationWorkController::class, 'addPost'])->middleware('permission:mobilization_work');
    Route::get('mobilization-work-costing-datatable', [MobilizationWorkController::class, 'extraCostingDatatable'])->name('mobilization_work.datatable');
    Route::get('mobilization-work-costing/edit/{mobilizationWork}', [MobilizationWorkController::class, 'edit'])->name('mobilization_work.edit')->middleware('permission:mobilization_work');
    Route::post('mobilization-work-costing/edit/{mobilizationWork}', [MobilizationWorkController::class, 'editPost'])->middleware('permission:mobilization_work');
    Route::get('mobilization-work-costing-details/{mobilizationWork}', [MobilizationWorkController::class, 'details'])->name('mobilization_work.details')->middleware('permission:mobilization_work');


    //Cost Calculation
    Route::get('cost/calculation', [CostCalculationController::class, 'costCalculation'])->name('cost_calculation');
    Route::get('estimate-report', [CostCalculationController::class, 'estimateReport'])->name('estimate_report')->middleware('permission:estimate_report');
    Route::get('costing-report', [CostCalculationController::class, 'costingReport'])->name('costing_report')->middleware('permission:costing_report');
    Route::get('estimation-costing-summary', [CostCalculationController::class, 'estimationCostingSummary'])->name('estimation_costing_summary')->middleware('permission:estimation_costing_summary');

    // Report
    Route::get('report/salary-sheet', 'ReportController@salarySheet')->name('report.salary.sheet')->middleware('permission:salary_sheet');
    Route::get('report/purchase', 'ReportController@purchase')->name('report.purchase')->middleware('permission:purchase_report');
    Route::get('report/sale', 'ReportController@sale')->name('report.sale')->middleware('permission:sale_report');
    Route::get('report/ledger', 'ReportController@ledger')->name('report.ledger');
    Route::get('receive/payment', 'ReportController@receivePayment')->name('report.receive_and_payment')->middleware('permission:receive_and_payment');
    Route::get('report/project-summary', 'ReportController@projectSummary')->name('report.project_summary')->middleware('permission:project_summary_report');
    Route::get('report/year-wise-payment', 'ReportController@yearWisePayment')->name('report.year_wise_payment')->middleware('permission:year_wise_report');


    Route::get('report/purchase-stock', 'ReportController@purchaseStock')->name('report.purchase_stock')->middleware('permission:row_material_stock');
    Route::get('report/sale-stock', 'ReportController@saleStock')->name('report.sale_stock');
    Route::get('report/labour-bill-sheet', 'ReportController@foodCost')->name('food_cost.report')->middleware('permission:labour_advance_report');
    Route::get('report/labour-bill-process', 'ReportController@labourBillProcess')->name('labour_bill_process.report')->middleware('permission:labour_bill_report');
    Route::get('report/employee-advance-salary', 'ReportController@employeeAdvanceSalary')->name('report.employee_advance_salary')->middleware('permission:employee_advance_report');


    Route::get('report/monthly-expenditure', 'ReportController@monthlyExpenditure')->name('report.monthly_expenditure')->middleware('permission:monthly_expenditure');
    Route::get('report/employee-list', 'ReportController@employeeList')->name('report.employee_list')->middleware('permission:employee_list');
    Route::get('report/employee-attendance', 'ReportController@employeeAttendance')->name('report.employee_attendance')->middleware('permission:employee_attendance_report');
    Route::get('report/employee-attendance-in-out', 'ReportController@employeeAttendanceInOut')->name('report.employee_attendance_in_out');
    Route::get('report/price-with-stock', 'ReportController@priceWithStock')->name('report.price.with.stock');
    Route::get('report/price-without-stock', 'ReportController@priceWithOutStock')->name('report.price.without.stock');
    Route::get('report/project', 'ReportController@project')->name('report.project_statement');
    Route::get('report/client', 'ReportController@client')->name('report.client_report')->middleware('permission:client_report');
    Route::get('report/project/wise', 'ReportController@projectwiseReport')->name('report.project_report')->middleware('permission:project_wise_report');


    // User Management
    Route::get('user', 'UserController@index')->name('user.all')->middleware('permission:users');
//    Route::get('user', 'UserController@index')->name('user.all');
    Route::get('user/add', 'UserController@add')->name('user.add')->middleware('permission:users');
//    Route::get('user/add', 'UserController@add')->name('user.add');
    Route::post('user/add', 'UserController@addPost')->middleware('permission:users');
//    Route::post('user/add', 'UserController@addPost');
    Route::get('user/edit/{user}', 'UserController@edit')->name('user.edit')->middleware('permission:users');
//    Route::get('user/edit/{user}', 'UserController@edit')->name('user.edit');
    Route::post('user/edit/{user}', 'UserController@editPost')->middleware('permission:users');
//    Route::post('user/edit/{user}', 'UserController@editPost');
    Route::post('user/delete', 'UserController@delete')->name('user.delete')->middleware('permission:users');


    // Common
    Route::get('get-branch', 'CommonController@getBranch')->name('get_branch');
    Route::get('get-bank-account', 'CommonController@getBankAccount')->name('get_bank_account');
    Route::get('get-account-head-type', 'CommonController@getAccountHeadType')->name('get_account_head_type');
    Route::get('get-account-head-type-trx', 'CommonController@getAccountHeadTypeTrx')->name('get_account_head_type_trx');
    Route::get('get-account-head-sub-type', 'CommonController@getAccountHeadSubType')->name('get_account_head_sub_type');
    Route::get('get-account-head-sub-type-trx', 'CommonController@getAccountHeadSubTypeTrx')->name('get_account_head_sub_type_trx');
    Route::get('order-details', 'CommonController@orderDetails')->name('get_order_details');
    Route::get('get-designation', 'CommonController@getDesignation')->name('get_designation');
    Route::get('get-employee-details', 'CommonController@getEmployeeDetails')->name('get_employee_details');
    Route::get('get-employee-details-bonus', 'CommonController@getEmployeeDetailsBonus')->name('get_employee_details_bonus');
    Route::get('get-month', 'CommonController@getMonth')->name('get_month');
    Route::get('get-month-salary-sheet', 'CommonController@getMonthSalaryMonth')->name('get_month_salary_sheet');
    Route::get('cash', 'CommonController@cash')->name('cash')->middleware('permission:cash');
    Route::post('cash', 'CommonController@cashPost')->middleware('permission:cash');
    Route::get('requisition-product-json', 'CommonController@requisitionProductJson')->name('requisition_product.json');
    Route::get('get-segment', 'CommonController@getSegment')->name('get_segment');
    Route::get('get-requisition', 'CommonController@getRequisition')->name('get_requisition');
    Route::get('get-unit', 'CommonController@getUnit')->name('get_unit');
    Route::get('get-purchase-order', 'CommonController@getPurchaseOrder')->name('get_purchase_order');
    Route::get('get-product', 'CommonController@getProduct')->name('get_product');
    Route::get('requisition-product-details', 'CommonController@requisitionProductDetails')->name('requisition_product.details');
    Route::get('get-requisition-approved', 'CommonController@getRequisitionApproved')->name('get_requisition_approved');
    Route::get('get-scrap-product-details', 'CommonController@getScrapProductDetails')->name('get_scrap_product_details');
    Route::get('get-scrap-product', 'CommonController@getScrapProduct')->name('get_scrap_product');
    Route::get('get-estimate-project-segment', 'CommonController@getEstimateProjectSegment')->name('get_estimate_project_segment');
    Route::get('get-estimate-product-unit', 'CommonController@getEstimateProductUnit')->name('get_estimate_product_unit');
    Route::get('get-estimate-floor', 'CommonController@getEstimateFloor')->name('get_estimate_floor');
    Route::get('get-bricks-area', 'CommonController@getBricksArea')->name('get_bricks_area');
    Route::get('get-wall-direction', 'CommonController@getWallDirection')->name('get_wall_direction');


    //loan holder
    Route::get('loan-holder', 'LoanHolderController@index')->name('loan_holder')->middleware('permission:loan_holder');
    Route::get('loan-holder/add', 'LoanHolderController@add')->name('loan_holder.add')->middleware('permission:loan_holder');
    Route::post('loan-holder/add', 'LoanHolderController@addPost')->middleware('permission:loan_holder');
    Route::get('loan-holder/edit/{loanHolder}', 'LoanHolderController@edit')->name('loan_holder.edit')->middleware('permission:loan_holder');
    Route::post('loan-holder/edit/{loanHolder}', 'LoanHolderController@editPost')->middleware('permission:loan_holder');

    //Holder loan section
    Route::get('loan/all','LoanController@Index')->name('loan.all')->middleware('permission:holder_loan_list');
    Route::get('loan/create','LoanController@Create')->name('loan.add')->middleware('permission:holder_loan_list');
    Route::post('loan/create','LoanController@loanStore')->middleware('permission:holder_loan_list');
    Route::get('loan/datatable', 'LoanController@loanDatatable')->name('loan.datatable');
    Route::get('loan-details/{loanHolder}/{type}', 'LoanController@loanDetails')->name('loan_details')->middleware('permission:holder_loan_list');
    Route::get('loan-payment/get-orders', 'LoanController@loanPaymentGetNumber')->name('loan_payment.get_number')->middleware('permission:holder_loan_list');
    Route::get('loan-payment/order-details', 'LoanController@loanPaymentOrderDetails')->name('loan_payment.order_details')->middleware('permission:holder_loan_list');
    Route::post('loan-payment/payment', 'LoanController@makePayment')->name('loan_payment.make_payment')->middleware('permission:holder_loan_list');
    Route::get('loan-payment-details/{loan}/{type}', 'LoanController@loanPaymentDetails')->name('loan_payment_details')->middleware('permission:holder_loan_list');
    // Route::get('loan-receipt/payment/print/{payment}', 'LoanController@loanPaymentPrint')->name('loan_receipt.payment_print');
    Route::get('loan-payment-print/{payment}', 'LoanController@loanPaymentPrint')->name('loan_payment_print')->middleware('permission:holder_loan_list');

    //Client Loan
    Route::get('client/loan/all','LoanController@clientLoan')->name('client_loan.all')->middleware('permission:client_loan');
    Route::get('client-loan-payment/get-orders', 'LoanController@clientLoanPaymentGetNumber')->name('client_loan_payment.get_number')->middleware('permission:client_loan');
    Route::post('client-loan/payment', 'LoanController@clientLoanmakePayment')->name('client_loan_payment.make_payment')->middleware('permission:client_loan');
    Route::get('client-loan-payment/order-details', 'LoanController@clientLoanPaymentOrderDetails')->name('client_loan_payment.order_details')->middleware('permission:client_loan');
    Route::get('client/loan-details/{clientLoan}/{type}', 'LoanController@clientLoanDetails')->name('client_loan_details')->middleware('permission:client_loan');
    Route::get('client/loan-payment-details/{loan}/{type}', 'LoanController@clientLoanPaymentDetails')->name('client_loan_payment_details')->middleware('permission:client_loan');
    Route::get('client/loan/datatable', 'LoanController@clientLoanDatatable')->name('client_loan.datatable');

    //Project Loan
    Route::get('project/loan/all','LoanController@projectLoanAll')->name('project_loan.all')->middleware('permission:project_loan');
    Route::get('project-loan-payment/get-orders', 'LoanController@projectLoanPaymentGetNumber')->name('project_loan_payment.get_number')->middleware('permission:project_loan');
    Route::post('project-loan/payment', 'LoanController@projectLoanmakePayment')->name('project_loan_payment.make_payment')->middleware('permission:project_loan');
    Route::get('project-loan/payment/order-details', 'LoanController@projectLoanPaymentOrderDetails')->name('project_loan_payment.order_details')->middleware('permission:project_loan');
    Route::get('project-loan-details/{loan}/{type}', 'LoanController@projectLoanDetails')->name('project_loan_details')->middleware('permission:project_loan');
    Route::get('project-loan-payment-details/{loan}/{type}', 'LoanController@projectLoanPaymentDetails')->name('project_loan_payment_details')->middleware('permission:project_loan');
    Route::get('project-loan/datatable', 'LoanController@projectLoanDatatable')->name('project_loan.datatable');
    Route::get('project-loan-print/{loan}/{type}', 'LoanController@projectLoanPrint')->name('project_loan_print')->middleware('permission:project_loan');
    Route::get('holder-loan-print/{loan}/{type}', 'LoanController@holderLoanPrint')->name('holder_loan_print')->middleware('permission:loan_holder');
    Route::get('client-loan-print/{loan}/{type}', 'LoanController@clientLoanPrint')->name('client_loan_print')->middleware('permission:client_loan');

    //Bank loan
    Route::get('bank_loan/all','BankLoanController@Index')->name('bank_loan.all');
    Route::get('bank_loan/add', 'BankLoanController@add')->name('bank_loan.add');
    Route::post('bank_loan/add', 'BankLoanController@addPost');
    Route::get('bank_loan/datatable', 'BankLoanController@bankLoanDatatable')->name('bank_loan.datatable');
    // Route::get('bank-loan-print/{loan}', 'LoanController@loanPrint')->name('bank_loan_print');
    // Route::get('bank-loan-payment/get-orders', 'LoanController@loanPaymentGetNumber')->name('loan_payment.get_number');
    // Route::get('bank-loan-payment/order-details', 'LoanController@loanPaymentOrderDetails')->name('loan_payment.order_details');
    // Route::post('bank-loan-payment/payment', 'LoanController@makePayment')->name('loan_payment.make_payment');
    // Route::get('bank-loan-details/{loanHolder}/{type}', 'LoanController@loanDetails')->name('loan_details');
    // Route::get('bank-loan-payment-details/{loan}/{type}', 'LoanController@loanPaymentDetails')->name('loan_payment_details');
    // Route::get('bank-loan-payment-print/{payment}', 'LoanController@loanPaymentPrint')->name('loan_payment_print');
//    });
});

require __DIR__.'/auth.php';
