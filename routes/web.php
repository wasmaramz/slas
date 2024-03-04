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

	Route::controller(App\Http\Controllers\Student\ManageApplication::class)->group(function(){
		Route::get('/manage/application', 'list_appl');
		Route::match(["get", "post"], '/manage/add/application', 'add_appl');
		Route::get('/manage/application/info/{form_id}', 'info_appl');
	});

	Route::controller(App\Http\Controllers\KetuaProgram\VerifyApplication::class)->group(function(){
		Route::get('/verify/application', 'list_appl');
		Route::match(['get', 'post'], '/verify/application/edit/{form_id?}', 'edit_form');
	});

	Route::controller(App\Http\Controllers\Manual::class)->group(function(){
		Route::get('/manual/leave/info', 'leave_info');
	});
});
