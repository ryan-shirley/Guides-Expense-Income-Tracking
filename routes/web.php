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
Route::get('/admin/home/user/{id}', 'Admin\UserController@show')->name('admin.user.show'); // User
Route::resource('/admin/payment', 'Admin\PaymentController', [
    'as' => 'admin'
]);
Route::post('/admin/home/payment/{id}', 'Admin\PaymentController@changePaymentStatus')->name('admin.payment.status.change');
Route::post('/admin/home/payment/{id}/accounts', 'Admin\PaymentController@changeAccountStatus')->name('admin.payment.account.change');

// Leader
Route::get('/leader/home', 'Leader\HomeController@index')->name('leader.home');
Route::resource('/leader/payment', 'Leader\PaymentController', [
    'as' => 'leader'
]);
