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

Route::group(['middleware' => 'auth:web', 'prefix' => 'dashboard'], function () {


    Route::get('/users', 'Dashboard\UserController@index')->name('dashboard.users');


    Route::get('recipes', 'Dashboard\RecipeController@index')->name('dashboard.recipes');
    Route::get('recipes/{recipe}', 'Dashboard\RecipeController@show');


    Route::get('/ingredients', 'Dashboard\IngredientController@index')->name('dashboard.ingredients');

    Route::get('/rules', 'Dashboard\RuleController@index')->name('dashboard.rules');

    Route::get('/analytics', 'Dashboard\AnalyticController@index')->name('dashboard.analytics');

});


