<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\mailUserCreate;
use App\Mail\mailResetUserPassword;
use App\Models\User;
use App\Models\UserResetPassword;
use App\Models\PasswordResetTokens;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginController;

class UserController extends Controller
{


    public function list(Request $request){


        $message = '';$status='200';
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;

        $obj =(New User())->list($request->search, $page, $cantItems);
        $totalUsers =(New User())->list($request->search, 1, 99999999999999);

        $total = count($totalUsers);
        if($total > $cantItems){
            $division = $total / $cantItems;
            $paginator = intval($division);
            if($paginator < $division){
                $paginator = $paginator + 1;
            };
        };
        if($total == 0){$message = "No results found";};


        return response()->json([
                'Users' => $obj,
                'tot' => $total,
                'paginator' => $paginator,
                'message' => $message,
                'status' => $status
            ]);
    }

    public function store(Request $request){
        $status = 200; $obj = []; $message = 'The administrator was successfully created';

        $obj = (New User())->mailFind($request->email, $request->id);

        if( count($obj) > 0 ){
            $status = 400; $obj = []; $message = 'This email is already registered';
        }else{
            //UserResetPassword PasswordResetTokens
            $obj = (New User())->userSave($request);
            //$obj = User::where( 'id', 11 );

            if($request->id == ''){
                $token = Hash::make($request->email);
                $objToken = (New UserResetPassword())->tokenized($obj, $token);

                $expirationMail = 'Expiration date';
                Mail::to( $obj->email )->send( (new mailUserCreate($obj->name, $obj->email, $token, $expirationMail)) );
            }else{
                $message = 'The administrator was successfully edited';
            };
        };


        return response()->json([
            'User' => $obj,
            'message' => $message,
            'status' => $status
        ]);
    }

    public function resetPassword(Request $request){
        //$obj = new Cities;
        $message = 'The password was successfully established';  $status = 200;

        $token = $request->token;
        $password = $request->password;
        $passwordConfirmation = $request->passwordConfirmation;

        $req = 0;$reqmss ='';$art = ' is ';
        if(!$token){ $req = $req + 1;      $reqmss = 'Token';      $status = 400;        };
        if(!$password){ $req = $req + 1;            $status = 400;
            if($req>0){$reqmss = $reqmss.', ';};
            $reqmss = $reqmss.'Password';
        };
        if(!$passwordConfirmation){ $req = $req + 1;            $status = 400;
            if($req>0){$reqmss = $reqmss.', ';};
            $reqmss = $reqmss.'Password Confirmation';
         };
         if($req>1){$art  = ' are ';};
         if($req>0){
            $message = $reqmss.$art.' required. ';
            $status = 400;
         };
         if($req < 3 && $password){
            //contar la cantidad de digitos en pass
            if(strlen($password) < 8){
                $message = $message.'Password must have 8 or more digits. ';
                $status = 400;
            };
            if($password != $passwordConfirmation){
                $message = $message.'Password doesn’t match';
                $status = 400;
            };
         };


        $objToken = (New UserResetPassword())->findUser($token);
        if($objToken && $status == 200){
            //$obj = User::where( 'id', $objToken->idUser );
            $objToken->active = 0;
            $objToken->updated_at = date("Y-m-d H:i:s");
            $objToken -> save();

            $obj = (New User())->saveUserPassword($objToken->idUser, $password);
        }else{
            $message = 'This token has already been used';
            $status = 400;
        };

        //*/

        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }






    public function resetPerfilPassword(Request $request){
        //$obj = new Cities;
        $message = 'The password was successfully established';  $status = 200;


        Log::info("### SESSION DATTA ##");
        $email = $request->user()->email;
        $userId = $request->user()->id;

        Log::info($email);
        $originalPassword = $request->originalPassword;
        $password = $request->password;
        $passwordConfirmation = $request->passwordConfirmation;

        //$obj = (New LoginController())->check($email, $originalPassword);
        $user = Auth::user();
        if (!\Hash::check($originalPassword, $user->password)) {
            return response()->json([
                'message' => 'Incorrect password',
                'status' => 400
            ]);
        }else{
            Log::info("-->todo correcto");
        };

        $req = 0;$reqmss ='';$art = ' is ';

        if(!$password){ $req = $req + 1;            $status = 400;
            if($req>0){$reqmss = $reqmss.', ';};
            $reqmss = $reqmss.'Password';
        };
        if(!$passwordConfirmation){ $req = $req + 1;            $status = 400;
            if($req>0){$reqmss = $reqmss.', ';};
            $reqmss = $reqmss.'Password Confirmation';
         };
         if($req>1){$art  = ' are ';};
         if($req>0){
            $message = $reqmss.$art.' required. ';
            $status = 400;
         };
         if($req < 3 && $password){
            //contar la cantidad de digitos en pass
            if(strlen($password) < 8){
                $message = 'Password must have 8 or more digits. ';
                $status = 400;
            };
            if($password != $passwordConfirmation){
                $message = 'Password doesn’t match';
                $status = 400;
            };
         };


         if($status == 200){
            $obj = (New User())->saveUserPassword($userId, $password);
        };

        //*/

        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }






    public function delete($id){

        $status = 200;  $message = 'The administrator was successfully deleted';

        $totalUsers =(New User())->list('', 1, 99999999999999);
        $total = count($totalUsers);
        if($total > 1){
            $obj = User::findOrFail($id);
            $obj -> delete();
        }else{
            $status = 400;  $message = 'The user cannot be deleted because is the only user on the system';
        };

        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }


    public function find($id){

        $status = 200;  $message = '';
            $obj = User::select("id", "name", "email")->where('id', $id)->first();
        if($obj){
        }else{
            $status = 400;  $message = 'The user cannot be find';
        };

        return response()->json([
            'message' => $message,
            'status' => $status,
            'user' => $obj
        ]);
    }


    public function forgotPassword(Request $request){
        $status = 200;$message = 'An email has been sent to reset your password';

        $obj = User::select("id", "name", "email")->where('email', $request->email)->first();

        if( $obj ){
            $token = Hash::make($request->email);
            $objToken = (New UserResetPassword())->tokenized($obj, $token);

            $expirationMail = 'Expiration date';
            Mail::to( $obj->email )->send( (new mailResetUserPassword($obj->name, $obj->email, $token, $expirationMail)) );
        }else{
            $status = 200;$message = 'The user was not found, check the email entered';
        };
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }


    public function login(Request $request){
        $message="Welcome to the UNESCO Cities of Gastronomy";$status = 200;
        $email = $request->email;
        $pass = $request->password;

        $validator = $this->validateLogin($request);

        if ($validator->fails()) {
            //login fails: do anything

        } else {
            // login success: do something

        }

        $obj = User::select("id", "name", "email")->where('id', $id)->first();
        if($obj){
        }else{
            $status = 400;  $message = 'The user cannot be find';
        };
        $token = Hash::make($request->email);

        return response()->json([
            'message' => $message,
            'status' => $status,
            'token' => $token
        ]);
    }

}
