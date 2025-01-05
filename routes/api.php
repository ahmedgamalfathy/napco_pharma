<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\User\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('v1/admin/auth')->group(function(){
    // Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/logout',[AuthController::class,'logout']);
});
Route::prefix('v1/{lang}/admin/users')->where(['lang'=>'en|ar'])->group(function(){
    Route::get('',[UserController::class,'index']);
    Route::get('edit',[UserController::class,'edit']);
    Route::put('update',[UserController::class,'update']);
    Route::post('create',[UserController::class,'create']);
    Route::delete('delete',[UserController::class,'delete']);
    Route::post('change-status', [UserController::class, 'changeUserStatus']);
});
