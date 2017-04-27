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
/* Post Section */
Route::post("signUp", "AuthController@signUp");
Route::post("signIn", "AuthController@signIn");
Route::post("updateArticle/{id}", "MainController@updateArticle");
Route::post("storeArticle", "MainController@storeArticle");
Route::post("destroyArticle/{id}", "MainController@destroyArticle");
Route::post("storeComment", "CommentsController@store");
Route::post("deleteComment/{id}", "CommentsController@deleteComment");
/* Get Section */
Route::get("showArticle/{id}", "MainController@showArticle");
Route::get("getArticles", "MainController@index");
Route::get("getUser", "AuthController@getUser");
Route::get("getComments/{id}", "CommentsController@index");
/* any section */
Route::any('{path?}', 'MainController@home')->where("path",".+");
