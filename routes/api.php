<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Auth
Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::get('loginCheck', [App\Http\Controllers\Api\AuthController::class, 'CheckLogin']);
// End Auth 
// Casher 

Route::get('ScheduleCheck', [App\Http\Controllers\Api\CasherController::class, 'CheckSchedule']);
Route::any('BoxCheck', [App\Http\Controllers\Api\CasherController::class, 'CheckBoxs']);


// End Casher 

Route::group(['middleware' => ['jwt.auth']], function () {
});
Route::get('items', [App\Http\Controllers\Api\CasherController::class, 'items']);
Route::post('DayOpenBox', [App\Http\Controllers\Api\CasherController::class, 'OpenBoxForDay']);
Route::post('closeBox', [App\Http\Controllers\Api\CasherController::class, 'closeBoxForDay']);
Route::get('AllBills',[App\Http\Controllers\Api\CasherController::class, 'BillsAll']);
Route::get('info',[App\Http\Controllers\Api\CasherController::class, 'infoCustomer']);
Route::get('expenses',[App\Http\Controllers\Api\CasherController::class, 'expenses']);
Route::post('NewExp',[App\Http\Controllers\Api\CasherController::class, 'NewExp']);

Route::get('BillsPending',[App\Http\Controllers\Api\CasherController::class, 'PendingBill']);
Route::post('confirmBill',[App\Http\Controllers\Api\CasherController::class, 'BillConfirm']);
Route::post('deleteBill',[App\Http\Controllers\Api\CasherController::class, 'BillDelete']);


//
Route::post('createBillApp', [App\Http\Controllers\Api\CasherController::class, 'createBill']);
// test 
Route::get('/testAPI', [App\Http\Controllers\Api\CasherController::class, 'testApi']);
