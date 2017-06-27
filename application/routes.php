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
Route::setRoute('PUT', '/posts/edit', 'PostsController@edit');

/**
 * Rutas de Comments.
 */
Route::setRoute('GET', '/comments', 'Comments@index');
Route::setRoute('GET', '/comments/{id}', 'Comments@get');
Route::setRoute('POST', '/comments/save', 'Comments@save');
Route::setRoute('DELETE', '/comments/delete', 'Comments@delete');