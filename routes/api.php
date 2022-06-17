<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;

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

Route::middleware('auth:sanctum')->get('/user',function(Request $request){
    return $request->user();
});

Route::post('login',[AuthController::class,'login']); 

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('registrarUsuario',[AuthController::class,'registrarUsuario']);
    Route::get('logout',[AuthController::class,'logout']);
});


Route::post('listarProductos',[ProductoController::class,'listarProductos']);
Route::post('crearProducto',[ProductoController::class,'crearProducto']);




    
