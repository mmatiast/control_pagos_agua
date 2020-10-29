<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'AppController@welcome');

Auth::routes(['verify' => true, 'register' => false]);

Route::get('clientes/{cliente_id}/pagos/{pago_id}/comprobante', 'PagoController@generarComprobante')->middleware('auth');

Route::get('/{any}', 'AppController@any')->middleware(['auth'/*, 'verified'*/])->where('any', '.*');
