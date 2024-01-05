<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitiesContoller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MainSiteContentController;
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
Route::post('cities', [CitiesContoller::class, 'list']);
Route::post('citiesStore', [CitiesContoller::class, 'citiesStore']);
Route::post('citiesUpdate', [CitiesContoller::class, 'citiesUpdate']);
Route::get('cities/find/{id}', [CitiesContoller::class, 'find']);
Route::post('cities/store/', [CitiesContoller::class, 'store']);
Route::post('cities/update/{id}', [CitiesContoller::class, 'update']);
Route::post('cities/updateCompleteInfo/{id}', [CitiesContoller::class, 'updateComplete']);
Route::post('cities/delete/{id}', [CitiesContoller::class, 'delete']);

Route::post('banners/store/', [BannersController::class, 'store']);
Route::post('banners/delete/', [BannersController::class, 'delete']);
Route::post('banners/update/', [BannersController::class, 'update']);

Route::post('addPDF', [FileController::class, 'store']);

Route::post('resize', [CitiesContoller::class, 'resise']);

Route::get('mainSiteContent/home', [MainSiteContentController::class, 'home']);
Route::post('mainSiteContent/linkStore', [MainSiteContentController::class, 'linkStore']);
Route::post('mainSiteContent/clustersave', [MainSiteContentController::class, 'clustersave']);

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});//*/
