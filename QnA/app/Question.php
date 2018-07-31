<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;

class Question extends Model
{
    function userIns() {
    	return new User;
    }

	// add question 
    public function add() {
    	$title = Request::get('title');
        $description = Request::get('description'); 
               
        // check login
        if (!($this->userIns()->checkLogin())) {
        	return ['status'=>0, 'msg'=>'login is required'];
        }
       	
       	// check title empty
        if (!($title)) {
            return ['status'=>0, 'msg'=>'title is empty'];
        }

        // store question
        $this->title = $title;
        $this->description = $description;
        $this->user_id = session('userID');
        if ($this->save()) {
        	return ['status'=>1, 'msg'=>'question added', 'id'=>$this->id];
        } else {
        	return ['status'=>0, 'msg'=>'question insert failed'];
        }
    }

    // edit question
    public function edit() {
    	$questionID = Request::get('id');
    	$title = Request::get('title');
        $description = Request::get('description'); 

        // check login
        if (!($this->userIns()->checkLogin())) {
        	return ['status'=>0, 'msg'=>'login is required'];
        }

        // check question id empty
        if (!($questionID)) {
        	return ['status'=>0, 'msg'=>'question id is required'];
        }

        // check quesion id in DB
        $question = $this->find($questionID);
        if (!($question)) {
        	return ['status'=>0, 'msg'=>'question does not exist'];
        }

        // check user authority
        if ($question->user_id != session('userID')) {
        	return ['status'=>0, 'msg'=>'permission denied'];
        }

        // edit question
        if ($title) {
        	$question->title = $title;
        }
        if ($description) {
        	$question->description = $description;
        }
        if ($question->save()) {
        	return ['status'=>1, 'msg'=>'update successfully'];
        } else {
        	return ['status'=>0, 'msg'=>'question update failed'];
        }

    }

    // read question
    public function read() {
    	// read paricular question
    	$questionID = Request::get('id');
    	$userCreateQuesion = Request::get('quser');
    	if ($questionID) {
    		$question = $this->where('id', $questionID)
    						 ->get(['id','user_id','title','description','created_at','updated_at']);
    		return ['status'=>1, 'data'=>$question];
    	}
    	//read questions created by paricular user
    	elseif ($userCreateQuesion) {
    		$questions = $this->where('user_id', $userCreateQuesion)
    						 ->get(['id','user_id','title','description','created_at','updated_at']);
    		return ['status'=>1, 'data'=>$questions];
    	}

    	// read all questions
    	$limit = Request::get('limit')?:10; //how many records display in one page
    	$skip = ((Request::get('page')?:1)-1)*$limit; //which page want to be diplayed
    	$questions = $this->orderBy('id')->limit($limit)
    					  ->skip($skip)
    					  ->get(['id','user_id','title','description','created_at','updated_at']);
    	return ['status'=>1, 'data'=>$questions];
    }

    // delete quesion
    public function remove() {
    	// check login
    	if (!($this->userIns()->checkLogin())) {
        	return ['status'=>0, 'msg'=>'login is required'];
        }

        // check id in request
        $questionID = Request::get('id');        
        if (!$questionID) {
        	return ['status'=>0, 'msg'=>'question id is required'];
        }

        //check id in DB
        $question = $this->find($questionID);
        if (!$question) {
        	return ['status'=>0, 'msg'=>'question does not exist'];
        }

        // check user authority
        if ($question->user_id != session('userID')) {
        	return ['status'=>0, 'msg'=>'permission denied'];
        }

        //delete question
        if ($question->delete()) {
        	return ['status'=>1, 'msg'=>'delete successfully'];
        } else {
        	return ['status'=>0, 'msg'=>'question delete failed'];
        }
    }


}
