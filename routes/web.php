<?php

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

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/home', function () {
    $user = Auth::user();

    if($user->hasRole('admin')) {
        return redirect()->route('admin.home');
    }
    else if($user->hasRole('leader')) {
        return redirect()->route('leader.home');
    }
    else {
        throw Exception('Undefined user role');
    }
});

// Auth
Auth::routes();

// Admin
Route::get('/admin/home', 'Admin\HomeController@index')->name('admin.home');
Route::resource('/admin/payments', 'Admin\PaymentController', [
    'as' => 'admin'
])->except([
    'show'
]);
Route::get('/admin/payments/user/{id}', 'Admin\UserController@show')->name('admin.payments.user.show'); // User
Route::post('/admin/payments/{id}', 'Admin\PaymentController@changePaymentStatus')->name('admin.payments.status.change'); // Change wether paid or not
Route::post('/admin/payments/{id}/accounts', 'Admin\PaymentController@approve')->name('admin.payments.account.approve'); // Approve payments
Route::get('/admin/payments/to-pay-back', 'Admin\PaymentController@toPayBack')->name('admin.payments.toPayBack');
Route::resource('/admin/incomes', 'Admin\IncomeController', [
    'as' => 'admin'
])->except([
    'show'
]);
Route::post('/admin/incomes/{id}/approve', 'Admin\IncomeController@approve')->name('admin.incomes.account.approve'); // Approve income

// Leader
Route::get('/leader/home', 'Leader\HomeController@index')->name('leader.home');
Route::resource('/leader/payments', 'Leader\PaymentController', [
    'as' => 'leader'
])->except([
    'index', 'show'
]);
