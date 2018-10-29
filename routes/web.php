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

        Route::get('/new', 'Dashboard\UserController@storeUserForm')->name('new');
        Route::post('/store', 'Dashboard\UserController@store')->name('store');

        Route::get('/editName/{user}', 'Dashboard\UserController@editName')->name('editName');
        Route::get('/editEmail/{user}', 'Dashboard\UserController@editEmail')->name('editEmail');
        Route::put('/update/{user}', 'Dashboard\UserController@update')->name('update');

        Route::get('/{user}/ingredients', 'Dashboard\UserController@showIngredientsByUser')->name('ingredients');
        Route::get('/{user}/recipes', 'Dashboard\UserController@showRecipesByUser')->name('recipes');
        Route::get('/{user}/refrigerator', 'Dashboard\UserController@showRefrigeratorsByUser')->name('refrigerators');
        Route::get('/{user}/delete', 'Dashboard\UserController@deleteUser')->name('delete');


    });

    Route::resource('recipes', 'Dashboard\RecipeController');
    Route::post('recipes/{recipe}/ingredient', 'Dashboard\RecipeController@addIngredient')->name('recipes.add.ingredient');
    Route::delete('recipes/{recipe}/{ingredient}', 'Dashboard\RecipeController@deleteIngredient')->name('recipes.delete.ingredient');


    Route::resource('/ingredients', 'Dashboard\IngredientController', ['except' => [
        'edit', 'show'
    ]]);



    Route::get('/rules', 'Dashboard\RuleController@index')->name('rules');

    Route::get('/analytics', 'Dashboard\AnalyticController@index')->name('analytics');

});


