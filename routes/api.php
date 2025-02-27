<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/autocomplete-ajax-city', [\App\Http\Controllers\GroupController::class, 'dataAjaxCity'])->name('apiCity');
Route::get('/search', [\App\Http\Controllers\GroupController::class, 'searchCity'])->name('apiSearch');
Route::post('/getcities', [\App\Http\Controllers\NewCityController::class, 'getcities'])->name('getcities');
Route::get('/get', [\App\Http\Controllers\NewCityController::class, 'searchAskar'])->name('apiAskar');
Route::get('/city/{city}', [\App\Http\Controllers\GroupController::class, 'showCity'])->name('apiShow');


Route::get('/region', [\App\Http\Controllers\NewGroupController::class, 'index']);


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
