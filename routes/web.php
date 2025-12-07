<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndonesiaController;

Route::get('/indonesia', function () {
    return view('indonesia');
});


Route::get('/', function () {
    return view('welcome');
});
