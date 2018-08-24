<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Question;

class QuestionController extends Controller
{
    
	public function create(Request $request) {
		// validation
		$Validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'title' => 'required'
        ]);
        if ($Validator->fails()) {
        	return ['msg'=>'validation error', 'error'=>$validator->messages()];
        }
        
        $question = new Question;      
        // store question
        $question->title = $request->title;
        $question->description = $request->description;
        $question->user_id = $request->user_id;
        if ($question->save()) {
        	return ['status'=>1, 'msg'=>'question added', 'id'=>$question->id];
        } else {
        	return ['status'=>0, 'msg'=>'question insert failed'];
        }
	}  
}
