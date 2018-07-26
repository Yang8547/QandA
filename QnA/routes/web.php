<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});
Route::get('api/register', function () {
	$user = new App\User;
    return $user->signUp();
});
Route::get('api/login', function () {
	$user = new App\User;
    return $user->signIn();
});