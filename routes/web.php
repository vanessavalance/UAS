<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home', [
        "app_name" => env('APP_NAME'),
        "copyright" => "2024",
        "title" => "Home",
    ]) ;
});

Route::get('/about', function () {
    return "hello word";
});

Route::get('/login', function () {
    return "hello word";
});

Route::get('/register', function () {
    return "hello word";
});
