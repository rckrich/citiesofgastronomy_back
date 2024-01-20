<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class FAQ extends Model
{
    use HasFactory;
    protected $table = "FAQ";

    public function list($search, $page, $cant)
    {
        Log::info("#si hay FAQ");
        $offset = ($page-1) * $cant;
        try{

            if($search){
                $result =  $this -> select( "id","faq","answer" )
                -> where( "faq",  "LIKE", "%$search%" )
                -> orderBy("id", 'DESC' )
                -> limit($cant)
                -> offset($offset)
                -> get()-> toArray();
            }else{
                $result =  $this -> select( "id","faq","answer" )
                -> orderBy("id", 'DESC' )
                -> limit($cant)
                -> offset($offset)
                -> get()-> toArray();
            };
        } catch ( \Exception $e ) {
            $result = [];
        };

        return $result;
    }

    public function serch( $id, $lActivo = true )
    {
        return $this    -> select("id","faq","answer")
                        //-> where( "active", '=', '1' )
                        -> where( "id", '=', $id )
                        -> first()
                        -> toArray();
    }

}
