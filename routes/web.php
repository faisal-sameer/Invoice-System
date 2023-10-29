<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'prevent-back-history'], function () {

    Auth::routes();
    //  Route::any('/register', function () {
    //      return view('juiceAndResturant.Call');
    //  });

    // Route::get('/send-mail', [HomeController::class, 'mail']);

    Route::get('/', [App\Http\Controllers\HomeController::class, 'LoginPage']);
    Route::get('/Call', [App\Http\Controllers\HomeController::class, 'Call'])->name('Call');
    Route::get('/TestAPIFA', [App\Http\Controllers\HomeController::class, 'TestAPIFA']);
    Route::any('/Home', [App\Http\Controllers\HomeController::class, 'Home'])->name('Home');
    Route::any('/Notification', [App\Http\Controllers\HomeController::class, 'Notification'])->name('Notification');
    Route::any('/RespoenMes', [App\Http\Controllers\HomeController::class, 'RespoenMes'])->name('RespoenMes');
    Route::any('/SendNewMessage', [App\Http\Controllers\HomeController::class, 'SendNewMessage'])->name('SendNewMessage');
    Route::any('/SendMessage', [App\Http\Controllers\HomeController::class, 'SendMessage'])->name('SendMessage');
    Route::any('/PreviousMessages', [App\Http\Controllers\HomeController::class, 'PreviousMessages'])->name('PreviousMessages');
    Route::any('/SentMail', [App\Http\Controllers\HomeController::class, 'SentMail'])->name('SentMail');

    //Admin
    Route::group(['middleware' => ['isAdmin']], function () {

        Route::any('/FAStored', [App\Http\Controllers\AdminController::class, 'FAStored'])->name('FAStored');
    });

    // Owner 
    Route::group(['middleware' => ['isOwners']], function () {
        Route::any('/BillDashboard', [App\Http\Controllers\OwnerController::class, 'BillDashboard'])->name('BillDashboard');
        Route::any('/AdminDashboard', [App\Http\Controllers\OwnerController::class, 'AdminDashboard'])->name('AdminDashboard');
        Route::any('/UpdateUser', [App\Http\Controllers\OwnerController::class, 'UpdateUser'])->name('UpdateUser');
        Route::any('/schedulStaff', [App\Http\Controllers\OwnerController::class, 'schedulStaff'])->name('schedulStaff');

        Route::any('/AdvSearchFollowTheFund', [App\Http\Controllers\OwnerController::class, 'AdvSearchFollowTheFund'])->name('AdvSearchFollowTheFund');
        Route::any('/export', [App\Http\Controllers\OwnerController::class, 'export'])->name('Export');
        Route::get('/AddToMenu', [App\Http\Controllers\OwnerController::class, 'AddToMenu'])->name('AddToMenu');
        Route::any('/Discount', [App\Http\Controllers\OwnerController::class, 'Discount'])->name('Discount');
        Route::any('/CreateDiscount', [App\Http\Controllers\OwnerController::class, 'DiscountCreate'])->name('CreateDiscount');
        Route::any('/DiscountDelete', [App\Http\Controllers\OwnerController::class, 'DeleteDiscount'])->name('DeleteDiscount');

        Route::any('/Stored', [App\Http\Controllers\OwnerController::class, 'Stored'])->name('Stored');
        Route::any('/EditStoreItemName', [App\Http\Controllers\OwnerController::class, 'EditItemStoreName'])->name('EditItemNameStore');
        Route::any('/EditStoreItem', [App\Http\Controllers\OwnerController::class, 'ItemEdit'])->name('EditItem');
        Route::any('/ChangeItem', [App\Http\Controllers\OwnerController::class, 'ItemChangeDelete'])->name('formChangeItem');
        Route::any('/deleteItemStore', [App\Http\Controllers\OwnerController::class, 'deleteFromStore'])->name('deleteStore');
        Route::any('/StoredSave', [App\Http\Controllers\OwnerController::class, 'StoreSave'])->name('SaveStored');
        Route::any('/Create-Categories', [App\Http\Controllers\OwnerController::class, 'NewCategories'])->name('CreateCat');
        Route::any('/extra-topping', [App\Http\Controllers\OwnerController::class, 'extraTopping'])->name('ExtraToppings');
        Route::any('/extra-topping-change', [App\Http\Controllers\OwnerController::class, 'ExtraToppingChange'])->name('changeExtraTopping');
        Route::any('/CustomersOwner', [App\Http\Controllers\OwnerController::class, 'Customers'])->name('CustomersOwner');
        Route::get('/CasherBoardOwner', [App\Http\Controllers\OwnerController::class, 'CasherBoard'])->name('CasherBoardOwner');

        Route::any('/Create-Item', [App\Http\Controllers\OwnerController::class, 'NewItem'])->name('CreateItem');
        Route::any('/expenses', [App\Http\Controllers\OwnerController::class, 'expenses'])->name('expenses');
        Route::any('/expenses-Create', [App\Http\Controllers\OwnerController::class, 'NewExpense'])->name('expensesNew');
        Route::any('/Update-Item', [App\Http\Controllers\OwnerController::class, 'ItemUpdate'])->name('UpdateItem');
        Route::any('/Update-Item-Ingredients', [App\Http\Controllers\OwnerController::class, 'ItemIingredientsUpdate'])->name('UpdateItemIingredients'); //post 
        Route::any('/delete-Item-{id}', [App\Http\Controllers\OwnerController::class, 'deleteItem'])->name('deleteItem');
        Route::any('/delete-Topping-{id}', [App\Http\Controllers\OwnerController::class, 'deleteTopping'])->name('deleteTopping');
        Route::any('/Chart-Item', [App\Http\Controllers\OwnerController::class, 'ItemChart'])->name('ChartItem');
        Route::any('/BarChart', [App\Http\Controllers\OwnerController::class, 'Chart'])->name('BarChart');
        Route::any('/ReceiptPayment', [App\Http\Controllers\OwnerController::class, 'ReceiptPaymentPDF'])->name('ReceiptPaymentPDF'); // post 
        Route::any('/OrderNote', [App\Http\Controllers\OwnerController::class, 'OrderNotePDF'])->name('OrderNotePDF'); // post 
        Route::any('/ReportForStore', [App\Http\Controllers\OwnerController::class, 'ReportForStore'])->name('ReportForStore'); // post 

        Route::any('/DashboardHr', [App\Http\Controllers\OwnerController::class, 'DashboardHr'])->name('DashboardHr');
        Route::any('/AttendanceFollowup', [App\Http\Controllers\OwnerController::class, 'AttendanceFollowup'])->name('AttendanceFollowup');
        Route::any('/VacationRequests', [App\Http\Controllers\OwnerController::class, 'VacationRequests'])->name('VacationRequests');
        Route::any('/vacationAccept', [App\Http\Controllers\OwnerController::class, 'vacationAccepted'])->name('vacationAccept');
        Route::any('/vacationReject', [App\Http\Controllers\OwnerController::class, 'vacationRejected'])->name('vacationReject');
        Route::any('/CreateDocument', [App\Http\Controllers\OwnerController::class, 'CreateDocument'])->name('CreateDocument');
        Route::any('/CreateDocumentBill', [App\Http\Controllers\OwnerController::class, 'CreateBillDocument'])->name('CreateDocumentBill');
        Route::any('/CasherBoardTransfer', [App\Http\Controllers\OwnerController::class, 'CasherBoardTransfer'])->name('CasherBoardTransfer');
        Route::any('/CasherBoardTransferCreate', [App\Http\Controllers\OwnerController::class, 'CasherBoardTransferCreate'])->name('CasherBoardTransferCreate');
    });


    //Casher 
    Route::group(['middleware' => ['isCasher']], function () {
        Route::get('/CasherBoard', [App\Http\Controllers\CasherController::class, 'CasherBoard'])->name('CasherBoard');
        Route::get('/CasherBoardTailors', [App\Http\Controllers\CasherController::class, 'CasherBoardTailors'])->name('CasherBoardTailors');
        Route::get('/search', [App\Http\Controllers\CasherController::class, 'search'])->name('search');

        Route::any('/TailorBill', [App\Http\Controllers\CasherController::class, 'TailorBill'])->name('TailorBill');
        Route::any('/SendCustomer', [App\Http\Controllers\CasherController::class, 'SendToCustomer'])->name('SendCustomer');
        Route::any('/sittailor', [App\Http\Controllers\CasherController::class, 'sittailor'])->name('sittailor');

        Route::any('/PendingBills', [App\Http\Controllers\CasherController::class, 'PendingBills'])->name('PendingBills');
        Route::any('/CloseBill', [App\Http\Controllers\CasherController::class, 'ClosePendingBill'])->name('CloseBill');
        Route::any('/DayOpen', [App\Http\Controllers\CasherController::class, 'openDay'])->name('openDay');
        Route::any('/DayEnd', [App\Http\Controllers\CasherController::class, 'EndDay'])->name('EndDay');
        Route::any('/cancel-Bill', [App\Http\Controllers\CasherController::class, 'cancelBill'])->name('cancelBill'); //post 
        Route::any('/Show-Bill', [App\Http\Controllers\CasherController::class, 'ShowBill'])->name('BillShow');
        Route::any('/Create-Bill', [App\Http\Controllers\CasherController::class, 'CreateBill'])->name('CreateBill'); // post 
        Route::any('/Customers', [App\Http\Controllers\CasherController::class, 'Customers'])->name('Customers');
        Route::any('/billPDFBig', [App\Http\Controllers\CasherController::class, 'billPDFBig'])->name('billPDFBig'); // post 
        Route::any('/billPDFTrans', [App\Http\Controllers\CasherController::class, 'billPDFTrans'])->name('billPDFTrans'); // post 

        Route::any('/DashboardHrForEmp', [App\Http\Controllers\CasherController::class, 'DashboardHrForEmp'])->name('DashboardHrForEmp');
        Route::any('/StaffAttend', [App\Http\Controllers\CasherController::class, 'StaffAttend'])->name('attend');
        Route::any('/Staffleaving', [App\Http\Controllers\CasherController::class, 'Staffleaving'])->name('leaving');
        Route::any('/VacationRequestEmp', [App\Http\Controllers\CasherController::class, 'VacationRequestEmp'])->name('VacationRequestEmp');
        Route::any('/staffvaction', [App\Http\Controllers\CasherController::class, 'RequestVaction'])->name('vaction');
        Route::any('/OtherExpenses', [App\Http\Controllers\CasherController::class, 'OtherExpenses'])->name('OtherExpenses');
        Route::any('/NewOtherExpenses', [App\Http\Controllers\CasherController::class, 'OtherExpensesNew'])->name('NewOtherExpenses');

        Route::any('/AttendanceFollowupForEmp', [App\Http\Controllers\CasherController::class, 'AttendanceFollowupForEmp'])->name('AttendanceFollowupForEmp');

        // Route::any('/ExportBill', [App\Http\Controllers\CasherController::class, 'billExport'])->name('billExport');
        // Route::any('/ExportBillDetail', [App\Http\Controllers\CasherController::class, 'billDetailsExport'])->name('DetailExport');
        // Route::any('/ImportBill', [App\Http\Controllers\CasherController::class, 'billImport'])->name('billImport');
        //Route::get('/ExportAndImport', [App\Http\Controllers\CasherController::class, 'ExportAndImport'])->name('ExportAndImport');
    });
});
