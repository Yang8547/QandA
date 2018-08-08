<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;

class Comment extends Model
{
    
    function userIns() {
    	return new User;
    }
	function questionIns() {
    	return new Question;
    }
    function answerIns() {
    	return new Answer;
    }

    // add comment
    public function add() {
    	// check login
    	if (!($this->userIns()->checkLogin())) {
    		return ['status'=>0, 'msg'=>'login is required'];
    	}

    	// check content empty
    	$content = Request::get('content');
    	if(!$content) {
    		return ['status'=>0, 'msg'=>'content is required'];
    	}

    	// check reply to
    	$replyTo = Request::get('reply');
    	if ($replyTo) {
    		if ($this->find($replyTo)) {
    			$this->reply_to = $replyTo;
    		} else {
    			return ['status'=>0, 'msg'=>'reply to does not exist'];
    		}
    	}

    	// check quesion id and answer id 
    	$quesionID = Request::get('qid');
    	$answerID = Request::get('aid');
    	if(!($quesionID||$answerID)) {
    		return ['status'=>0, 'msg'=>'question or answer id is required'];
    	}
    	if ($quesionID) {
    		$question = $this->questionIns()->find($quesionID);
    		if (!$question) {
    			return ['status'=>0, 'msg'=>'question does not exist'];
    		}
    		$this->question_id = $quesionID;
    	} elseif ($answerID) {
    		$answer = $this->answerIns()->find($answerID);
    		if (!$answer) {
    			return ['status'=>0, 'msg'=>'answer does not exist'];
    		}
    		$this->answer_id = $answerID;
    	}

    	$this->content = $content;
    	$this->user_id = session('userID');
    	if ($this->save()) {
    		return ['status'=>1, 'msg'=>'comment added', 'id'=>$this->id];
    	} else {
    		return ['status'=>0, 'msg'=>'comment added failed'];
    	}
    }

    // read comment
    public function read() {
    	// check id
    	$questionID = Request::get('qid');
    	$answerID = Request::get('aid');
    	if(!$questionID & !$answerID) {
    		return ['status'=>0, 'msg'=>'question or answer id is required'];
    	}

    	// read all comments of particular question or answer
    	if ($questionID) {
    		$comments = $this->where('question_id', $questionID)->get();    		
    	} elseif ($answerID) {
    		$comments = $this->where('answer_id', $answerID)->get();
    	}
    	return ['status'=>1, 'data'=>$comments]; 
	}

	// delete comment
	public function remove() {
		// check login
		if (!($this->userIns()->checkLogin())) {
			return ['status'=>0, 'msg'=>'login is required'];
		}

		// check comment id
		$commentID = Request::get('cid');
		if (!$commentID) {
			return ['status'=>0, 'msg'=>'comment id is required'];
		}

		// check comment id in DB
		$comment = $this->find($commentID);
		if (!$comment) {
			return ['status'=>0, 'msg'=>'comment does not exist'];
		}

		// check authority
		if ($comment->user_id != session('userID')) {
			return ['status'=>0, 'msg'=>'permission denied'];
		}

		// delete comment
		$this->where('reply_to', $commentID)->delete();
		if ($comment->delete()) {
        	return ['status'=>1, 'msg'=>'delete successfully'];
        } else {
        	return ['status'=>0, 'msg'=>'comment delete failed'];
        }
	} 

}
