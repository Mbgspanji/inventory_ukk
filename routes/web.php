<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('categories', CategoryController::class);
    Route::get('items/export', [ItemController::class, 'export'])->name('items.export');
    Route::resource('items', ItemController::class);
    Route::get('items/{item}/history', [ItemController::class, 'history'])->name('items.history');
    
    Route::get('lendings/export', [LendingController::class, 'export'])->name('lendings.export');
    Route::resource('lendings', LendingController::class);
    Route::post('lendings/{lending}/return', [LendingController::class, 'returnItem'])->name('lendings.return');
    
    Route::middleware('can:admin')->group(function () {
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::resource('users', UserController::class);
    });
    
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
