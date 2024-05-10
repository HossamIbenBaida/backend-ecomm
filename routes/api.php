<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\productController;
use App\Http\Controllers\Infuencer\ProductController as InfuencerProductController;

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

// Route::get('users', [UserController::class , 'users']);
// Route::post('users',[UserController::class , 'store']);
// Route::get('users/{id}',[UserController::class , 'show']);
// Route::put('users/{id}',[UserController::class , 'update']);
// Route::delete('users/{id}',[UserController::class ,'destroy']);
Route::post('login',[AuthController::class , 'login']);
Route::post('register',[AuthController::class , 'register']);

// Route::middleware(['auth:api'])->group(function () {
//     Route::post('logout',[AuthController::class , 'logout']);
//     Route::get('user',[UserController::class , 'user']);
//     Route::post('upload',[ImageController::class , 'upload']);
//     Route::put('users/info',[UserController::class , 'updateInfo']);
//     Route::put('users/password',[UserController::class , 'updatePassword']);
//     Route::apiResource('users',UserController::class);
//     Route::apiResource('roles',RoleController::class);
//     Route::apiResource('products', productController::class);
//     Route::apiResource('orders', OrderController::class)->only('index','show');
//     Route::apiResource('permissions', PermissionController::class)->only('index');
//     Route::get('export',[OrderController::class , 'export']);
//     Route::get('chart',[DashboardController::class , 'chart']);
    
// });

//commun routes
Route::middleware(['auth:api'])->group(function () { 
    Route::post('logout',[AuthController::class , 'logout']);
    Route::put('users/info',[AuthController::class , 'updateInfo']);
    Route::get('user',[AuthController::class , 'user']);
    Route::put('users/password',[AuthController::class , 'updatePassword']);
});
Route::middleware(['auth:api','scope:admin'])->prefix('admin')->group(function () {     
        
        Route::post('upload',[ImageController::class , 'upload']);
        Route::apiResource('users',UserController::class);
        Route::apiResource('roles',RoleController::class);
        Route::apiResource('products', productController::class);
        Route::apiResource('orders', OrderController::class)->only('index','show');
        Route::apiResource('permissions', PermissionController::class)->only('index');
        Route::get('export',[OrderController::class , 'export']);
        Route::get('chart',[DashboardController::class , 'chart']);
        
    })->namespace('Admin');




    Route::prefix('infuencer')->group(function () {     
        Route::get('products',[InfuencerProductController::class , 'index']);    
        Route::middleware(['auth:api','scope:influencer'])->group(function(){

        });
    })->namespace('Influencer');