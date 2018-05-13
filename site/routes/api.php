<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => '/v1.0/', 'middleware' => 'api'], function() {
    Route::get('/categories/projects-type', function(Request $request) {
        return App\Category::where('parent_id', 0)->get();
    });

    Route::get('/categories/parent-id/{id}', function(Request $request) {
        return App\Category::whereIn('parent_id', function($query) use ($request) {
            $query->select('id')
                ->from('categories')
                ->where('parent_id', 0)
                ->where('id', $request->id)
                ->get();
        })->get();
    });
});