<?php
use App\Http\Controllers\CityController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::get('regions/{id}', [RegionController::class, 'show']);


Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::post('/autocomplete-ajax-city', [GroupController::class, 'dataAjaxCity']);
    Route::post('/autocomplete-ajax-group', [GroupController::class, 'dataAjaxGroup']);
    Route::post('/autocomplete-ajax-region', [GroupController::class, 'dataAjaxRegion']);
    Route::post('/autocomplete-ajax-district', [GroupController::class, 'dataAjaxDistrict']);
    Route::get('/region/{id}', [RegionController::class, 'show']);
    Route::get('/region/group/{id}', [RegionController::class, 'group']);
    Route::get('/district/group/{id}', [DistrictController::class, 'group']);
    Route::get('/district/{id}', [DistrictController::class, 'show']);
    Route::get('/home', [GroupController::class, 'show']);
        Route::get('/', [GroupController::class, 'show'])->name('main');
    Route::post('/regions/search', [GroupController::class, 'search']);
    Route::post('/city/update/{id}', [CityController::class, 'update']);
    Route::post('/district/update/{id}', [DistrictController::class, 'update']);
    Route::post('/city/store', [CityController::class, 'store']);
    Route::post('/district/store', [DistrictController::class, 'store']);
    Route::get('/city/region/{id}', [CityController::class, 'region']);
    Route::get('/city/delete/{id}', [CityController::class, 'destroy']);
    Route::get('/district/delete/{id}', [DistrictController::class, 'destroy']);
    Route::get('/groups/search', [GroupController::class, 'search']);

    Route::post('/select2-autocomplete-ajax', 'RegionController@dataAjaxRegion');
    Route::get('ajax-request', 'AjaxController@create');
    Route::post('ajax-request', 'AjaxController@store');
});
Route::get('api/city/{timestamp}', [CityController::class, 'bytimestamp'])->name('cityapi');
Route::get('api/district/{timestamp}', [DistrictController::class, 'bytimestamp'])->name('districtapi');
Route::get('documentation', [HomeController::class, 'documentation'])->name('documentation');
Route::get('test', [HomeController::class, 'test'])->name('test');
Route::get('api/region/{timestamp}', [RegionController::class, 'bytimestamp'])->name('regionapi');
Route::get('/count', [GroupController::class, 'count']);
