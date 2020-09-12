<?php

use Illuminate\Support\Facades\Route;

Route::any('login', 'UserController@login')->name('login');
Route::any('register', 'UserController@register');

Route::middleware(['auth'])->group(function() {

    Route::get('/', 'IndexController@index');
    Route::post('logout', 'UserController@logout');

    Route::get('tag', 'TagController@index');
    Route::post('tag', 'TagController@store');
    Route::post('tag/remove', 'TagController@remove');

    Route::get('image', 'ImageController@index');
    Route::post('image/upload', 'ImageController@upload');
    Route::post('image/tag', 'ImageController@tag');
    Route::post('image/remove', 'ImageController@remove');

    Route::get('home', 'HomeController@index');
    Route::post('home', 'HomeController@store');
    Route::get('home/{home}', 'HomeController@edit');
    Route::post('home/save', 'HomeController@save');
    Route::post('home/remove', 'HomeController@remove');
    Route::post('home/image/add', 'HomeController@addImage');

});





require __DIR__ . '/admin.php';