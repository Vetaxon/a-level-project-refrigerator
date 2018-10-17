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


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login/{social}', 'Auth\LoginController@socialLogin')
    ->where('social', 'facebook|google|github');

Route::get('/login/{social}/callback', 'Auth\LoginController@handleProviderCallback')
    ->where('social', 'facebook|google|github');


Auth::routes();

Route::get('/home', 'Dashboard\HomeController@index')->name('home');

Route::group(['middleware' => 'auth:web', 'prefix' => 'dashboard', 'as' => 'dashboard.'], function () {

    Route::group(['prefix' => 'users', 'as' => 'user.'], function () {

        Route::get('/', 'Dashboard\UserController@index')->name('index');
        Route::get('/{user}/ingredients', 'Dashboard\UserController@showIngredientsByUser')->name('ingredients');
        Route::get('/{user}/recipes', 'Dashboard\UserController@showRecipesByUser')->name('recipes');
        Route::get('/{user}/refrigerator', 'Dashboard\UserController@showRefrigeratorsByUser')->name('refrigerators');
        Route::post('/', 'Dashboard\UserController@storeUser')->name('store');
        Route::put('/{user}', 'Dashboard\UserController@showRefrigeratorsByUser')->name('update');

    });

    Route::get('recipes', 'Dashboard\RecipeController@index')->name('recipes');
//    Route::get('recipes/{recipe}', 'Dashboard\RecipeController@show');

    Route::resource('/ingredients', 'Dashboard\IngredientController', ['except' => [
        'edit', 'show'
    ]]);

    Route::get('/ingredients', 'Dashboard\IngredientController@index')->name('ingredients');

    Route::get('/rules', 'Dashboard\RuleController@index')->name('rules');

    Route::get('/analytics', 'Dashboard\AnalyticController@index')->name('analytics');

});


