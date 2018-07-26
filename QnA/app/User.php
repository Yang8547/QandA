<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Request;
use Hash;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // register 
    public function signUp() {
        // dd(Request::all());
        
        // check username and password empty
        $userName = Request::get('username');
        $password = Request::get('password');        
        if(!($userName&&$password)) {
            return ['status'=>0, 'msg'=>'username or password cannot be empty!'];
        }

        // check username exist
        if($this->where('username',$userName)->exists()) {
            return ['status'=>0, 'msg'=>'username already exists!'];
        }

        // insert username and password into DB
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->username = $userName;
        $this->password = $hashedPassword;
        if($this->save()) {
            return ['status'=>1, 'msg'=>'Record has been added successfuly',
            'username'=>$userName, 'password'=>$hashedPassword];
        } else {
           return ['status'=>0, 'msg'=>'Record cannot be added!'];
        }

    }

    // login
    public function signIn() {
        // check username and password empty
        $userName = Request::get('username');
        $password = Request::get('password');        
        if(!($userName&&$password)) {
            return ['status'=>0, 'msg'=>'username or password cannot be empty!'];
        }

        // check username exist and then check password and set the session value
        $userInfo = $this->where('username', $userName)->first();
        if(!$userInfo) {
            return ['status'=>0, 'msg'=>'User name does not exist!'];
        } else {
            $hashedPassword = $userInfo->password;
            $passwordValid = Hash::check($password, $hashedPassword);
            if(!$passwordValid) {
                return ['status'=>0, 'msg'=>'Password is not valid!'];
            } else {
                session()->put('username', $userInfo->username);
                session()->put('userID', $userInfo->id);
                // dd(session()->all());
                return ['status'=>1, 'msg'=>'login successfuly'];
            }
        }

    }

}
