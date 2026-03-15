<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AccountController, TransactionController};
use Laravel\Fortify\Features;

Route::get('/', function(){
    if (auth()->check()) {
        return redirect('/transactions');
    }
    return redirect('/login');
});

Route::redirect('/', '/login')->name('home');
Route::redirect('/dashboard', '/transactions')->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::resource('accounts', AccountController::class);
    Route::patch('accounts/{account}/restore', [AccountController::class, 'restore'])
        ->name('accounts.restore')
        ->withTrashed();

    Route::resource('transactions', TransactionController::class)->shallow();
    Route::patch('transactions/{transaction}/restore', [TransactionController::class, 'restore'])
        ->name('transactions.restore')
        ->withTrashed(); 
    Route::put('transactions/{transaction}/togglePaid', [TransactionController::class, 'togglePaid'])
        ->name('transactions.togglePaid');
    Route::delete('transactions/{transaction}/forceDestroy', [TransactionController::class, 'forceDestroy'])
        ->name('transactions.forceDestroy');

});


require __DIR__.'/settings.php';

