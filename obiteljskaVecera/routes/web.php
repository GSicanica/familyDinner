<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClanController;
use App\Http\Controllers\JeloController;
use App\Http\Controllers\PrijedlogController;

// Route for the home page
Route::get('/', [ClanController::class, 'index'])->name('home');

// Routes for managing members (Clanovi)
Route::get('/clanovi/create', [ClanController::class, 'create'])->name('clanovi.create');
Route::post('/clanovi', [ClanController::class, 'store'])->name('clanovi.store');
Route::get('/clanovi/{clan}/edit', [ClanController::class, 'edit'])->name('clanovi.edit');
Route::put('/clanovi/{clan}', [ClanController::class, 'update'])->name('clanovi.update');

// Routes for managing dishes (Jela)
Route::get('/jela/create', [JeloController::class, 'create'])->name('jela.create');
Route::post('/jela', [JeloController::class, 'store'])->name('jela.store');
Route::get('/jela/{jelo}/edit', [JeloController::class, 'edit'])->name('jela.edit');
Route::put('/jela/{jelo}', [JeloController::class, 'update'])->name('jela.update');

// Routes for the voting process (Glasanje)
Route::get('/glasanje', [PrijedlogController::class, 'glasanje'])->name('prijedlozi.glasanje');
Route::post('/glasanje', [PrijedlogController::class, 'glasaj'])->name('prijedlozi.glasaj');
Route::get('/rezultat', [PrijedlogController::class, 'rezultat'])->name('prijedlozi.rezultat');
Route::post('/resetiraj-glasanje', [PrijedlogController::class, 'resetiraj'])->name('prijedlozi.resetiraj');
