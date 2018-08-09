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

    function questionIns() {
        return new Question;
    }

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
            return ['status'=>1, 'msg'=>'Record has been added successfully',
            'username'=>$userName, 'id'=>$this->id, 'password'=>$hashedPassword];
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
                return ['status'=>1, 'msg'=>'login successfully', 
                'name'=>session('username'), 'id'=>session('userID')];
            }
        }

    }

    // change password
    public function changePassword() {
        // check login
        if (!$this->checkLogin()) {
            return ['status'=>0, 'msg'=>'login required'];
        }

        // check password empty
        $oldPW = Request::get('old');
        $newPW = Request::get('new');
        if (!$oldPW || !$newPW) {
            return ['status'=>0, 'msg'=>'old password and new password are required'];      
        }

        // old password validation
        $user = $this->find(session('userID'));
        if (!$user) {
            return ['status'=>0, 'msg'=>'user does not exist'];
        }
        $passwordValid = Hash::check($oldPW, $user->password);
        if (!$passwordValid) {
            return ['status'=>0, 'msg'=>'old password invalid!'];
        }

        // change password
        $user->password = password_hash($newPW, PASSWORD_DEFAULT);
        if ($user->save()) {
            return ['status'=>1];
        } else {
            return ['status'=>0, 'msg'=>'password change fail'];
        }
    
    }


    // check login
    public function checkLogin() {
        return session('username') ?: false;
    }

    //logout
    public function logOut() {
        // clear session
        session()->forget('username');
        session()->forget('userID');
        return ['status'=>1, 'msg'=>'log out successfully'];
    }

    // many to many relationship
    public function answers() {
        return $this->belongsToMany('App\Answer')->withPivot('vote');
    }

    // get user information
    public function read() {
        // check id
        $userID = Request::get('id');
        if (!$userID) {
            return ['status'=>0, 'msg'=>'user id required'];
        }
        // get user info
        $user = $this->find($userID,['id','username','intro','avatar_url']);
        if (!$user) {
            return ['status'=>0, 'msg'=>'user does not exist'];
        }
        // get number of answer and question created by the user
        $answer_count = $user->answers()->count();
        $question_count = $this->questionIns()->where('user_id',$userID)->count();       
        $user->answer_count = $answer_count;
        $user->question_count = $question_count;
        return ['status'=>1, 'data'=>$user];

    }

    // update user info
    public function edit() {
        // check login
        if (!$this->checkLogin()) {
            return ['status'=>0, 'msg'=>'login required'];
        }

        // update info
        $user = $this->find(session('userID'));
        $user->email = Request::get('email');
        $user->phone = Request::get('phone');
        $user->intro = Request::get('intro');
        $user->avatar_url = Request::get('avatar_url');

        return $user->save() ? ['status'=>1] : ['status'=>0, 'msg'=>'update failed']

    }

    
}
