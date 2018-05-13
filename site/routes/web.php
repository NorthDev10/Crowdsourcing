<?php

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

Auth::routes();

Route::get('/', function () {
    return view('welcome', [
        'category'   => [],
        'categories' => App\Category::with('children')->where('parent_id', '0')->get(),
        'delimiter'  => ''
     ]);
})->name('home');

Route::group(['prefix' => '/projects'], function () {

    Route::get('/', 'SubtaskController@index')->name('subtasks');

    Route::get('/{type_of_project}/{category_id}', 'SubtaskController@showProjectsByType')
        ->where('category_id', '\d+')
        ->name('typeOfProject');

    Route::get('/{type_of_project}/{category}/{category_id}', 'SubtaskController@show')
        ->where('category_id', '\d+')
        ->name('category');
});

Route::get('/project/{type_of_project}/{category}/{category_id}/{project}', 'ProjectController@show')
    ->where('category_id', '\d+')
    ->name('project');

Route::group(['middleware' => 'auth'], function () {

    Route::resource('my-projects', 'Dashboard\ProjectController');
    Route::get('/profile/{id}', 'HomeController@index')->name('profile');
});
