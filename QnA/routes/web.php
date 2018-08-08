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
    // $user->signIn();
    // return redirect('/');
});
Route::get('api/logout', function () {
	$user = new App\User;
	return $user->logOut();  
});
Route::get('api/question/add', function () {
	$question = new App\Question;
	return $question->add();  
});
Route::get('api/question/edit', function () {
	$question = new App\Question;
	return $question->edit();  
});
Route::get('api/question/read', function () {
	$question = new App\Question;
	return $question->read();  
});
Route::get('api/question/remove', function () {
	$question = new App\Question;
	return $question->remove();  
});
Route::get('api/answer/add', function () {
	$answer = new App\Answer;
	return $answer->add();  
});
Route::get('api/answer/edit', function () {
	$answer = new App\Answer;
	return $answer->edit();  
});
Route::get('api/answer/read', function () {
	$answer = new App\Answer;
	return $answer->read();  
});
Route::get('api/answer/vote', function () {
	$answer = new App\Answer;
	return $answer->vote();  
});
Route::get('api/comment/add', function () {
	$comment = new App\Comment;
	return $comment->add();  
});
Route::get('api/comment/read', function () {
	$comment = new App\Comment;
	return $comment->read();  
});
Route::get('api/comment/remove', function () {
	$comment = new App\Comment;
	return $comment->remove();  
});
Route::get('api/timeline', 'CommonController@timeline');
// Route::get('api/checklogin', function () {
// 	$user = new App\User;
// 	dd($user->checkLogin()); 
// });