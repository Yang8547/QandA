<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;

class Question extends Model
{
    function userIns() {
    	return new User;
    }

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
}
