<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Storefront;
use App\Livewire\Checkout;
use App\Livewire\Success;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Products;

Route::get('/', Storefront::class);
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/success/{invoice?}', Success::class)->name('success');
Route::get('/login', \App\Livewire\Admin\Login::class)->name('login');
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/settings', Settings::class)->name('admin.settings');
    Route::get('/admin/products', Products::class)->name('admin.products');
    Route::get('/admin/orders', \App\Livewire\Admin\Orders::class)->name('admin.orders');
    Route::get('/admin/coupons', \App\Livewire\Admin\Coupons::class)->name('admin.coupons');
    Route::get('/admin/orders/{id}/receipt', function ($id) {
        $order = \App\Models\Order::with('items')->findOrFail($id);
        $settings = \App\Models\StoreSetting::first();
        return view('admin.receipt', compact('order', 'settings'));
    })->name('admin.orders.receipt');
});

Route::get('/order/{id}/receipt', function ($id) {
    $order = \App\Models\Order::with('items')->findOrFail($id);
    $settings = \App\Models\StoreSetting::first();
    return view('admin.receipt', compact('order', 'settings'));
})->name('order.receipt');

Route::post('/admin/set-view-mode', function (\Illuminate\Http\Request $request) {
    session(['view_mode' => $request->mode]);
    return back();
});
