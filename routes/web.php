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

	Route::controller(App\Http\Controllers\Admin\ManageUser::class)->group(function(){
		Route::get('/manage/users/list/{level}', 'index');
		Route::get('/manage/user/info/{user_id}', 'info_user');

		Route::match(['get', 'post'], '/manage/user/add/staff', 'add_staff');
		Route::match(['get', 'post'], '/manage/user/edit/staff/{user_id?}', 'edit_staff');
		Route::match(['get', 'post'], '/manage/user/add/stud', 'add_stud');
		Route::match(['get', 'post'], '/manage/user/edit/stud/{user_id?}', 'edit_stud');

		Route::match(['get', 'post'], '/manage/user/change/password/{user_id?}', 'change_password_user');
		Route::post('/manage/user/delete', 'delete_user');
	});
});
