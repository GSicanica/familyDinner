<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClanController;
use App\Http\Controllers\PrijedlogController;

Route::get('/', [ClanController::class, 'index']);
Route::resource('clanovi', ClanController::class);
Route::get('/glasanje', [PrijedlogController::class, 'glasanje']);
Route::post('/glasanje', [PrijedlogController::class, 'glasaj']);
Route::get('/rezultat', [PrijedlogController::class, 'rezultat']);
