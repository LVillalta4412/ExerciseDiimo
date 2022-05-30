<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;
//Aqui podremos verificar las rutas que contendrá nuestra API.

Route::group([
    'middleware' => ['jwt.verify'],
    'prefix' => 'v1/diimo'

], function ($router) {

/*rutas Users*/
/* Route::get('/user', [UserController::class, 'index']); */
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);
Route::put('/user/{id}', [UserController::class, 'update']);


/*rutas products*/
Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/search/{filter}', [ProductsController::class, 'search']);
Route::get('/products/{id}', [ProductsController::class, 'show']);
Route::post('/products', [ProductsController::class, 'store']);
Route::delete('/products/{id}', [ProductsController::class, 'destroy']);
Route::put('/products/{id}', [ProductsController::class, 'update']);

});

//rutas para login, register y logout.
Route::post('/v1/diimo/login', [UserController::class, 'authenticate']);
Route::post('/v1/diimo/register', [UserController::class, 'register']);
Route::post('/v1/diimo/logout', [UserController::class, 'logout']);

//rutas recuperacion de password
Route::post('/v1/diimo/update_password/{token}', [UserController::class, 'updatePassword']);
Route::post('/v1/diimo/recover_password', [UserController::class, 'recover_password']);
Route::post('/v1/diimo/time_recover_password', [UserController::class, 'time_recover_password']);
