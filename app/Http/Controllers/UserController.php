<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
   function userRegistration(Request $request)
    {
        try {
            $request->validate([
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required|email|unique:users',
                'mobile' => 'required',
                'password' => 'required',
            ]);

            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);

            return response()->json([
                'satus' => 'success',
                'message' => 'User Registration Successfully',
                ],200);
        } catch (Exception $e) {
            return response()->json([
                'satus' => 'failed',
                'message' => 'User Registration Failed',
                //'message' => $e->getMessage(), if I want to specifically error this response
                ],200);
        }
    }
    public function userLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $count = User::where('email', '=', $request->input('email'))
        ->where('password', '=', $request->input('password'))
        ->count();
        if ($count==1){
            //JWT Token issue for login
            $token = JWTToken::CreateToken($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' =>'User Login Successfully',
                'token' =>$token
            ], 200);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ],401);
        }
    }

}
