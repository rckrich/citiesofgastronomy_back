<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkType extends Model
{
    use HasFactory;
    protected $table = "social_network_type";

    public function list( $type=1 )
    {

            if($type == 1){
                $obj = $this    -> select("name", "id", "codde", "icon", "active" )
                                -> where( "active", '=', "1" )
                                -> orderBy('id', 'desc')-> get()-> toArray();
            }elseif($type == 2){
                $obj = $this    -> select("name", "id", "codde", "icon", "active" )
                                -> whereIn( "active", ["1", "2"] )
                                -> orderBy('id', 'desc')-> get()-> toArray();
            };

        return $obj;

    }
}
