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
        	return response()->json(['status'=>1, 'msg'=>'question added', 'id'=>$question->id], 201);
        } else {
        	return ['status'=>0, 'msg'=>'question insert failed'];
        }
	}  

	public function read($id) {

    	$question = Question::where('id', $id)
    						->get(['id','user_id','title','description','created_at','updated_at']);
    		return response()->json(['status'=>1, 'data'=>$question], 200) ;   	
	}

	public function readByUserId($user_id) {
		$questions = Question::where('user_id',$user_id)
							->get(['id','user_id','title','description','created_at','updated_at']);
		return response()->json(['status'=>1, 'data'=>$questions], 200);
	}

	public function update(Request $request, $id) {

		var validator = Validator::make($request->all(), [
			'user_id' => 'required',
            'id' => 'required',
		])
		
        // check quesion id in DB
        $question = Question::find($id);
        if (!($question)) {
        	return response()->json(['status'=>0, 'msg'=>'question does not exist'], 400) ;
        }

        // check user authority
        if ($question->user_id != $request->user_id) {
        	return response()->json(['status'=>0, 'msg'=>'permission denied'],401);
        }

        // edit question       
        $question->title = $request->title;              
        $question->description = $request->description;
        if ($question->save()) {
        	return return response()->json(['status'=>1, 'msg'=>'update successfully', 
        		'data'=>$question], 201);
        } else {
        	return ['status'=>0, 'msg'=>'question update failed'];
        }
	}

	public function delete($id) {

        //check id in DB
        $question = Question::find($id);
        if (!$question) {
        	return response()->json(['status'=>0, 'msg'=>'question does not exist'], 400);
        }

        // check user authority
        if ($question->user_id != session('userID')) {
        	response()->json(['status'=>0, 'msg'=>'permission denied'], 401);
        }

        //delete question
        if ($question->delete()) {
        	return response()->json(['status'=>1, 'msg'=>'delete successfully'], 204);
        } else {
        	return ['status'=>0, 'msg'=>'question delete failed'];
        }
	}

	
}
