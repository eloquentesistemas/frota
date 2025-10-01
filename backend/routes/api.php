<?php


use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\UsuariosController;

use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'], function ($router) {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::post('me', [UserController::class, 'me']);


});



Route::group([
    'middleware' => 'auth'],
    function ($router) {
        Route::get('motorista/list', [PessoaController::class, 'findMotorista']);
        Route::get('cliente/list', [PessoaController::class, 'findCliente']);
        Route::get('fornecedor/list', [PessoaController::class, 'findFornecedor']);
        Route::get('veiculos/list', [\App\Http\Controllers\VeiculoController::class, 'find']);
        Route::get('cidade/list', [\App\Http\Controllers\CidadeController::class, 'find']);
        Route::get('natureza_financeiras/list', [\App\Http\Controllers\NaturezaFinanceiraController::class, 'find']);

        Route::get('pagamentos/detalhes/{conta_id}', [\App\Http\Controllers\PagamentoController::class, 'detalhes']);




        Route::resource('users', UsuariosController::class)->except(['create', 'edit']);
        Route::resource('cidades', \App\Http\Controllers\CidadeController::class)->except(['create', 'edit']);
        Route::resource('pessoas', \App\Http\Controllers\PessoaController::class)->except(['create', 'edit']);
        Route::resource('pessoa_veiculos', \App\Http\Controllers\PessoaVeiculoController::class)->except(['create', 'edit']);

        Route::resource('veiculos', \App\Http\Controllers\VeiculoController::class)->except(['create', 'edit']);
        Route::resource('pneus_veiculos', \App\Http\Controllers\PneusVeiculoController::class)->except(['create', 'edit']);
        Route::resource('abastecimento_veiculos', \App\Http\Controllers\AbastecimentoVeiculoController::class)->except(['create', 'edit']);

        Route::resource('pagamentos', \App\Http\Controllers\PagamentoController::class)->except(['create', 'edit']);
        Route::resource('natureza_financeiras', \App\Http\Controllers\NaturezaFinanceiraController::class)->except(['create', 'edit']);
        Route::resource('faturamentos', \App\Http\Controllers\FaturamentoController::class)->except(['create', 'edit']);
        Route::resource('contas', \App\Http\Controllers\ContaController::class)->except(['create', 'edit']);

        Route::resource('calibracaos', \App\Http\Controllers\CalibracaoController::class)->except(['create', 'edit']);
        Route::resource('lubrificacaos', \App\Http\Controllers\LubrificacaoController::class)->except(['create', 'edit']);


    });



