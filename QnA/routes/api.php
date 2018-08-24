<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With, X-CLIENT-ID, X-CLIENT-SECRET');
header('Access-Control-Allow-Credentials: true');

Route::get('/users/{user_name}', 'UserController@exist');
Route::post('/users', 'UserController@create');
Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout');

Route::post('/questions', 'QuestionController@create');
Route::get('/questions/{question_id}', 'QuestionController@read');
Route::get('/questions-by-user/{user_id}', 'QuestionController@readByUserId');
Route::put('/questions/{question_id}', 'QuestionController@update');
Route::delete('/questions/{question_id}', 'QuestionController@delete');
