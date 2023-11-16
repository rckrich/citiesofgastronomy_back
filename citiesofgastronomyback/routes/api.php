<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitiesContoller;

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


Route::get('home', [HomeController::class, 'home']);
Route::get('cities', [CitiesContoller::class, 'list']);
Route::post('cities/find/{id}', [CitiesContoller::class, 'find']);
Route::post('cities/store/', [CitiesContoller::class, 'store']);
Route::post('cities/update/{id}', [CitiesContoller::class, 'update']);
Route::post('cities/updateCompleteInfo/{id}', [CitiesContoller::class, 'updateComplete']);
Route::post('cities/delete/{id}', [CitiesContoller::class, 'find']);

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});//*/
