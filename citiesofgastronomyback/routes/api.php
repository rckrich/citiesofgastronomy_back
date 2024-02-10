<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitiesContoller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MainSiteContentController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\InitiativesController;
use App\Http\Controllers\ToursController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\TastierLifeController;
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
Route::post('cities/store', [CitiesContoller::class, 'store']);
Route::post('cities/update/{id}', [CitiesContoller::class, 'update']);
Route::post('cities/updateCompleteInfo/{id}', [CitiesContoller::class, 'updateComplete']);
Route::post('cities/delete/{id}', [CitiesContoller::class, 'delete']);

Route::post('newsletter', [HomeController::class, 'newsletter']);
Route::get('newsletterAdmin', [HomeController::class, 'newsletterList']);
Route::post('newsletter/DownloadVerify', [HomeController::class, 'newsletterDownloadVerify']);
Route::get('newsletter/Download', [HomeController::class, 'newsletterDownload']);

Route::get('about', [AboutController::class, 'list']);
Route::get('tours', [ToursController::class, 'index']);
Route::get('calendar', [CalendarController::class, 'index']);

Route::get('generalDatta', [ContactsController::class, 'generalDatta']);

Route::get('contacts', [ContactsController::class, 'index']);
Route::post('adminContacts', [ContactsController::class, 'list']);
Route::post('contact/save', [ContactsController::class, 'contactSave']);
Route::post('contact/find', [ContactsController::class, 'contactFind']);
Route::post('contact/delete/{id}', [ContactsController::class, 'delete']);

Route::get('tastierLife', [TastierLifeController::class, 'index']);
//testier life -->falta

Route::post('banners/store', [BannersController::class, 'store']);
Route::post('banners/delete', [BannersController::class, 'delete']);
Route::post('banners/update', [BannersController::class, 'update']);

Route::post('addPDF', [FileController::class, 'store']);

Route::post('resize', [CitiesContoller::class, 'resise']);

Route::get('mainSiteContent/home', [MainSiteContentController::class, 'home']);
Route::post('mainSiteContent/linkStore', [MainSiteContentController::class, 'linkStore']);
Route::post('mainSiteContent/clustersave', [MainSiteContentController::class, 'clustersave']);

Route::post('about/timeline/list', [AboutController::class, 'list']);
Route::get('about/timeline/find/{id}', [AboutController::class, 'timelineFind']);
Route::post('about/timeline/save', [AboutController::class, 'timelineSave']);
Route::post('about/faq/list', [AboutController::class, 'listfaq']);
Route::get('about/faq/find/{id}', [AboutController::class, 'faqFind']);
Route::post('about/faq/save', [AboutController::class, 'faqSave']);
Route::post('about/delete', [AboutController::class, 'aboutDel']);


Route::get('initiatives', [InitiativesController::class, 'index']);
Route::post('initiatives', [InitiativesController::class, 'index']);
Route::get('initiatives/create', [InitiativesController::class, 'create']);
Route::post('initiatives/store', [InitiativesController::class, 'store']);
Route::get('initiatives/{id}', [InitiativesController::class, 'edit']);
Route::post('typeOfActivity/store', [InitiativesController::class, 'typeOfActivity_store']);
Route::post('topic/store', [InitiativesController::class, 'topic_store']);
Route::post('sdg/store', [InitiativesController::class, 'sdg_store']);
Route::post('connectionsToOther/store', [InitiativesController::class, 'connectionsToOther_store']);

/*

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});//*/
