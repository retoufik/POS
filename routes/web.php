<?php

use App\Http\Controllers\authController;
use App\Livewire\{Main, Order, TableMap};
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Models\Commande;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

Route::get('/', function () {
    return redirect()->route('main');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [authController::class,'login'])->name('login.post');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [authController::class,'register'])->name('register.post');
Route::post('/logout',[authController::class,'logout'])->name('logout');
    Route::get('/main', function(){
        return view('app');
    })->name('main');
    Route::get('/tables', function(){
        return view('table');
    })->name('tables');
    Route::get('/order', function(){
        return view('main');
    })->name('order');
    Route::get('/order/{commande}', Order::class)->name('order.show');


Route::get('/orders/pdf/{order}', function (Commande $order) {
    return PDF::loadView('pdf.order', compact('order'))->stream();
})->name('orders.pdf');