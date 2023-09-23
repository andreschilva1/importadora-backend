<?php

use App\Http\Controllers\Api\AuthApiController;
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
            'photo_path' => $user->profile_photo_path,
        ]);
    });
    
});