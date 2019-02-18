<?php

namespace App\Http\Controllers;

use Request;
use App\Answer;
use App\Question;
class CommonController extends Controller
{

    public function timeline() {
    	$limit = Request::get('limit')?:10; //how many records display in one page
    	$skip = ((Request::get('page')?:1)-1)*$limit; //which page want to be diplayed
    	$questions = Question::orderBy('created_at', 'desc')
    					   ->limit($limit)
    					   ->skip($skip)
    					   ->get();
    	$answers = Answer::orderBy('created_at', 'desc')
    					 ->limit($limit)
    					 ->skip($skip)
                         ->with(['question', 'comments'])
    					 ->get();
    	$data = $questions->toBase()->merge($answers);
    	$data = $data->sortByDesc(function($item) {
            return $item->created_at;
        });
        return response()->json(['status'=>1, 'data'=>$data->values()], 200);
    	
    }
}
