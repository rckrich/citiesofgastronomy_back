<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserResetPassword extends Model
{
    use HasFactory;
    protected $table = "user_reset_password";

    public function tokenized($user, $token){

        /*$objItem = UserResetPassword::where('email',  $email);
        //$objItem = UserResetPassword::where( 'email', $email );
        //$objItem = $this  -> select("email", "token", "created_at")->where( 'email', $email );

        if(!$objItem){
        };//*/
        Log::info($user);
        $fecha_actual = date("Y-m-d H:i:s");

        $objItem = new UserResetPassword;
        //$objItem->email = $email;
        $objItem->idUser = $user->id;
        $objItem->token = $token;
        $objItem->expirationDate = date("Y-m-d",strtotime($fecha_actual."+ 1 days"));
        $objItem->active = 1;
        $objItem->created_at = $fecha_actual;
        $objItem->updated_at = $fecha_actual;
        $objItem -> save();


        return $objItem;
    }

    public function findUser($token){
        $user = $this   -> where( "token", '=', $token ) ->where('active', '1') -> first();

        return $user;
    }

}
