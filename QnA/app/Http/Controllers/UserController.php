<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use Hash;
use Validator;

class UserController extends Controller
{
    // check user exist
    public function exist($user_name) {
        $count = User::where('username', $user_name)
                 ->count();
        return response()->json(['data' => $count], 200);
        // return ['data' => $count];
    }


    public function create(Request $request) {
		
		// validation 
		$validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'Password' => 'required',
        ]);

        if ($validator->fails()) {
            return ['msg'=>'validation error', 'error'=>$validator->messages()];
        }

        // insert username and password into DB
        $hashedPassword = password_hash($request->Password, PASSWORD_DEFAULT);
        $user = new User;
        $user->username = $request->user_name;
        $user->password = $hashedPassword;
        $emailData = ['name' => 'Toby'];
        if($user->save()) {
        	// Mail::to('anyang47@hotmail.com')->send(new RegisterMail($emailData));
        	return response()->json(['msg'=>'Record has been added successfully',
            		'username'=>$user->username,
            		'id'=>$user->id], 201);
            // return ['msg'=>'Record has been added successfully',
            // 		'username'=>$user->username,
            // 		'id'=>$user->id];
        } else {
           return ['msg'=>'Record cannot be added!'];
        }
    }

    public function login(Request $request) {
    	// validation
		$validator = Validator::make($request->all(), [
		            'user_name' => 'required',
		            'Password' => 'required',
		]);

        if ($validator->fails()) {
            return ['msg'=>'validation error', 'error'=>$validator->messages()];
        }

    	$userInfo = User::where('username', $request->user_name)->first();
        if(!$userInfo) {
            return ['status'=>0, 'msg'=>'User name does not exist!'];
        } else {
            $hashedPassword = $userInfo->password;
            $passwordValid = Hash::check($request->Password, $hashedPassword);
            if(!$passwordValid) {
                return ['status'=>0, 'msg'=>'Password is not valid!'];
            } else {
                session()->put('username', $userInfo->username);
                session()->put('userID', $userInfo->id);
                // dd(session()->all());
                return response()->json(['status'=>1, 
                	'msg'=>'login successfully', 
                	'name'=>session('username'), 
                	'id'=>session('userID')], 200);
                // return ['status'=>1, 'msg'=>'login successfully', 
                // 'name'=>session('username'), 'id'=>session('userID')];
            }
        }
    }

    public function logout(Request $request) {
    	session()->forget('username');
        session()->forget('userID');
        return ['status'=>1, 'msg'=>'log out successfully', 'id'=>$request->id];
    }
}
