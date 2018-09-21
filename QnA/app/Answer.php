<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;

class Answer extends Model
{
    
    function userIns() {
    	return new User;
    }

	function questionIns() {
    	return new Question;
    }

    // add answer
    public function add() {
    	// check login
    	if (!($this->userIns()->checkLogin())) {
    		return ['status'=>0, 'msg'=>'login is required'];
    	}

    	// check question id and content
    	$questionID = Request::get('questionid');
    	$content = Request::get('content');
    	if (!($questionID && $content)) {
    		return ['status'=>0, 'msg'=>'question id and content are required'];
    	}

    	// check question in DB
    	$question = $this->questionIns()->find($questionID);
        if (!($question)) {
        	return ['status'=>0, 'msg'=>'question does not exist'];
        }

        // check same user answer same question(user can only modify the answer but not add a new answer to the same quetion)
        $answered = $this->where(['question_id'=>$questionID, 'user_id'=>session('userID')])
        			->exists();
        if($answered) {
        	return ['status'=>0, 'msg'=>'answer duplicate'];
        }

        //add
        $this->content = $content;
        $this->user_id = session('userID');
        $this->question_id = $questionID;
        if ($this->save()) {
        	return ['status'=>1, 'msg'=>'answer add successfully', 'id'=>$this->id];
        } else {
        	return ['status'=>0, 'msg'=>'answer add failed'];
        }
    }

    //edit question
    public function edit() {    	
    	// check login
    	if (!($this->userIns()->checkLogin())) {
    		return ['status'=>0, 'msg'=>'login is required'];
    	}

    	// check id and content
    	$answerID = Request::get('id');
    	$content = Request::get('content');
    	if (!($answerID && $content)) {
    		return ['status'=>0, 'msg'=>'answer id and content are required'];
    	}

    	// check id in DB
    	$answer = $this->find($answerID);
        if (!($answer)) {
        	return ['status'=>0, 'msg'=>'answer does not exist'];
        }

        // check authority
        if ($answer->user_id != session('userID')) {
        	return ['status'=>0, 'msg'=>'permission denied'];
        }

        // edit
        $answer->content = $content;
        if ($answer->save()) {
        	return ['status'=>1, 'msg'=>'answer update successfully'];
        } else {
        	return ['status'=>0, 'msg'=>'answer update failed'];
        }
    }

    // read answers
    public function read() {
    	// check id in request
    	$answerID = Request::get('aid');
    	$questionID = Request::get('qid');
    	if (!($answerID || $questionID)) {
    		return ['status'=>0, 'msg'=>'answer id or question id are required'];
    	}

    	// case 1 read particular answer
    	if ($answerID) {
    		// check id in DB
    		$answer = $this->find($answerID);
    		if (!$answer) {
    			return ['status'=>0, 'msg'=>'answer does not exist'];
    		}
    		// return answer
    		return ['status'=>1, 'data'=>$answer];
    	}

    	// case 2 read all answers for particular question
    	elseif ($questionID) {
    		// check quesion id in DB
    		if (!($this->questionIns()->find($questionID))) {
    			return ['status'=>0, 'msg'=>'question does not exist'];
    		}
    		// return answers
    		$answers = $this->where('question_id', $questionID)->get()->keyBy('id');
    		return ['status'=>1, 'data'=>$answers];
    	}

    }

    // many to many relationship
    public function users() {
    	return $this->belongsToMany('App\User')->withPivot('vote')->withTimestamps();
    }
    // one to many relationship
    public function question() {
        return $this->belongsTo('App\Question');
    }

    public function comments() {
        return $this->hasMany('App\Comment');
    }

    // like dislike method
    public function vote() {
    	// check login
    	if (!($this->userIns()->checkLogin())) {
    		return ['status'=>0, 'msg'=>'login is required'];
    	}

    	// check answer id and vote
    	$answerID = Request::get('aid');
    	$vote = Request::get('vote');
    	if (!$answerID || $vote==null) {
    		return ['status'=>0, 'msg'=>'answer id and vote are required'];
    	}

    	// check answer id in DB
    	$answer = $this->find($answerID);
    	if (!$answer) {
    		return ['status'=>0, 'msg'=>'answer does not exist'];
    	}

    	// delete the existing vote under the same answer and same user if exists
    	$answer->users()
    		   ->newPivotStatement()
    		   ->where(['answer_id'=>$answerID, 'user_id'=>session('userID')])
    		   ->delete();

    	// insert vote in pivot table
    	$voteNumber = $vote >= 1? 1:0;
    	$answer->users()->attach(session('userID'), ['vote'=>$voteNumber]);
    	return ['status'=>1, 'msg'=>'voted'];

    }




}

