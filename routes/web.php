<?php

use Illuminate\Support\Facades\Route;

Route::controller(App\Http\Controllers\Authenticate::class)->group(function(){
	Route::match(['get', 'post'], '/login', 'login')->name('login');
	Route::get('/logout', 'logout')->name('logout');
});

Route::middleware(App\Http\Middleware\IsAuthenticate::class)->group(function(){
	Route::controller(App\Http\Controllers\Dashboard::class)->group(function(){
		Route::get('/', 'index');
	});

	Route::controller(App\Http\Controllers\Profile::class)->group(function(){
		Route::match(['get', 'post'], '/edit/profile', 'edit_profile');
		Route::match(['get', 'post'], '/change/password', 'change_password');
	});
});
