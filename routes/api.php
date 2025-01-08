<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\Dashboard\Faq\FaqController;
use App\Http\Controllers\Dashboard\Product\ProductController;
use App\Http\Controllers\Dashboard\Customer\CustomerController;
use App\Http\Controllers\Dashboard\Product\ProductImageController;
use App\Http\Controllers\Dashboard\Product\ProductCategoryController;

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
Route::prefix('v1/{lang}/admin/customers')->where(['lang' => 'en|ar'])->group(function(){
    Route::get('', [CustomerController::class, 'index']);
    Route::post('create', [CustomerController::class, 'create']);
    Route::get('edit', [CustomerController::class, 'edit']);
    Route::put('update', [CustomerController::class, 'update']);
    Route::delete('delete', [CustomerController::class, 'delete']);
});
Route::prefix('v1/{lang}/admin/faqs')->where(['lang' => 'en|ar'])->group(function(){
    Route::get('', [FaqController::class, 'index']);
    Route::post('create', [FaqController::class, 'create']);
    Route::get('edit', [FaqController::class, 'edit']);
    Route::put('update', [FaqController::class, 'update']);
    Route::delete('delete', [FaqController::class, 'delete']);
});
Route::prefix('v1/{lang}/admin/products')->where(['lang' => 'en|ar'])->group(function(){
    Route::get('', [ProductController::class, 'index']);
    Route::post('create', [ProductController::class, 'create']);
    Route::get('edit', [ProductController::class, 'edit']);
    Route::put('update', [ProductController::class, 'update']);
    Route::delete('delete', [ProductController::class, 'delete']);
});
Route::prefix('v1/{lang}/admin/product-images')->where(['lang' => 'en|ar'])->group(function(){
    Route::get('', [ProductImageController::class, 'index']);
    Route::post('create', [ProductImageController::class, 'create']);
    Route::delete('delete', [ProductImageController::class, 'delete']);
});
Route::prefix('v1/{lang}/admin/product-categories')->where(['lang' => 'en|ar'])->group(function(){
    Route::get('', [ProductCategoryController::class, 'index']);
    Route::post('create', [ProductCategoryController::class, 'create']);
    Route::get('edit', [ProductCategoryController::class, 'edit']);
    Route::put('update', [ProductCategoryController::class, 'update']);
    Route::delete('delete', [ProductCategoryController::class, 'delete']);
});
