<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndonesiaController;

Route::get('/provinsi', [IndonesiaController::class, 'provinsi']);
Route::get('/kota/{provId}', [IndonesiaController::class, 'kota']);
Route::get('/kecamatan/{kotaId}', [IndonesiaController::class, 'kecamatan']);
Route::get('/kelurahan/{kecId}', [IndonesiaController::class, 'kelurahan']);
Route::get('/cuaca/{kota}', [IndonesiaController::class, 'cuaca']);
Route::get('/shalat/{kota}', [IndonesiaController::class, 'shalat']);
