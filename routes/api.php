<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('TestConectividad','App\Http\Controllers\CategoriaController@TestConectividad');
Route::post('CrearCuenta','App\Http\Controllers\CategoriaController@CrearCuenta');


Route::get('/agricultores', 'App\Http\Controllers\AgricultorController@index'); //traer todos los agricultores
Route::post('/agricultores', 'App\Http\Controllers\AgricultorController@store');  //Registrar un agricultor
Route::put('/agricultores/{id}', 'App\Http\Controllers\AgricultorController@update');  //Actualizar un agricultor
Route::delete('/agricultores/{id}', 'App\Http\Controllers\AgricultorController@destroy');  //Eliminar un agricultor

