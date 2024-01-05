<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkType extends Model
{
    use HasFactory;
    protected $table = "social_network_type";

    public function list(  )
    {
            $obj = $this    -> select("name", "id", "codde" )
                            -> where( "active", '=', "1" )
                            -> orderBy('id', 'desc')-> get()-> toArray();

        return $obj;

    }
}
