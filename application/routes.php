<?php
/**
 * En este archivo definimos todas las rutas de nuestra aplicación.
 * User: Bautista
 * Date: 17/6/2017
 * Time: 5:41 PM
 */

use FrameApi\Core\Route;

Route::setRoute('GET', '/', 'HomeController@index');


/**
 * Rutas de Posts.
 */
Route::setRoute('GET', '/posts', 'PostsController@index');
Route::setRoute('GET', '/posts/{id}', 'PostsController@get');
Route::setRoute('POST', '/posts/save', 'PostsController@save');
Route::setRoute('POST', '/posts/like', 'PostsController@like');
Route::setRoute('POST', '/posts/edit', 'PostsController@edit');
Route::setRoute('DELETE', '/posts/delete', 'PostsController@delete');

/**
 * Rutas de Categories.
 */
Route::setRoute('GET', '/categories', 'CategoriesController@index');
Route::setRoute('GET', '/categories/{id}', 'CategoriesController@get');
Route::setRoute('POST', '/categories/save', 'CategoriesController@save');
Route::setRoute('POST', '/categories/edit', 'CategoriesController@edit');

/**
 * Rutas de Users.
 */
Route::setRoute('GET', '/users', 'UsersController@index');
Route::setRoute('GET', '/users/{id}', 'UsersController@get');
Route::setRoute('POST', '/users/save', 'UsersController@save');
Route::setRoute('POST', '/users/edit', 'UsersController@edit');
Route::setRoute('DELETE', '/users/delete', 'UsersController@delete');
Route::setRoute('POST', '/users/send-recover-code', 'UsersController@sendRecoverCode');
Route::setRoute('POST', '/users/validate-recover-code', 'UsersController@validateRecoverCode');
Route::setRoute('POST', '/users/change-pass', 'UsersController@changePass');

/**
 * Rutas de Login
 */
Route::setRoute('POST', '/register', 'AuthController@register');
Route::setRoute('POST', '/login', 'AuthController@login');
Route::setRoute('POST', '/logout', 'AuthController@logout');

/**
 * Rutas de Comments.
 */
Route::setRoute('GET', '/comments', 'CommentsController@index');
Route::setRoute('GET', '/comments/{id}', 'CommentsController@getOfPost');
Route::setRoute('POST', '/comments/save', 'CommentsController@save');
Route::setRoute('DELETE', '/comments/delete', 'CommentsController@delete');