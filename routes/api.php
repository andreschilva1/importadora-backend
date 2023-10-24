<?php

use App\Http\Controllers\Api\AlmacenController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PaqueteController;
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

Route::post('/login', [AuthApiController::class, 'login'])->name('api.login');

//Cliente
Route::post('createClient', [ClientController::class, 'createClient']);




Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('logout', [AuthApiController::class, 'logout'])->name('api.logout');    
    
    Route::get('/user', function (Request $request) {
        $user =  $request->user();
        
        return response()->json([
            'status' => 'success',
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'rol'=> $user->getRoleNames()->first(),
            'celular' => $user->celular,
            'photo_path' => $user->profile_photo_url,
        ]);
    });
    //Almacén
    Route::get('obtenerAlmacenes', [AlmacenController::class, 'obtenerAlmacenes']);
    Route::post('crearAlmacen', [AlmacenController::class, 'crearAlmacen']);
    Route::delete('eliminarAlmacen', [AlmacenController::class, 'eliminarAlmacen']);
    Route::patch('editAlmacen', [AlmacenController::class, 'editAlmacen']);
    
    //Empleado
    Route::get('getEmployees', [UserController::class, 'getEmployees']);
    Route::post('createEmployee', [UserController::class, 'createEmployee']);
    Route::delete('deleteEmployee', [UserController::class, 'deleteEmployee']);
    Route::patch('editEmployee', [UserController::class, 'editEmployee']);
    
    //Cliente
    Route::patch('editClient', [ClientController::class, 'editClient']);
    Route::post('getClienteByCasillero', [ClientController::class, 'getClientByCasillero']);
    
    //paquete
    Route::post('/createPaquete', [PaqueteController::class, 'createPaquete'])->name('api.createPaquete');
    Route::post('/reconocerPaquete', [PaqueteController::class, 'reconocerPaquete'])->name('api.reconocerPaquete');
}); 