<?php

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
Route::group(['prefix'=>'v1'],function(){
    Route::post('/users',[App\Http\Controllers\UserController::class,'register']);
    Route::post('/users/login',[App\Http\Controllers\UserController::class,'login']);

    Route::middleware(App\Http\Middleware\ApiAuthMiddleware::class)->group(function (){
        Route::get('/users/profile',[App\Http\Controllers\UserController::class,'get']);
        Route::get('/users/profile/contacts',[App\Http\Controllers\UserController::class,'getContacts']);
        Route::patch('/users/profile',[App\Http\Controllers\UserController::class,'update']);
        Route::delete('/users/logout',[App\Http\Controllers\UserController::class,'logout']);
        Route::post('/users/refresh',[App\Http\Controllers\UserController::class,'refresh']);
    });

    Route::get('/contacts/{id}', [App\Http\Controllers\ContactsController::class, 'get']);
});


