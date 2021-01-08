<?php

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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Voyager::routes();
    Route::resource('update-stock', 'StockUpdateController');
});

Route::post('/get-asm-area', 'StockUpdateController@get_asm_area');
Route::post('/get-tso-area', 'StockUpdateController@get_tso_area');
Route::post('/get-db-area', 'StockUpdateController@get_db_area');
Route::post('/get-db-name', 'StockUpdateController@get_db_name');
Route::post('/get-product-category', 'StockUpdateController@get_product_category');
Route::post('/get-product-name', 'StockUpdateController@get_product_name');
