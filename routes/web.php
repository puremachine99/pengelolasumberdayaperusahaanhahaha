<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrOrderController;
use App\Http\Controllers\GoogleController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/table/{table}', [QrOrderController::class, 'showForm']);
Route::post('/table/{table}', [QrOrderController::class, 'startOrder']);
Route::get('/order/{order}', [QrOrderController::class, 'showMenu'])->name('qr.menu');
Route::post('/order/{order}/submit', [QrOrderController::class, 'submitOrder']);
Route::get('/orders/{order}/invoice', [QrOrderController::class, 'showInvoice'])->name('orders.invoice');


// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
