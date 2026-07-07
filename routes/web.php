<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

Route::get('/skins/{skin}/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/skins/{skin}/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/track-order', [OrderController::class, 'track'])->name('orders.track');
Route::get('/order/{orderCode}', [OrderController::class, 'show'])->name('orders.show');

/*
|--------------------------------------------------------------------------
| Admin Panel — /admin-{hash}/*
|--------------------------------------------------------------------------
| The prefix comes from ADMIN_URL_HASH in .env. Always generate admin URLs
| with named routes (route('admin.*')) so the hash can be rotated freely.
*/
Route::prefix('admin-'.config('app.admin_url_hash'))->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [Admin\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [Admin\AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');

        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::patch('/orders/{order}/approve', [Admin\OrderController::class, 'approve'])->name('orders.approve');
        Route::patch('/orders/{order}/cancel', [Admin\OrderController::class, 'cancel'])->name('orders.cancel');
        Route::resource('orders', Admin\OrderController::class)->except(['create', 'store']);

        Route::resource('skins', Admin\SkinController::class)->except(['show']);

        Route::get('/settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});
