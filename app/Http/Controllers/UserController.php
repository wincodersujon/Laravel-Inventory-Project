<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
    public function sendOTPCode(Request $request){

        $email=$request->input('email');
        $otp=rand(1000,9999);
        $count=User::where('email', '=', $email)->count();

        if($count==1){
            //OTP email address
            Mail::to($email)->send(new OTPMail($otp));
            //OTP code table update
            User::where('email', '=', $email)->update(['otp'=>$otp]);

            return response()->json([
                'status' => 'success',
                'message' =>'4 digit OTP code has been send to your email',
            ], 200);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ],401);
        }
    }
    function verifyOTP(Request $request){
        $email=$request->input('email');
        $otp=$request->input('otp');
        $count=User::where('email', '=', $email)
        ->where('otp','=',$otp)->count();

        if($count==1){
            //DB OTP update
            User::where('email', '=', $email)->update(['otp'=>'0']);

            //Reset password token issue
            $token=JWTToken::createTokenForSetPassword($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' =>'OTP verification successful',
                'token' => $token
            ], 200);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ],401);
        }
    }
    function resetPassword(Request $request){
        try{
            $email=$request->header('email');
            $password=$request->input('password');
            User::where('email','=',$email)->update(['password' => $password]);
            return response()->json([
                'status' => 'success',
                'message' =>'Reset Password Successful'
            ],200);
        }catch(Exception $exception){
            return response()->json([
                'status' => 'failed',
                'message' =>'Error Resetting Password'
            ],401);
        }
    }

}
