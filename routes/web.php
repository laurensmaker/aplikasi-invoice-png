<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PackingListController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('backend.dasbor');
})->name('dasbor');
Route::resource('invoice', App\Http\Controllers\InvoiceController::class);
Route::resource('packing-list', App\Http\Controllers\PackingListController::class);
Route::post('invoice-header', [InvoiceController::class, 'storeHeader'])->name('invoice.storeHeader');
Route::post('invoice-item', [InvoiceController::class, 'storeItem'])->name('invoice.storeItem');
// routes/web.php - pastikan ada route ini:
Route::get('/invoice/{id}/edit', [InvoiceController::class, 'edit'])->name('invoice.edit');
Route::put('/invoice/{id}', [InvoiceController::class, 'update'])->name('invoice.update');
Route::delete('/invoice/item/{id}', [InvoiceController::class, 'destroyItem'])->name('invoice.item.destroy');

// routes/web.php
Route::get('/invoice/{id}/print', [InvoiceController::class, 'printPdf'])->name('invoice.print');

Route::post('/packinglist', [PackingListController::class, 'store'])->name('packinglist.store');

Route::get('/packing-list/{id}/print', [PackingListController::class, 'printPdf'])->name('packing-list.print');
Route::delete('/packing-list/{id}', [PackingListController::class, 'destroy'])->name('packing-list.destroy');
