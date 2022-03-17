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

/*
 * Sites
 *
 */

 Route::get('/sites/all',       'Api\SiteController@index');
 Route::post('/sites',         'Api\SiteController@store');
 Route::get('/sites',          'Api\SiteController@show');
 Route::delete('/sites/delete', 'Api\SiteController@destroy');

// Route::group(['prefix' => 'sites'], function() {
//     Route::get('/all',       'Api\SiteController@index');
//     Route::post('/',         'Api\SiteController@store');
//     Route::get('/',          'Api\SiteController@show');
//     Route::delete('/delete', 'Api\SiteController@destroy');
// });

/*
 * Site Crawl
 *
 */
Route::post('/site/crawl', 'Api\SiteCrawlController@store');

/*
 * Site Pages
 *
 */
Route::get('/site/pages', 'Api\SitePagesController@index');

/*
 * Handle Non-existent Routes
 *
 * Respond with a message and 404 for non-existent routes
 *
 */
// Route::fallback(function () {
//     return response()->json([
//         'message' => 'Silence is golden',
//     ], 404);
// });
