<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    // check user exist
    public function exist($user_name) {
        $count = User::where('username', $user_name)
                 ->count();
        return ['data' => $count];
    }

    public function create(Request $request) {
		
		// validation ...

        // insert username and password into DB
        $hashedPassword = password_hash($request->Password, PASSWORD_DEFAULT);
        $user = new User;
        $user->username = $request->user_name;
        $user->password = $hashedPassword;
        if($user->save()) {
            return ['msg'=>'Record has been added successfully',
            		'username'=>$user->username,
            		'id'=>$user->id];
        } else {
           return ['msg'=>'Record cannot be added!'];
        }
    }
}
