<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class LoginController extends Controller
{
    public function login(Request $request){

        $user = User::with('facility','level')
                    ->whereUserId($request->get('userId'))
                    ->whereDisableFlag('N')
                    ->first();

        $status = true;
        $error = null;

        if(!$user){
            $status = false;
            $error = "Login failed! Please check username/password.";
        }else{

            //check if password is the same
            if( $user['password'] === md5($request->get('password')) ){
                Auth::attempt(array(
                    'user_id' => $request->get('userId'),
                    'password' => $user['password']
                ));
            } else{
                $status = false;
                $error = "Login failed! Wrong password.";                
            }

            // $user->username = $request->get('username');s
            // File::append("userlogin_logs.txt",$request->get('username') ." - ".date("Y-m-d h:i:s A")."\n");
        }

        // \Log::info(Auth::user()->user_id);

        return [
            'status' => $status,
            'error' => $error,
            'user' => $user,
            // 'token' => $status ? $this->token() : null
        ];
    }

    public function getUser(){

        if(Auth::check()){
            return array(
                'status' => true,
                'name' => Auth::user()->user_fname . " " . Auth::user()->user_mname . " " . Auth::user()->user_lname,
            );
        } else{
            return array(
                'status' => false,
                'error' => "Error retrieving user",
            );
        }

    }

    public function logout(){

        $status = Auth::logout();

        // \Log::info($status);

        // if($status){
        return [
            'status' => true,
        ];
        // } else{
        //     return [
        //         'status' => false,
        //         'error' => "Error in logout",
        //     ];
        // }

    }

}
