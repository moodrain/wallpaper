<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function index()
    {
        if (request()->filled('token')) {
            return file_get_contents(public_path('home.html'));
        } else {
            return redirect('/image');
        }
    }
}