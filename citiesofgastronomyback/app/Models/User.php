<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function list($search, $page, $cant){

        $result = [];
        $offset = ($page-1) * $cant;

        $result =  $this    -> select("id", "name", "email" )
                                -> where( "name",  "LIKE", "%$search%" )
                                -> orWhere( "email",  "LIKE", "%$search%" )
                                -> orderBy("name", 'ASC' )
                                -> limit($cant)
                                -> offset($offset)
                                -> get()-> toArray();


        return $result;

    }


    public function userSave(Request $request){
        $status = 200;$mensaje="The administrator was successfully created";

            $objItem=[];

            try{
                if(  !$request->input("id")  ){

                    Log::info("::CREA User");
                    $objItem = new User;
                    $objItem->created_at = date("Y-m-d H:i:s");
                    $objItem->password = '111';
                    //$objItem->active = '1';
                }else{
                    Log::info("::MODIFICA User");
                    $objItem = User::findOrFail( $request->input("id")  );
                };
                $objItem->name = $request->input("name");
                $objItem->email = $request->input("email");
                $objItem->updated_at = date("Y-m-d H:i:s");
                $objItem -> save();

            } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Error";
            };


            return $objItem;
      }


      public function mailFind($mail, $id){
        if($id == ''){
                return $this ->select("id", "name", "email")-> orderBy("name", 'ASC')-> where( "email", 'LIKE', "%{$mail}%")-> get()-> toArray();
        }else{
            return $this ->select("id", "name", "email")-> orderBy("name", 'ASC')
            -> where( "email", 'LIKE', "%{$mail}%")->where("id", "!=", $id)-> get()-> toArray();
        };
      }


      public function saveUserPassword($idUser, $password){
            $objItem = User::findOrFail( $idUser  );
            $objItem->password = Hash::make($password);
            $objItem->updated_at = date("Y-m-d H:i:s");
            $objItem -> save();

            return $objItem;
      }
}
