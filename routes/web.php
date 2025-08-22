<?php

use Illuminate\Support\Facades\Route;

Route::get('/scalardocs', function () {
    return view('scalar');
});

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/register', function () {
//     return view('register');
// });

