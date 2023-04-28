<?php

use App\Http\Controllers\Login;
use App\Http\Controllers\Signup;
use Illuminate\Support\Facades\Route;

// Statics pages
Route::get('/', function () {
    return view('static.index');
});

Route::get('/about', function () {
    return view('static.about');
});

Route::get('/contact', function () {
    return view('static.contact');
});


// Login / Signup 
Route::get('/login', function () {
    return view('login.login');
});
Route::post('/login', [ Login::class, 'login' ] );


Route::get('/signup', function () {
    return view('login.signup');
});
Route::post('/signup', [ Signup::class, 'signup' ] );


// Disconnect
Route::get('/disconnect', function () {
    session_start();
    session_destroy();
});


