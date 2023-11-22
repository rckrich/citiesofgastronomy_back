<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    use HasFactory;
    protected $table = "links";


    public function storeLINK( $link, $id, $section ){

        $objCity = new Links;
        $objCity->image = $link;
        $objCity->idOwner = $id;
        $objCity->active = 1;
        $objCity->idSection = $section;
        $objCity->created_at = date("Y-m-d H:i:s");
        $objCity->updated_at = date("Y-m-d H:i:s");
        $objCity -> save();
    }

    public function list( $seccion, $idOwner )
    {
        return $this    -> select("image" )
                      -> where( "active", '=', '1' )
                      -> where( "idSection", '=', $seccion )
                      -> where( "idOwner", '=', $idOwner )
                      -> get()
                      -> toArray();
    }
}
