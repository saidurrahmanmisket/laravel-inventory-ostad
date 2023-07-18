<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\otpMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class users extends Controller
{
    public function userRegistration(Request $request)
    {

        try {
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);
            return response()->json([

                'status' => 'success',
                'message' => 'user registration successfully'
            ], status: 200);
        } catch (Exception $e) {
            return response()->json([

                'status' => 'fail',
                'message' => $e->getMessage()
            ], status: 400);
        }
    }
    function userLogin(Request $request)
    {
        $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->count();

        if ($count === 1) {
            // jwt token 
            $token = JWTToken::createToken($request->input('email'));
            return response()->json([

                'status' => 'success',
                'message' => 'user login successfully',
                'token' => $token
            ], status: 200);
        } else {

            return response()->json([

                'status' => 'fail',
                'message' => 'Unauthorize'
            ], status: 400);
        }
    }
    function sendOtp(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', '=', $email)->count();
        if ($count === 1) {

            Mail::to($email)->send(new otpMail($otp));
            User::where('email', '=', $email)->update([
                'otp' => $otp
            ]);

            return response()->json([

                'status' => 'success',
                'message' => 'otp sent successfully'
            ], status: 200);
        } else {
            return response()->json([

                'status' => 'fail',
                'message' => 'failed'
            ], status: 400);
        }
    }
    function verifyOtp(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)
            ->where('otp', '=', $otp)
            ->count();

        if (1 == $count) {
            // database otp update 
            User::where('email', '=', $email)
                ->update(['otp' => '0']);
            // make token for pass 
            $token = JWTToken::createTokenForPass($email);
            return response()->json([

                'status' => 'success',
                'message' => 'otp verify successfully',
                'token' => $token
            ], status: 200);
        } else {
            return response()->json([

                'status' => 'fail',
                'message' => 'failed'
            ], status: 400);
        }
    }
    function restPass(Request $request)
    {
        try {
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', '=', $email)
                ->update(['password' => $password]);
            return response()->json([

                'status' => 'success',
                'message' => 'Password change successfully'
            ], status: 200);
        } catch (Exception $e) {
            return response()->json([

                'status' => 'fail',
                'message' => 'failed',
                'error'=>$e->getMessage()
            ], status: 400);
        }
    }
}
