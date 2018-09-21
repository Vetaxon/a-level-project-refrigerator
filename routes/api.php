<?php


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
 * API Route group for user authentication
 */

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {

    Route::get('user', 'API\AuthController@user');
    Route::post('login', 'API\AuthController@login');
    Route::post('register', 'API\AuthController@register');
    Route::put('user', 'API\AuthController@update');
    Route::delete('user', 'API\AuthController@update');
    Route::put('user/password', 'API\AuthController@changePassword');
    Route::post('logout', 'API\AuthController@logout');
    Route::post('refresh', 'API\AuthController@refresh');


});

/*
 * API Route group for ALL! private user's and public ingredients
 */

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('ingredients', 'API\IngredientController@index');
    Route::get('ingredients/{ingredient}', 'API\IngredientController@show')->where('id', '[0-9]+');
    Route::post('ingredients', 'API\IngredientController@store');
    Route::put('ingredients/{ingredient}', 'API\IngredientController@update')->where('id', '[0-9]+');
    Route::delete('ingredients/{ingredient}', 'API\IngredientController@destroy')->where('id', '[0-9]+');

});


/*
 * API Route group for measures of ingredients
 */

Route::group(['middleware' => 'api'], function () {

    Route::get('measures', 'API\MeasureController@index');

});


/*
 * API Route group for ALL! private user's and public recipes
 */

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('recipes', 'API\RecipeController@index');
    Route::get('recipes/{recipes}', 'API\RecipeController@show')->where('id', '[0-9]+');
    Route::post('recipes', 'API\RecipeController@store');
    Route::put('recipes/{recipes}', 'API\RecipeController@update')->where('id', '[0-9]+');
    Route::delete('recipes/{recipes}', 'API\RecipeController@destroy')->where('id', '[0-9]+');

});


/*
 * API Route group for user's ingredients in refrigerator
 */

Route::group(['middleware' => 'auth:api', 'prefix' => 'refrigerator'], function () {

    Route::get('ingredients', 'API\RefrigeratorController@index');
    Route::get('ingredients/{ingredient}', 'API\RefrigeratorController@show')->where('id', '[0-9]+');
    Route::post('ingredients', 'API\RefrigeratorController@store');
    Route::put('ingredients/{ingredient}', 'API\RefrigeratorController@update')->where('id', '[0-9]+');
    Route::delete('ingredients/{ingredient}', 'API\RefrigeratorController@destroy')->where('id', '[0-9]+');

});

/*
 * API Route group for user's ingredients in refrigerator
 */

Route::group(['middleware' => 'auth:api', 'prefix' => 'refrigerator'], function () {

    Route::get('ingredients', 'API\RefrigeratorController@index');
    Route::get('ingredients/{ingredient}', 'API\RefrigeratorController@show')->where('id', '[0-9]+');
    Route::post('ingredients', 'API\RefrigeratorController@store');
    Route::put('ingredients/{ingredient}', 'API\RefrigeratorController@update')->where('id', '[0-9]+');
    Route::delete('ingredients/{ingredient}', 'API\RefrigeratorController@destroy')->where('id', '[0-9]+');


    // API Route for recommended recipes for user according to ingredients and its values  in refrigerator
    Route::get('recipes', 'API\RefrigeratorRecipeController@index');

});