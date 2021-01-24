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
    return redirect('/admin');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::resource('update-stock', 'StockUpdateController');
    Route::post('update-stock/declare', 'StockUpdateController@declare')->name('update-stock.declare');
    Route::post('update-stock/draft', 'StockUpdateController@draft')->name('update-stock.draft');
    Route::get('download/pdf/{stockId}', 'ReportController@download_pdf')->name('download-pdf');
    Route::get('download/excel/{stockId}', 'ReportController@download_excel')->name('download-excel');
    Route::get('draft/remove/{draftId}', 'StockUpdateController@remove_draft')->name('remove-draft');

    Route::get('reports', 'ReportController@index')->name('reports');
    Route::post('reports/individual', 'ReportController@individual_report')->name('individual-report');
    Route::post('reports/overall', 'ReportController@overall_report')->name('overall-report');

});

Route::post('/get-asm-area', 'StockUpdateController@get_asm_area');
Route::post('/get-tso-area', 'StockUpdateController@get_tso_area');
Route::post('/get-db-area', 'StockUpdateController@get_db_area');
Route::post('/get-db-name', 'StockUpdateController@get_db_name');
Route::post('/get-product-category', 'StockUpdateController@get_product_category');
Route::post('/get-product-name', 'StockUpdateController@get_product_name');
