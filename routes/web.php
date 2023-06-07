<?php

use App\Http\Middleware\Logged;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products;
use App\Http\Controllers\Details;
use App\Http\Controllers\Users;
use App\Http\Controllers\Index;


/*
|---------------------------------------------
| Render statics views 
|

*/

Route::view('/about', 'static.about') -> name("about");

Route::view('/contact', 'static.contact') -> name("contact");


/*
|---------------------------------------------
| Others 
|

*/

Route::get('/', Index::class ) -> name("root");



// Login / Signup

Route::post('/login',  [ Users::class, "show" ] );
Route::post('/signup', [ Users::class, "store" ] );


Route::view('/signup', 'login.signup') -> name("signup");

Route::view('/login', 'login.login') -> name("login");



// Disconnect

Route::get('/disconnect', function () {

    session_destroy();
    return redirect('/');

}) -> name("disconnect") -> middleware(Logged::class);



// Details 

Route::get('/details/{product_id}', Details::class );

// Articles && SearchBar
Route::view("/articles", "articles") -> name("articles");

// Cart Managment
Route::post(
    "/add", 
    [ Products::class, 'addProductToCart' ] 
) -> middleware(Logged::class) -> name('addCart');

Route::get(
    "/delete/{id}", 
    [ Products::class, 'deleteProductFromCart' ] 
) -> middleware(Logged::class) -> name('removeCart');

/*

Route::get("/example", function(){
    return "Example";
}) -> middleware(Logged::class);

*/
