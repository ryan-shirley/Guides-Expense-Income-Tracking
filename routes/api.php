<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['sentry.context'])->group(function () {
    Route::middleware('auth:api')->post('payment', 'API\Leader\PaymentController@store');
    Route::middleware('auth:api')->get('payments', 'API\Admin\PaymentController@index');
    Route::middleware('auth:api')->get('payments/export', 'API\Admin\PaymentController@export');
    Route::middleware('auth:api')->get('incomes/export', 'API\Admin\IncomeController@export');
    Route::middleware('auth:api')->get('bank-transactions', 'API\Admin\BankTransactionsController@index');
});
