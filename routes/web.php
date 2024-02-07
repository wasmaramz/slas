<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
	return view('authenticate.login');
	return view('authenticate.reset-password');
	return view('authenticate.forgot-password');
	return view('dashboard.index');
	abort(403);
	abort(403, "TEST");
	abort(500);
	abort(404);
    //return view('welcome');
	//return view('index');
	//return view('dashboard.ecommerce-dashboard');
	//return view('dashboard.general-dashboard');
});
