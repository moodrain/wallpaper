<?php

use Illuminate\Support\Facades\Route;

Route::any('admin/login', 'Admin\UserController@login');

Route::prefix('admin')->middleware(['auth', 'admin'])->namespace('Admin')->group(function() {

    Route::view('/', 'admin/index');

    Route::get('subject/list', 'SubjectController@list');
    Route::any('subject/edit', 'SubjectController@edit');
    Route::post('subject/destroy', 'SubjectController@destroy');

});
