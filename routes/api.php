<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('crearCuenta', [AuthController::class, 'crearCuenta']);
Route::post('loginAgricultor', [AuthController::class, 'loginAgricultor']);
Route::post('login', [AuthController::class, 'login']);
//Route::post('crearCuenta','App\Http\Controllers\AgricultorController@crearCuenta');

Route::middleware(['auth:api'])->group(function () {
    Route::post('testConectividad','App\Http\Controllers\AgricultorController@testConectividad');
    Route::post('confirmarCuenta','App\Http\Controllers\AgricultorController@confirmarCuenta');
    Route::post('confirmarPiloto','App\Http\Controllers\AgricultorController@confirmarPiloto');
    Route::post('confirmarTransporte','App\Http\Controllers\AgricultorController@confirmarTransporte');
    Route::post('envioCargamento','App\Http\Controllers\AgricultorController@envioCargamento');
    Route::post('estadoCargamento','App\Http\Controllers\AgricultorController@estadoCargamento');
    Route::post('listadoCargamentos','App\Http\Controllers\AgricultorController@listadoCargamentos');
    Route::post('listadoParcialidades','App\Http\Controllers\AgricultorController@listadoParcialidades');
    Route::post('recibirParcialidad','App\Http\Controllers\AgricultorController@recibirParcialidad');
    Route::post('infoParcialidad','App\Http\Controllers\AgricultorController@infoParcialidad');
    Route::post('infoPesoParcialidad','App\Http\Controllers\AgricultorController@infoPesoParcialidad');
    Route::post('certificarPesoParcialidad','App\Http\Controllers\AgricultorController@certificarPesoParcialidad');
});

/* //Rutas de Agricultor
Route::post('testConectividad','App\Http\Controllers\AgricultorController@testConectividad');
Route::post('crearCuenta','App\Http\Controllers\AgricultorController@crearCuenta');
Route::post('confirmarCuenta','App\Http\Controllers\AgricultorController@confirmarCuenta');
Route::post('confirmarPiloto','App\Http\Controllers\AgricultorController@confirmarPiloto');
Route::post('confirmarTransporte','App\Http\Controllers\AgricultorController@confirmarTransporte');
Route::post('envioCargamento','App\Http\Controllers\AgricultorController@envioCargamento');
Route::post('estadoCargamento','App\Http\Controllers\AgricultorController@estadoCargamento');
 */
//Rutas de Peso Cabal
Route::post('consultaTransporte','App\Http\Controllers\PesoCabalController@consultaTransporte');
Route::post('certificacionPesaje','App\Http\Controllers\PesoCabalController@certificacionPesaje');

//Protegidas por token
/* Route::middleware('jwt.verify')->group(function(){
    Route::post('index', [UserController::class, 'index']);
}); */

Route::get('/agricultores', 'App\Http\Controllers\AgricultorController@index'); //traer todos los agricultores
Route::post('/agricultores', 'App\Http\Controllers\AgricultorController@store');  //Registrar un agricultor
Route::put('/agricultores/{id}', 'App\Http\Controllers\AgricultorController@update');  //Actualizar un agricultor
Route::delete('/agricultores/{id}', 'App\Http\Controllers\AgricultorController@destroy');  //Eliminar un agricultor
