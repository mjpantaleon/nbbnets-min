<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Session;

class VerifierController extends Controller
{

    public function getVerifier(Request $request){

        $username       = $request->get('username');
        $password       = $request->get('password');
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        $current        = Session::get('userInfo')['user_id'];

        // IF CURRENT USER IS EQUAL TO USERNAME
        if($current === $username){
            return array(
                'status' => false,
                'message' => 'User is currently logged in',
            );
        }

        $verifier_username =  User::whereUserId($username)
                                ->wherePassword(md5($password))
                                ->whereFacilityCd($facility_cd)
                                ->whereDisableFlag('N')
                                ->first();

        if($verifier_username){

            return array(
                'status' => true,
                'data' => $verifier_username['user_id'],
            );

        }

        return array(
            'status' => false,
            'message' => 'Invalid Credentials',
        );

    }

}
