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



}
