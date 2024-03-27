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
    return view('welcome');
});
Route::get('/userCreate', function () {
    $inputs=[];
    $inputs["name"]='name';
    $inputs["email"]='email';
    $inputs["token"]='token';
    $inputs["expirationMail"]='expirationMail';
    return view('userCreate', $inputs);
});
