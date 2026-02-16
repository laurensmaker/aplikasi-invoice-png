<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('backend.dasbor');
})->name('dasbor');
