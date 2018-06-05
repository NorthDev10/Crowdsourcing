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

Route::get('/', 'SubtaskController@index')->name('home');

Route::get('/search-tasks', 'SubtaskController@findTasks')
        ->name('findTasks');

Route::group(['prefix' => '/projects'], function () {

    Route::get('/{type_of_project}/{category_id}', 'SubtaskController@showProjectsByType')
        ->where('category_id', '\d+')
        ->name('typeOfProject');

    Route::get('/{type_of_project}/{category}/{category_id}', 'SubtaskController@show')
        ->where('category_id', '\d+')
        ->name('category');
});

Route::get('/project/{type_of_project}/{type_category_id}/{project}', 'ProjectController@show')
    ->where('category_id', '\d+')
    ->name('project');

Route::group(['middleware' => 'auth'], function () {

    Route::resource('my-projects', 'Dashboard\ProjectController', ['except' => [
        'store',
    ]]);

    Route::resource('my-profile', 'Dashboard\UserProfileController', ['except' => [
        'show', 'create', 'store', 'update', 'destroy'
    ]]);

    Route::resource('my-tasks', 'Dashboard\MyTaskListController', ['only' => [
        'index', 'update'
    ]]);

    Route::post('leave-feedback', 'ReviewController@store')->name('leave_feedback');

    Route::get('/profile/{user_id}', 'Dashboard\UserProfileController@show')
        ->where('user_id', '\d+')
        ->name('user_profile');

    Route::post('/subscribe_task', 'SubtaskController@store')->name('subscribeTask');

    Route::put('/give_the_task', 'TaskExecutorController@update')->name('give_the_task');
    Route::put('/pick_up_task', 'TaskExecutorController@update')->name('pick_up_task');
});



Route::group(['prefix' => '/api/v1.0/', 'middleware' => 'auth'], function() {
    Route::get('/categories/projects-type', function() {
        return App\Category::where('parent_id', 0)->get();
    });

    Route::get('/categories/parent-id/{id}', function($id) {
        return App\Category::whereIn('parent_id', function($query) use ($id) {
            $query->select('id')
                ->from('categories')
                ->where('parent_id', 0)
                ->where('id', $id)
                ->get();
        })->get();
    });

    Route::get('/business-activities', function() {
        return App\BusinessActivity::all();
    });

    Route::get('/list-of-skills', function() {
        return App\Skill::all();
    });

    Route::post('/list-of-task-name', 'SubtaskController@listOfTaskName');

    Route::resource('api-my-projects', 'Dashboard\ProjectApiController', ['only' => [
        'store', 'edit', 'update'
    ]]);

    Route::resource('api-my-profile', 'Dashboard\UserProfileApiController', ['only' => [
        'edit', 'update'
    ]]);
});