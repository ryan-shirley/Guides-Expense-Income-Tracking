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
    $greetings = "";

    /* This sets the $time variable to the current hour in the 24 hour clock format */
    $time = date("H");

    /* Set the $timezone variable to become the current timezone */
    $timezone = date("e");

    /* If the time is less than 1200 hours, show good morning */
    if ($time < "12") {
        $greetings = "Good morning";
    } else

    /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
    if ($time >= "12" && $time < "17") {
        $greetings = "Good afternoon";
    } else

    /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
    if ($time >= "17" && $time < "19") {
        $greetings = "Good evening";
    } else

    /* Finally, show good night if the time is greater than or equal to 1900 hours */
    if ($time >= "19") {
        $greetings = "Good night";
    }

    return view('welcome', compact('greetings')); 
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
