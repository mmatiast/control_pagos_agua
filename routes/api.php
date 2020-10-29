<?php

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

Route::middleware('auth:api')->get('/user', 'UserController@user');

Route::group(['prefix' => 'estadistica', 'middleware' => 'auth:api'], function(){
	Route::get('conteo/{tabla}/{columna}/{valor}', 'EstadisticaController@obtenerEstadistica')->name('estadistica.obtener.conteo.condicion');
	Route::get('conteo/{tabla}', 'EstadisticaController@obtenerCantidadDeRegistros')->name('estadistica.obtener.conteo.tablas');
	Route::get('clientes-morosos', 'EstadisticaController@morosos');
});

Route::group(['prefix'=> 'users', 'middleware' => 'auth:api'], function() {
	Route::get('', 'UserController@index');
	Route::post('', 'UserController@store');
	Route::get('{id}', 'UserController@show');
	Route::put('{id}', 'UserController@update');
	Route::delete('{id}', 'UserController@delete');
	Route::put('{id}/restore', 'UserController@restore');
});

Route::group(['prefix'=> 'roles', 'middleware' => 'auth:api'], function() {
	Route::get('', 'RoleController@index');
	Route::post('', 'RoleController@store');
	Route::get('{id}', 'RoleController@show');
	Route::put('{id}', 'RoleController@update');
	Route::delete('{id}', 'RoleController@delete');
	Route::put('{id}/restore', 'RoleController@restore');
});

Route::group(['prefix'=> 'bancos', 'middleware' => 'auth:api'], function() {
	Route::get('', 'BancoController@index');
	Route::post('', 'BancoController@store');
	Route::get('{id}', 'BancoController@show');
	Route::put('{id}', 'BancoController@update');
	Route::delete('{id}', 'BancoController@delete');
	Route::put('{id}/restore', 'BancoController@restore');
});

Route::group(['prefix'=> 'formas-de-pago', 'middleware' => 'auth:api'], function() {
	Route::get('', 'FormaPagoController@index');
	Route::post('', 'FormaPagoController@store');
	Route::get('{id}', 'FormaPagoController@show');
	Route::put('{id}', 'FormaPagoController@update');
	Route::delete('{id}', 'FormaPagoController@delete');
	Route::put('{id}/restore', 'FormaPagoController@restore');
});

Route::group(['prefix'=> 'clientes', 'middleware' => 'auth:api'], function() {
	Route::get('search', 'ClienteController@search');
	Route::get('', 'ClienteController@index');
	Route::post('', 'ClienteController@store');
	Route::get('{id}', 'ClienteController@show');
	Route::put('{id}', 'ClienteController@update');
	Route::delete('{id}', 'ClienteController@delete');
	Route::put('{id}/restore', 'ClienteController@restore');

	Route::get('{id}/contadores', 'ContadorController@index');
	Route::post('{id}/contadores', 'ContadorController@store');
	Route::get('{id}/contadores/{contador_id}', 'ContadorController@show');
	Route::put('{id}/contadores/{contador_id}', 'ContadorController@update');

	Route::get('{id}/contadores/{contador_id}/consumos', 'ClienteController@consumos');
	Route::get('{id}/contadores/{contador_id}/pagos', 'ClienteController@pagos');
});


Route::group(['prefix'=> 'contadores', 'middleware' => 'auth:api'], function() {
	Route::get('{id}', 'ConsumoController@contadores');
	Route::post('{id}/consumo', 'ConsumoController@store');
	Route::get('{id}/consumos', 'ConsumoController@index');
});

Route::group(['prefix'=> 'consumos', 'middleware' => 'auth:api'], function() {
	Route::get('{id}', 'ConsumoController@show');
	Route::post('{id}', 'ConsumoController@update');
	Route::post('{id}/pago', 'PagoController@store');
	Route::get('{id}/pagos', 'PagoController@index');
});

Route::group(['prefix'=> 'pagos', 'middleware' => 'auth:api'], function() {
	Route::get('{id}', 'PagoController@show');
	Route::post('{id}', 'PagoController@update');
});

Route::get('clientes-morosos', 'ReporteController@morosos')->middleware('auth:api');

