<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        try{
            $this->validateLogin($request);

            $txt = date("YmdHis").$request->email;
            //Log::info("Todo bien ::");

            if (Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                  'token' => $request->user()->createToken($txt)->plainTextToken,
                  'message' => 'Welcome to the UNESCO Cities of Gastronomy'
                ]);
              }else{
                return response()->json([
                    'message' => 'User or password incorrect, please try again'
                  ], 401);
              };
        } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Error";
        };
          //*/

          return response()->json([
            'message' => 'Unauthorized',
            'status' => 401
          ], 401);
    }

    public function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required'
          ]);
    }


    public function notLogin(){
        return response()->json([
            'message' => 'Unauthorized',
            'status' => 401
          ], 401);
    }


    public function routeValidate(){
        return response()->json([
            'message' => 'You are already logged in',
            'status' => 200
          ], 200);
    }
}
