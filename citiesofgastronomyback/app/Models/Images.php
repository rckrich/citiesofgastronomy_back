<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Images extends Model
{
    use HasFactory;
    protected $table = "images";


    public function storeIMG( $image, $id, $section ){

        $photo =  $image->store('public/images/gallery');
        $photo = str_replace('public/', 'storage/', $photo);

        $objCity = new Images;
        $objCity->image = $photo;
        $objCity->idOwner = $id;
        $objCity->active = 1;
        $objCity->idSection = $section;
        $objCity->created_at = date("Y-m-d H:i:s");
        $objCity->updated_at = date("Y-m-d H:i:s");
        $objCity -> save();
    }

    public function list( $seccion, $idOwner )
    {
        return $this    -> select("image", "id" )
                      -> where( "active", '=', '1' )
                      -> where( "idSection", '=', $seccion )
                      -> where( "idOwner", '=', $idOwner )
                      -> get()
                      -> toArray();
    }

}
