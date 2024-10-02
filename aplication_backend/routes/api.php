<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Middleware para obtener la información del usuario autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user(); // Retorna el usuario autenticado
});

// Rutas para autenticación
Route::group([
    'middleware' => 'api', // Aplica el middleware de API
    'prefix' => 'auth' // Prefijo para las rutas de autenticación
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login'); // Ruta para iniciar sesión
    Route::post('logout', 'App\Http\Controllers\AuthController@logout'); // Ruta para cerrar sesión
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh'); // Ruta para refrescar el token
    Route::post('register', 'App\Http\Controllers\AuthController@register'); // Ruta para registrar un nuevo usuario
    Route::post('me', 'App\Http\Controllers\AuthController@me'); // Ruta para obtener información del usuario autenticado
});

// Proteger las rutas de notas con autenticación
Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('notes', NotesController::class); // Ruta para manejar las notas (CRUD)
});
