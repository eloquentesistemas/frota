<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/relatorios/clientes', [\App\Http\Controllers\RelatorioController::class, 'cliente'])->name('clientes.index');
Route::get('/relatorios/motoristas', [\App\Http\Controllers\RelatorioController::class, 'motorista'])->name('motorista.index');
Route::get('/relatorios/veiculos', [\App\Http\Controllers\RelatorioController::class, 'veiculos'])->name('veiculos.index');
Route::get('/relatorios/faturamentos', [\App\Http\Controllers\RelatorioController::class, 'faturamento'])->name('faturamento.index');
Route::get('/relatorios/financeiro', [\App\Http\Controllers\RelatorioController::class, 'financeiro'])->name('financeiro.index');
