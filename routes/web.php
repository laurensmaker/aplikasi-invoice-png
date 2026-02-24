<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('backend.dasbor');
})->name('dasbor');
Route::resource('invoice', App\Http\Controllers\InvoiceController::class);
Route::resource('packing-list', App\Http\Controllers\PackingListController::class);
Route::post('invoice-header', [InvoiceController::class, 'storeHeader'])->name('invoice.storeHeader');
Route::post('invoice-item', [InvoiceController::class, 'storeItem'])->name('invoice.storeItem');
