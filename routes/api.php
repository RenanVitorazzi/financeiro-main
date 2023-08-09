<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\RecebimentosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('troca_cheques', [TrocaChequeController::class, 'trocar'])->name('trocar');
Route::get('clientes/procurar', [ClienteController::class, 'procurarCliente'])->name('procurarCliente');
Route::get('contas/procurar', [ContaController::class, 'procurarContas'])->name('procurarContas');

// Route::get('/enviarContaCorrente', [ClienteController::class, 'todosClientes'])->name('todosClientes');
