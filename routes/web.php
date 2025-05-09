<?php

use Illuminate\Support\Facades\Route;
use App\Models\Commande;
use Barryvdh\DomPDF\Facade\Pdf as PDF;



Route::middleware('auth')->group(function() {
    Route::view('/','app')->name('app');
    Route::view('/order','main')->name('order');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/login', [\App\Http\Controllers\authController::class, 'login'])->name('login.post');
Route::post('/register', [App\Http\Controllers\authController::class, 'register'])->name('register.post');
Route::post('/logout', [App\Http\Controllers\authController::class, 'logout'])->name('logout');
Route::get('/orders/pdf/{order}', function (Commande $order) {
    return PDF::loadView('pdf.order', compact('order'))->stream();
})->name('orders.pdf');