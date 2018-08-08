<?php

namespace App\Http\Controllers;

use Request;
use App\Answer;
use App\Question;
class CommonController extends Controller
{
    function questionIns() {
    	return new Question;
    }

    function answerIns() {
    	return new Answer;
    }

    public function timeline() {
    	$limit = Request::get('limit')?:10; //how many records display in one page
    	$skip = ((Request::get('page')?:1)-1)*$limit; //which page want to be diplayed
    	$questions = $this->questionIns()
    					   ->orderBy('created_at', 'desc')
    					   ->limit($limit)
    					   ->skip($skip)
    					   ->get();
    	$answers = $this->answerIns()
    					 ->orderBy('created_at', 'desc')
    					 ->limit($limit)
    					 ->skip($skip)
    					 ->get();
    	$data = $questions->toBase()->merge($answers);
    	$data = $data->sortByDesc(function($item) {
    					  	return $item->created_at;
    			});
    	return $data;
    	
    }
}
