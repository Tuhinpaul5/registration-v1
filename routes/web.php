<?php

use Illuminate\Support\Facades\Route;

Route::get('/scalardocs', function () {
    return view('scalar');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signup', function () {
    return view('register');
});

