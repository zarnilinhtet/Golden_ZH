<?php


use App\Models\Brand;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\InOutController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WayAssignController;
use App\Http\Controllers\SalePersonController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\WayCallTaskController;
use App\Http\Controllers\PricePercentController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReturnInvoiceController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\AccountLocationReportController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('dashboard/{branch?}', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/home', function () {
    return view('home');
})->name('home');
Route::middleware('auth')->group(function () {

    Route::post('/migrate', function () {
        DB::table('items')->truncate();
        DB::table('item_variations')->truncate();

        return redirect()->back()->with('success', 'Tables truncated successfully!');
    })->name('migrate');
    Route::get('/migrations', function () {
        Artisan::call('migrate');
        return "Migration run successfully!";
    });
    //Sale Person Report
    Route::get('sale_person_report/{id}', [SalePersonController::class, 'sale_person_report'])->name('sale_person_report');
    Route::post('sale_person_report_store', [SalePersonController::class, 'sale_person_report_store'])->name('sale_person_report_store');
    Route::get('sale_person_report_edit/{id}', [SalePersonController::class, 'sale_person_report_edit'])->name('sale_person_report_edit');
    Route::post('sale_person_report_update/{id}', [SalePersonController::class, 'sale_person_report_update'])->name('sale_person_report_update');
    Route::get('sale_person_report_delete/{id}/{sale_person_id}', [SalePersonController::class, 'sale_person_report_delete'])->name('sale_person_report_delete');

    //Way Call Task
    Route::get('way_call_task/{id}', [WayCallTaskController::class, 'index'])->name('way_call_task');
    Route::post('way_call_task_store', [WayCallTaskController::class, 'store'])->name('way_call_task_store');
    Route::get('way_call_task_edit/{id}', [WayCallTaskController::class, 'edit'])->name('way_call_task_edit');
    Route::post('way_call_task_update/{id}', [WayCallTaskController::class, 'update'])->name('way_call_task_update');
    Route::get('way_call_task_delete/{id}', [WayCallTaskController::class, 'delete'])->name('way_call_task_delete');


    Route::get('sale_person', [SalePersonController::class, 'sale_person'])->name('sale_person');
    Route::post('sale_person_store', [SalePersonController::class, 'sale_person_store']);
    Route::get('sale_person_edit/{id}', [SalePersonController::class, 'sale_person_edit']);
    Route::post('sale_person_update/{id}', [SalePersonController::class, 'sale_person_update']);
    Route::get('sale_person_delete/{id}', [SalePersonController::class, 'sale_person_delete']);
    // Route::get('way_call', [WayAssignController::class, 'way_call']);
    Route::get('way_call', [WayAssignController::class, 'way_call'])->name('wall_call');
    Route::get('select_month/{start}', [WayAssignController::class, 'select_month'])->name('select_month');
    Route::get('select_sale_person/{year}/{month}', [WayAssignController::class, 'select_sale_person'])->name('select_sale_person');
    Route::get('show_way_call/{year}/{month}/{id}', [WayAssignController::class, 'show_way_call'])->name('show_way_call');

    //Account
    Route::get('account', [AccountController::class, 'account']);
    Route::post('/accounts_register', [AccountController::class, 'accountRegister']);
    Route::get('/account/{branch_id?}', [AccountController::class, 'accountStore'])->name('accounts');
    Route::get('/account', [AccountController::class, 'accountStore']);
    Route::get('/accounts_delete/{id}', [AccountController::class, 'delete']);
    Route::get('/accounts_show/{id}', [AccountController::class, 'show']);
    Route::post('/accounts_update/{id}', [AccountController::class, 'update']);
    Route::get('get_accounts_transaction', [InvoiceController::class, 'invoice_get_transaction'])->name('get_accounts_transaction');


    //Transaction
    Route::get('/transaction', [TransactionController::class, 'transaction']);
    Route::get('/transaction/{branch_id?}', [TransactionController::class, 'transaction'])->name('transactions');
    Route::post('/transaction_register', [TransactionController::class, 'register']);
    Route::get('/transaction_delete/{id}', [TransactionController::class, 'delete']);
    Route::get('/transaction_show/{id}', [TransactionController::class, 'show']);
    Route::post('/transaction_update/{id}', [TransactionController::class, 'update']);
    Route::put('/payment_update/{id}', [TransactionController::class, 'payment_update'])->name('payment.update');
    Route::get('/payment_edit/{id}', [TransactionController::class, 'payment_edit']);
    Route::get('/get-accounts', [TransactionController::class, 'getAccountsByLocation'])->name('get-accounts');

    //makepayment
    Route::get('make_payment/{id}', [InvoiceController::class, 'payment']);
    Route::post('make_payment_store/{id}', [InvoiceController::class, 'payment_store']);

    Route::post('payment_update/{id}', [InvoiceController::class, 'payment_update']);
    //Payment
    Route::get('/payment/{id}', [TransactionController::class, 'payment']);
    Route::post('/transaction_payment_register/{id}', [PaymentController::class, 'payment_register']);
    Route::get('/transaction_delete_payment/{id}', [PaymentController::class, 'paymentDelete']);
    Route::get('/transaction_payment_edit/{id}', [PaymentController::class, 'makePaymentEdit']);
    Route::put('/transaction_payment_update/{id}', [PaymentController::class, 'paymentUpdate']);

    Route::get('/get-voucher-no', [PaymentController::class, 'getVoucherNo'])->name('get-voucher-no');
    Route::get('/transaction_delete_payment/{id}', [PaymentController::class, 'paymentDelete']);
    Route::get('/transaction_delete_payment_id/{id}', [PaymentController::class, 'paymentDeleteId']);
    Route::get('/delete_record_payment/{id}', [PaymentController::class, 'delete_record_payment']);
    Route::get('/payments_restore/{id}', [PaymentController::class, 'restore_payment']);

    //setting
    Route::get('setting', [SettingController::class, 'index'])->name('setting');
    Route::post('setting_store', [SettingController::class, 'store']);
    Route::get('setting_edit/{id}', [SettingController::class, 'edit']);
    Route::post('setting_update/{id}', [SettingController::class, 'update']);
    Route::get('setting_delete/{id}', [SettingController::class, 'delete']);
    Route::get('invoice_setting', [SettingController::class, 'invoice']);
    Route::post('invoice_setting/edit', [SettingController::class, 'invoice_setting_edit']);
    Route::get('pos_setting', [SettingController::class, 'pos']);
    Route::post('pos_setting/edit', [SettingController::class, 'pos_setting_edit']);
    Route::get('invoice_return_setting', [SettingController::class, 'invoice_return']);
    Route::post('invoice_return_setting_edit', [SettingController::class, 'invoice_return_setting_edit']);
    Route::get('supplier_refund_setting', [SettingController::class, 'supplier_refund']);

    Route::get('exchange_order_setting', [SettingController::class, 'exchange_order']);
    Route::post('exchange_order_setting_edit', [SettingController::class, 'exchange_order_setting_edit']);
    Route::get('exchange_order_return_setting', [SettingController::class, 'exchange_order_return']);
    Route::post('exchange_order_return_setting_edit', [SettingController::class, 'exchange_order_return_setting_edit']);
    Route::get('/get-transactions', [TransactionController::class, 'getTransactionsByLocation'])->name('get-transactions');

    //doctors
    Route::get('doctors', [DoctorController::class, 'index']);
    Route::post('doctors_register', [DoctorController::class, 'store']);
    Route::get('doctors_edit/{id}', [DoctorController::class, 'edit']);
    Route::post('doctors_update/{id}', [DoctorController::class, 'update']);
    Route::get('doctors_delete/{id}', [DoctorController::class, 'delete']);
    Route::get('doctorDetails/{id}', [DoctorController::class, 'show']);

    //item
    Route::get('items', [ItemController::class, 'index']);
    Route::get('item_kits', [ItemController::class, 'item_kits_index'])->name('item.kit.index');
    Route::get('item_kits/{branch?}', [ItemController::class, 'item_kits_index'])->name('item.kit.index');
    Route::post('items_register', [ItemController::class, 'register']);
    //for item search to add
    Route::get('/item_search_for_add', [InvoiceController::class, 'item_search'])->name(
        'search_item_name_for_add'
    );
    Route::post('/item_search_fill', [InvoiceController::class, 'item_data_search_fill'])->name('item_data_search_fill');
    Route::get('barcode/{id}', [ItemController::class, 'barcode']);

    Route::get('items_expire', [ItemController::class, 'items_expire']);
    Route::get('item_price_percent', [PricePercentController::class, 'item_price_percent']);
    Route::post('price_percent_store', [PricePercentController::class, 'store']);
    Route::get('price_edit/{id}', [PricePercentController::class, 'edit']);
    Route::post('price_update/{id}', [PricePercentController::class, 'update']);
    Route::get('price_delete/{id}', [PricePercentController::class, 'delete']);


    //Customer
    Route::get('customer', [CustomerController::class, 'index']);
    Route::get('customer_credit/{id}', [CustomerController::class, 'credit']);
    Route::post('customer_register', [CustomerController::class, 'store']);
    Route::get('customer_edit/{id}', [CustomerController::class, 'edit']);
    Route::post('customer_update/{id}', [CustomerController::class, 'update']);
    Route::get('customer_delete/{id}', [CustomerController::class, 'delete']);
    Route::get('all_customer_credit', [CustomerController::class, 'all_customer_credit']);
    Route::get('customer_view/{id}', [CustomerController::class, 'view']);
    Route::get('upload_file/{id}', [CustomerController::class, 'upload_file'])->name('upload_file');
    Route::post('customer_file_upload_store/{id}', [CustomerController::class, 'upload_file_store']);
    Route::get('file_edit/{id}', [CustomerController::class, 'file_edit']);
    Route::post('file_update/{id}', [CustomerController::class, 'file_update']);
    Route::get('file_delete/{id}', [CustomerController::class, 'file_delete']);
    Route::get('customer_invoice/{id}', [CustomerController::class, 'customer_invoice']);
    Route::get('birthday_reminder', [CustomerController::class, 'birthday_reminder'])->name('birthday_reminder');
    //Start Treatment

    Route::get('treatment', [TreatmentController::class, 'index']);
    Route::get('treatment_register', [TreatmentController::class, 'show_register']);
    Route::post('treatment_store', [TreatmentController::class, 'store']);
    Route::get('treatment_edit/{id}', [TreatmentController::class, 'edit']);
    Route::post('treatment_update/{id}', [TreatmentController::class, 'update']);
    Route::get('treatment_destory/{id}', [TreatmentController::class, 'destory']);
    Route::get('treatment_search', [TreatmentController::class, 'treatment_search'])->name('treatment_search');
    Route::get('/customer_name_search', [TreatmentController::class, 'customer_search'])->name('customer_search');
    Route::post('/customer_search_fill', [TreatmentController::class, 'customer_search_fill'])->name('customer_search_fill');
    Route::get('/treatment_service_search', [TreatmentController::class, 'treatment_service_search'])->name('treatment_service_search');
    Route::post('/treatment_service_search_fill', [TreatmentController::class, 'treatment_service_search_fill'])->name('treatment_service_search_fill');
    Route::get('treatment_details/{id}', [TreatmentController::class, 'details']);
    // End Treatment

    //Service
    Route::get('service', [CustomerController::class, 'service']);
    Route::get('service/{branch?}', [CustomerController::class, 'service'])->name('service_type');
    Route::get('service_search', [CustomerController::class, 'service_search']);


    //Supplier

    Route::get('supplier', [SupplierController::class, 'index']);
    Route::post('supplier_register', [SupplierController::class, 'store']);
    Route::get('supplier_edit/{id}', [SupplierController::class, 'edit']);
    Route::post('supplier_update/{id}', [SupplierController::class, 'update']);
    Route::get('supplier_delete/{id}', [SupplierController::class, 'delete']);

    //Location
    Route::get('warehouse', [WarehouseController::class, 'index'])->name('warehouse');
    Route::post('warehouse_register', [WarehouseController::class, 'warehouse_register']);
    Route::get('warehouse_Delete/{id}', [WarehouseController::class, 'warehouse_delete']);
    Route::get('warehouse_Edit/{id}', [WarehouseController::class, 'warehouse_edit']);
    Route::post('warehouse_Update/{id}', [WarehouseController::class, 'warehouse_Update']);
    Route::get('transfer_item', [WarehouseController::class, 'transfer_item']);
    Route::post('store_transfer_item', [WarehouseController::class, 'store_transfer_item'])->name('store_transfer_item');
    Route::post('/autocomplete-part-code-location', [WarehouseController::class, 'autocompletePartCode'])->name('autocomplete.part-code-location');
    Route::post('/get-part-data-location', [WarehouseController::class, 'getPartData'])->name('get.part.data-location');
    Route::get('/show_transfer_history', [WarehouseController::class, 'show_history']);
    Route::get('transfer_delete/{id}', [WarehouseController::class, 'transfer_delete']);

    //Purchase Order
    Route::get('purchase_order_manage', [PurchaseOrderController::class, 'index'])->name('purchase_order');
    Route::get('purchase_order_register', [PurchaseOrderController::class, 'purchase_order_register'])->name('purchase_order_register');
    Route::post('purchase_order_store', [PurchaseOrderController::class, 'purchase_order_store'])->name('purchase_order_store');
    Route::get('purchase_order_delete/{id}', [PurchaseOrderController::class, 'po_delete'])->name('purchase_order_delete');
    Route::get('purchase_order_edit/{id}', [PurchaseOrderController::class, 'edit'])->name('purchase_order_edit');
    Route::post('purchase_order_update/{id}', [PurchaseOrderController::class, 'purchase_order_update'])->name('purchase_order_update');
    Route::get('purchase_order_details/{id}', [PurchaseOrderController::class, 'details'])->name('purchase_order_details');
    Route::post('item_search_for_po', [PurchaseOrderController::class, 'item_search_for_po'])->name('item_search_for_po');
    Route::get('get_accounts_transaction_po', [PurchaseOrderController::class, 'po_get_transaction'])->name('get_accounts_transaction_po');

    Route::post('/autocomplete_price', [App\Http\Controllers\PurchaseOrderController::class, 'autocomplete_price'])->name('autocomplete_price');
    Route::get('/customer_service_search', [App\Http\Controllers\PurchaseOrderController::class, 'po_search'])->name('po_search');
    Route::post('/customer_service_search_fill', [App\Http\Controllers\PurchaseOrderController::class, 'po_search_fill'])->name('po_search_fill');
    Route::post('/autocomplete-part-code', [PurchaseOrderController::class, 'autocompletePartCode'])->name('autocomplete.part-code');
    Route::post('/get-part-data', [PurchaseOrderController::class, 'getPartData'])->name('get.part.data');

    //Invoice
    Route::get('invoice', [InvoiceController::class, 'index']);
    Route::get('/invoice_record', [InvoiceController::class, 'record']);

    Route::post('invoice_register', [InvoiceController::class, 'invoice_register']);
    Route::get('invoice_reg', [InvoiceController::class, 'invoice']);
    Route::get('invoice_edit/{id}', [InvoiceController::class, 'invoice_edit']);
    Route::get('invoice_delete/{id}', [InvoiceController::class, 'invoice_delete']);
    Route::post('invoice_update/{id}', [InvoiceController::class, 'invoice_update']);
    Route::get('invoice_detail/{invoice}', [InvoiceController::class, 'invoice_detail'])->name('invoice_detail');
    Route::get('do_detail/{invoice}', [InvoiceController::class, 'do_detail'])->name('do_detail');
    Route::get('invoice_daily_sales', [InvoiceController::class, 'invoice_daily_sales'])->name('invoice_daily_sales');
    Route::get('invoice_daily_sale_search', [InvoiceController::class, 'invoice_daily_sale_search'])->name('invoice_daily_sale_search');
    Route::get('invoice_receipt_print/{invoice}', [InvoiceController::class, 'invoice_receipt_print']);


    Route::post('/autocomplete-part-code-invoice', [InvoiceController::class, 'autocompletePartCode'])->name('autocomplete.part-code-invoice');
    Route::post('/get-part-data-invoice', [InvoiceController::class, 'getPartData'])->name('get.part.data-invoice');
    Route::post('/autocomplete-barcode-invoice', [InvoiceController::class, 'autocompleteBarCode'])->name('autocomplete.barcode-invoice');
    Route::post('/get-barcode-data-invoice', [InvoiceController::class, 'getBarcodeData'])->name('get.barcode.data-invoice');
    Route::post('/autocomplete-part-code', [InvoiceController::class, 'autocompletePartCodeInvoice'])->name('autocomplete-part-code-invoice');
    Route::post('/get-part-data', [InvoiceController::class, 'getPartDataInvoice'])->name('get-part-data-invoice');



    //sale return
    Route::get('sale_return/{id}', [ReturnInvoiceController::class, 'index'])->name('sale_return');
    Route::get('sale_return_register/{id}', [ReturnInvoiceController::class, 'invoice_return'])->name('invoice_return');
    Route::post('invoice_return_register/{id}', [ReturnInvoiceController::class, 'invoice_return_register'])->name('invoice_return_register');
    Route::get('sale_return_get_transaction', [ReturnInvoiceController::class, 'sale_return_get_transaction'])->name('sale_return_get_transaction');
    Route::get('sale_return_delete/{id}', [ReturnInvoiceController::class, 'delete'])->name('sale_return_delete');

    //POS
    Route::get('pos_register', [InvoiceController::class, 'pos_register']);
    Route::get('pos_manage', [InvoiceController::class, 'pos']);
    Route::get('pos_daily_sales', [InvoiceController::class, 'pos_daily_sales'])->name('pos_daily_sales');
    Route::get(' pos_daily_sale_search', [InvoiceController::class, 'pos_daily_sale_search'])->name(' pos_daily_sale_search');


    //Quotation
    Route::get('quotation', [InvoiceController::class, 'quotation']);
    Route::get('quotation_register', [InvoiceController::class, 'quotation_register']);
    Route::get('/customer_service', [InvoiceController::class, 'customer_service_search'])->name('customer_service_search');
    Route::post('/customer_service_fill', [InvoiceController::class, 'customer_service_search_fill'])->name('customer_service_search_fill');
    Route::get('quotation_delete/{id}', [InvoiceController::class, 'quotation_delete']);
    Route::get('quotation_edit/{id}', [InvoiceController::class, 'quotation_edit']);
    Route::get('change_invoice/{id}', [InvoiceController::class, 'change_invoice']);


    //item
    Route::get('items', [ItemController::class, 'index'])->name('item.index');
    Route::get('items/{branch?}', [ItemController::class, 'index'])->name('item.index');

    Route::get('items_register', [ItemController::class, 'register']);
    Route::post('item_store', [ItemController::class, 'store']);
    Route::get('item_details/{id}', [ItemController::class, 'details']);
    Route::get('item_edit/{id}', [ItemController::class, 'edit']);
    Route::get('item_kit_edit/{id}', [ItemController::class, 'item_kit_edit']);
    Route::post('item_update/{id}', [ItemController::class, 'update']);
    Route::get('item_delete/{id}', [ItemController::class, 'delete']);
    Route::get('reorder_item', [ItemController::class, 'reorder_item']);

    //inout
    Route::get('in_out/{id}', [ItemController::class, 'inout']);
    Route::post('in/{id}', [InOutController::class, 'in']);
    Route::post('out/{id}', [InOutController::class, 'out']);
    Route::get('/stock_adjust_print/{variation_id}/{invoiceid}', [InOutController::class, 'display_print'])->name('display_print');
    Route::get('display_print/{id}/{item_variation_id}', [InOutController::class, 'damage_item_print'])->name('display_print');
    Route::get('/display_print/{items_id}/{id}', [InOutController::class, 'display_print'])->name('display_print');
    Route::get('invoice_record/{id}', [InOutController::class, 'invoice_record']);
    Route::get('purchase_record/{id}', [InOutController::class, 'purchase_order_reord']);
    Route::get('pos_record/{id}', [InOutController::class, 'pos_record']);
    Route::get('stock_adjust/{id}', [InOutController::class, 'stock_adjust']);
    Route::get('damage_item/{id}', [InOutController::class, 'damage_item']);

    //unit
    Route::get('unit', [UnitController::class, 'index']);
    Route::post('unit_store', [UnitController::class, 'unit_store']);
    Route::get('unit_edit/{id}', [UnitController::class, 'edit']);
    Route::post('unit_update/{id}', [UnitController::class, 'update']);
    Route::get('unit_delete/{id}', [UnitController::class, 'delete']);
    Route::get('get_part_data-unit', [UnitController::class, 'get_part_data_unit'])->name('get.part.data-unit');
    Route::post('unitSearch_withName', [UnitController::class, 'unitSearch_withName'])->name('unit_search_withName');
    Route::post('unitSearch_withID', [UnitController::class, 'unitSearch_withID'])->name('unit_search_withID');


    //report
    Route::get('report', [ReportController::class, 'report_invoice']);
    Route::get('/report_invoice/{branch?}', [ReportController::class, 'report_invoice'])->name('report_invoice');
    Route::get('/report_quotation/{branch?}', [ReportController::class, 'report_quotation'])->name('report_quotation');
    Route::get('/report_expense/{branch?}', [ReportController::class, 'reportExpense'])->name('report_expense');
    Route::get('invoice_search', [ReportController::class, 'invoiceSearch']);
    Route::get('report_quotation', [ReportController::class, 'report_quotation']);
    Route::get('report_po', [ReportController::class, 'report_po']);
    Route::get('report_sale_return', [ReportController::class, 'report_sale_return']);
    Route::get('report_item', [ReportController::class, 'report_item']);
    Route::get('report_item/{branch?}', [ReportController::class, 'report_item'])->name('report_item');
    Route::get('report_pos', [ReportController::class, 'report_pos']);
    Route::get('report_pos/{branch?}', [ReportController::class, 'report_pos'])->name('report_pos');
    Route::get('report_expense', [ReportController::class, 'reportExpense']);
    Route::get('expense_search', [ReportController::class, 'expenseSearch']);
    Route::get('monthly_invoice_search', [ReportController::class, 'monthly_invoice_search']);
    Route::get('pos_search', [ReportController::class, 'monthly_pos_search']);
    Route::get('quotation_search', [ReportController::class, 'monthly_quotation_search']);
    Route::get('item_search', [ReportController::class, 'monthly_item_search']);
    Route::get('monthly_po_search', [ReportController::class, 'monthly_po_search']);
    Route::get('monthly_sale_return_search', [ReportController::class, 'monthly_sale_return_search']);
    Route::get('report_sale_person/{branch?}', [ReportController::class, 'report_sale_person']);
    Route::get('customer_report/{branch?}', [ReportController::class, 'customer_report'])->name('customer_report');
    Route::get('customer_report_search', [ReportController::class, 'customer_report_search']);
    Route::get('customer_invoice/{id}/{start}/{end}', [ReportController::class, 'customer_invoice_report']);

    Route::get('report_account_total', [ReportController::class, 'account_total_report']);
    Route::get('profit_loss', [ReportController::class, 'profit_loss']);
    Route::get('balance_sheet', [ReportController::class, 'balance_sheet']);
    Route::get('general_ledger', [ReportController::class, 'general_ledger']);
    Route::get('general_ledger_fitter', [ReportController::class, 'generalLedgerFitter'])->name('report#generalLedgerFitter');
    Route::get('profit_loss_fitter', [ReportController::class, 'ProfitLossFitter'])->name('report#profitLossFitter');

    Route::get('balance_sheet_filter', [ReportController::class, 'balance_sheet_filter'])->name('report#balance_sheet_filter');
    Route::get('account_transactions/{id}', [ReportController::class, 'AccountTransactions']);
    Route::get('account_transaction_payment/{id}', [ReportController::class, 'TransactionsPayment']);
    Route::get('report_item_details/{id}/{branch}', [ItemController::class, 'report_item_details']);
    Route::get('doctor', [ReportController::class, 'doctor']);
    Route::get('doctorDetail/{id}', [ReportController::class, 'doctorDetail']);
    Route::get('doctor_report/{branch?}', [ReportController::class, 'doctor'])->name('doctor_report');
    Route::get('doctor_search', [ReportController::class, 'doctorSearch']);

    Route::get('doctorDetailSearch/{id}', [ReportController::class, 'doctorDetailSearch']);
    Route::get('inout_patient', [ReportController::class, 'inout_patient']);
    Route::get('inout_patient/{branch?}', [ReportController::class, 'inout_patient'])->name('inout_patient');
    Route::get('inout_patient_search', [ReportController::class, 'inout_patient_search']);
    Route::get('foc_patient', [ReportController::class, 'foc_patient']);
    Route::get('foc_patient/{branch?}', [ReportController::class, 'foc_patient'])->name('foc_patient');
    Route::get('foc_patient_invoice/{id}', [ReportController::class, 'foc_patient_invoice']);

    Route::get('foc_patient_search', [ReportController::class, 'foc_patient_search']);
    Route::get('patient_invoice/{id}', [ReportController::class, 'patient_invoice']);

    //account location report
    Route::get('account_location_report', [AccountLocationReportController::class, 'index']);
    Route::get('account_location_all_report/{id}', [AccountLocationReportController::class, 'account_location_all_report']);
    Route::get('account_location_general_ledger/{id}', [AccountLocationReportController::class, 'general_ledger']);
    Route::get('account_location_profit_loss/{id}', [AccountLocationReportController::class, 'profit_loss']);
    Route::get('account_location_balance_sheet/{id}', [AccountLocationReportController::class, 'balance_sheet']);
    Route::get('account_location_transaction/{branch}/{id}', [AccountLocationReportController::class, 'AccountTransactions']);
    Route::get('account_location_transaction_payment/{branch}/{id}', [AccountLocationReportController::class, 'TransactionsPayment']);


    //Excel_Item_Export & Import
    Route::get('file-import-export', [ItemController::class, 'fileImportExport']);
    Route::post('file-import', [ItemController::class, 'fileImport'])->name('file-import');
    Route::post('file-update-import', [ItemController::class, 'fileUpdateImport'])->name('file-update-import');
    Route::get('file-export', [ItemController::class, 'fileExport'])->name('file-export');
    Route::get('file-import-template', [ItemController::class, 'fileImportTemplate'])->name('file-import-template');

    //expense
    Route::get('expense', [ExpenseController::class, 'index']);
    Route::post('expense_store', [ExpenseController::class, 'expenseStore']);
    Route::get('expense_edit/{expense}', [ExpenseController::class, 'edit']);
    Route::post('expense_update/{expense}', [ExpenseController::class, 'update']);
    Route::get('expense_delete/{expense}', [ExpenseController::class, 'delete']);
    Route::get('get_part_data-unit-expense', [ExpenseController::class, 'get_part_data_unit-expense'])->name('get.part.data-unit_expense');
    Route::get('/get_accounts_transaction_expense', [ExpenseController::class, 'expense_get_transaction'])->name('get_accounts_transaction_expense');

    Route::get('expense_category', [ExpenseCategoryController::class, 'index']);
    Route::post('expense_category_store', [ExpenseCategoryController::class, 'categoryStore']);
    Route::get('expense_category_edit/{id}', [ExpenseCategoryController::class, 'edit']);
    Route::post('expense_category_update/{id}', [ExpenseCategoryController::class, 'update']);
    Route::get('expense_category_delete/{id}', [ExpenseCategoryController::class, 'delete']);

    //User Type
    Route::get('user_type', [UserTypeController::class, 'index']);
    Route::post('type_store', [UserTypeController::class, 'store']);
    Route::get('user_type_edit/{id}', [UserTypeController::class, 'edit']);
    Route::post('user_type_update/{id}', [UserTypeController::class, 'update']);
    Route::get('user_type_delete/{id}', [UserTypeController::class, 'delete']);

    //Configuration
    Route::get('config_manage', [UserProfileController::class, 'index']);
    Route::get('config', [UserProfileController::class, 'config'])->name('config');
    Route::post('config_store', [UserProfileController::class, 'config_store'])->name('config_store');
    Route::get('config_edit/{id}', [UserProfileController::class, 'edit']);
    Route::get('config_delete/{id}', [UserProfileController::class, 'delete']);
    Route::post('config_update/{id}', [UserProfileController::class, 'config_edit']);
    Route::get('config_details/{id}', [UserProfileController::class, 'details']);

    // Sale Person Assign
    Route::get('sale_person_assign', [WayAssignController::class, 'index']);
    Route::post('sale_person_assign_store', [WayAssignController::class, 'store']);
    Route::get('sale_person_assign_edit/{id}', [WayAssignController::class, 'edit']);
    Route::post('sale_person_assign_update/{id}', [WayAssignController::class, 'update']);
    Route::get('sale_person_assign_delete/{id}', [WayAssignController::class, 'delete']);


    //User
    Route::get('user', [UserController::class, 'user_register'])->name('user');
    Route::post('User_Register', [UserController::class, 'user_store']);
    Route::get('/delete_user/{id}', [UserController::class, 'delete_user']);
    Route::get('/delete_user/{id}', [UserController::class, 'delete_user']);
    Route::get('/userShow/{id}', [UserController::class, 'userShow']);
    Route::post('/update_user/{id}', [UserController::class, 'update_user']);
    Route::get('user_permission/{id}', [UserController::class, 'permission']);
    Route::post('user_permission_store/{id}', [UserController::class, 'permissionStore']);
    Route::post('/drop_table', [ItemController::class, 'drop_table'])->name('drop.table');

    //Brand
    Route::get('brand', [BrandController::class, 'brand']);
    Route::post('brand_store', [BrandController::class, 'brand_store']);
    Route::get('brand_edit/{id}', [BrandController::class, 'brand_edit']);
    Route::post('brand_update/{id}', [BrandController::class, 'brand_update']);
    Route::get('brand_delete/{id}', [BrandController::class, 'brand_delete']);
});
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

require __DIR__ . '/auth.php';
