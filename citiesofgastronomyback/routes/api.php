<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
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
use App\Http\Controllers\Controller;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

use App\Http\Middleware\TokenConfirmation;
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
Route::get('citiesAdmin', [CitiesContoller::class, 'list'])->middleware('auth:sanctum');
Route::post('citiesAdmin', [CitiesContoller::class, 'list'])->middleware('auth:sanctum');
Route::post('citiesStore', [CitiesContoller::class, 'citiesStore'])->middleware('auth:sanctum');
Route::post('citiesUpdate', [CitiesContoller::class, 'citiesUpdate'])->middleware('auth:sanctum');
Route::get('cities/find/{id}', [CitiesContoller::class, 'find']);
Route::get('cities/edit/{id}', [CitiesContoller::class, 'find'])->middleware('auth:sanctum');
Route::get('citiesAdmin/find/{id}', [CitiesContoller::class, 'find'])->middleware('auth:sanctum');
Route::post('cities/store', [CitiesContoller::class, 'store'])->middleware('auth:sanctum');
Route::post('cities/update/{id}', [CitiesContoller::class, 'update'])->middleware('auth:sanctum');
Route::post('cities/updateCompleteInfo/{id}', [CitiesContoller::class, 'updateComplete'])->middleware('auth:sanctum');
Route::post('cities/delete/{id}', [CitiesContoller::class, 'delete'])->middleware('auth:sanctum');

Route::post('newsletter', [HomeController::class, 'newsletter']);
Route::get('newsletterAdmin', [HomeController::class, 'newsletterList'])->middleware('auth:sanctum');
Route::post('newsletter/DownloadVerify', [HomeController::class, 'newsletterDownloadVerify'])->middleware('auth:sanctum');
Route::get('newsletter/Download', [HomeController::class, 'newsletterDownload'])->middleware('auth:sanctum');

Route::get('about', [AboutController::class, 'list']);
Route::get('calendar', [CalendarController::class, 'index']);
//Route::get('calendar', [InitiativesController::class, 'calendar']);

Route::get('generalDatta', [ContactsController::class, 'generalDatta'])->middleware('auth:sanctum');
Route::get('contacts', [ContactsController::class, 'index']);
Route::post('adminContacts', [ContactsController::class, 'list'])->middleware('auth:sanctum');
Route::post('contact/save', [ContactsController::class, 'contactSave'])->middleware('auth:sanctum');
Route::post('contact/findAdmin', [ContactsController::class, 'contactFind'])->middleware('auth:sanctum');
Route::post('contact/find', [ContactsController::class, 'contactFind']);
Route::post('contact/delete/{id}', [ContactsController::class, 'delete'])->middleware('auth:sanctum');
//testier life -->falta

Route::post('banners/store', [BannersController::class, 'store'])->middleware('auth:sanctum');
Route::post('banners/delete', [BannersController::class, 'delete'])->middleware('auth:sanctum');
Route::post('banners/update', [BannersController::class, 'update'])->middleware('auth:sanctum');

Route::post('addPDF', [FileController::class, 'store']);

Route::post('resize', [CitiesContoller::class, 'resise']);

Route::get('mainSiteContent/home', [MainSiteContentController::class, 'home'])->middleware('auth:sanctum');
Route::post('mainSiteContent/linkStore', [MainSiteContentController::class, 'linkStore'])->middleware('auth:sanctum');
Route::post('mainSiteContent/clustersave', [MainSiteContentController::class, 'clustersave'])->middleware('auth:sanctum');

Route::post('about/timeline/list', [AboutController::class, 'list'])->middleware('auth:sanctum');
Route::get('about/timeline/find/{id}', [AboutController::class, 'timelineFind'])->middleware('auth:sanctum');
Route::post('about/timeline/save', [AboutController::class, 'timelineSave'])->middleware('auth:sanctum');
Route::post('about/faq/list', [AboutController::class, 'listfaq'])->middleware('auth:sanctum');
Route::get('about/faq/find/{id}', [AboutController::class, 'faqFind'])->middleware('auth:sanctum');
Route::post('about/faq/save', [AboutController::class, 'faqSave'])->middleware('auth:sanctum');
Route::post('about/delete', [AboutController::class, 'aboutDel'])->middleware('auth:sanctum');


Route::get('initiatives', [InitiativesController::class, 'index']);
Route::post('initiatives', [InitiativesController::class, 'index']);
Route::get('initiativesAdmin', [InitiativesController::class, 'indexAdmin'])->middleware('auth:sanctum');
Route::post('initiativesAdmin', [InitiativesController::class, 'indexAdmin'])->middleware('auth:sanctum');
Route::get('initiatives/create', [InitiativesController::class, 'create'])->middleware('auth:sanctum');
Route::post('initiatives/store', [InitiativesController::class, 'store'])->middleware('auth:sanctum');
Route::get('initiatives/edit/{id}', [InitiativesController::class, 'edit'])->middleware('auth:sanctum');
Route::get('initiatives/find/{id}', [InitiativesController::class, 'edit']);
Route::post('initiatives/delete/{id}', [InitiativesController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('typeOfActivity/store', [InitiativesController::class, 'typeOfActivity_store'])->middleware('auth:sanctum');
Route::post('typeOfActivity/delete/{id}', [InitiativesController::class, 'typeOfActivity_delete'])->middleware('auth:sanctum');
Route::post('topic/store', [InitiativesController::class, 'topic_store']);
Route::post('topic/delete/{id}', [InitiativesController::class, 'topic_delete'])->middleware('auth:sanctum');
Route::post('sdg/store', [InitiativesController::class, 'sdg_store']);
Route::post('sdg/delete/{id}', [InitiativesController::class, 'sdg_delete'])->middleware('auth:sanctum');
Route::post('connectionsToOther/store', [InitiativesController::class, 'connectionsToOther_store']);
Route::post('connectionsToOther/delete/{id}', [InitiativesController::class, 'connectionsToOther_delete'])->middleware('auth:sanctum');



Route::get('tastierLife', [TastierLifeController::class, 'index']);
Route::post('tastierLife', [TastierLifeController::class, 'index']);
Route::get('tastierLifeAdmin', [TastierLifeController::class, 'index'])->middleware('auth:sanctum');
Route::post('tastierLifeAdmin', [TastierLifeController::class, 'index'])->middleware('auth:sanctum');
Route::get('recipe/create', [TastierLifeController::class, 'create'])->middleware('auth:sanctum');
Route::post('recipe/store', [TastierLifeController::class, 'storeRecipe'])->middleware('auth:sanctum');
Route::get('recipe/findRecipe/{id}', [TastierLifeController::class, 'findRecipe']);
Route::get('recipe/findRecipeAdmin/{id}', [TastierLifeController::class, 'findRecipe'])->middleware('auth:sanctum');
Route::get('recipe/show/{id}', [TastierLifeController::class, 'showRecipe']);
Route::post('recipe/delete/{id}', [TastierLifeController::class, 'delete'])->middleware('auth:sanctum');
Route::get('recipe/vote/{id}', [TastierLifeController::class, 'vote']);
Route::get('chef/findChef/{id}', [ChefController::class, 'findChef']);
Route::get('chef/create', [ChefController::class, 'create']);
Route::post('chef/store', [ChefController::class, 'store'])->middleware('auth:sanctum');
Route::post('chef/delete/{id}', [ChefController::class, 'delete'])->middleware('auth:sanctum');
Route::post('categories/store', [CategoriesController::class, 'store'])->middleware('auth:sanctum');
Route::post('categories/delete/{id}', [CategoriesController::class, 'delete'])->middleware('auth:sanctum');
Route::get('categories/find/{id}', [CategoriesController::class, 'findCategory']);


Route::get('tours', [ToursController::class, 'index']);
Route::post('tours', [ToursController::class, 'index']);
Route::get('toursAdmin', [ToursController::class, 'list'])->middleware('auth:sanctum');
Route::post('toursAdmin', [ToursController::class, 'list'])->middleware('auth:sanctum');
Route::get('tours/create', [ToursController::class, 'create'])->middleware('auth:sanctum');
Route::post('tours/store', [ToursController::class, 'store']);
Route::get('tours/find/{id}', [ToursController::class, 'find'])->middleware('auth:sanctum');
Route::post('tours/delete/{id}', [ToursController::class, 'delete'])->middleware('auth:sanctum');
Route::get('tours/show/{id}', [ToursController::class, 'show']);

Route::post('generalSearch', [Controller::class, 'generalSearch']);


Route::post('user', [UserController::class, 'list'])->middleware('auth:sanctum');
Route::post('user/delete/{id}', [UserController::class, 'delete'])->middleware('auth:sanctum');
Route::post('user/store', [UserController::class, 'store'])->middleware('auth:sanctum');
Route::get('user/find/{id}', [UserController::class, 'find'])->middleware('auth:sanctum');
Route::post('user/forgotPassword', [UserController::class, 'forgotPassword']);
Route::post('user/resetPassword', [UserController::class, 'resetPassword']);
Route::post('user/perfilPassword', [UserController::class, 'resetPerfilPassword'])->middleware('auth:sanctum');

Route::post('routeValidate', [LoginController::class, 'routeValidate'])->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
