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

Route::post("signUp", "AuthController@signUp");
Route::post("signIn", "AuthController@signIn");
Route::post("updateArticle/{id}", "MainController@updateArticle");
Route::post("storeArticle", "MainController@storeArticle");
Route::post("destroyArticle/{id}", "MainController@destroyArticle");
Route::get("showArticle/{id}", "MainController@showArticle");
Route::get("getArticles", "MainController@index");
Route::get("getUser", "AuthController@getUser");
Route::get("getComments/{id}", "CommentsController@index");
Route::post("storeComment", "CommentsController@store");
