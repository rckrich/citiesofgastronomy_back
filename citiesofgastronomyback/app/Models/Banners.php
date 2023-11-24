<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banners extends Model
{
    use HasFactory;
    protected $table = "banners";

    public function storeBanner( $image, $section ){

        $banner =  $image->store('public/images/banners');
        $banner = str_replace('public/', 'storage/', $banner);

        $objCity = new Banners;
        $objCity->banner = $banner;
        //$objCity->idOwner = $id;
        //$objCity->active = 1;
        $objCity->idSection = $section;
        $objCity->created_at = date("Y-m-d H:i:s");
        $objCity->updated_at = date("Y-m-d H:i:s");
        $objCity -> save();
    }

    public function list( $seccion )
    {
        return $this    -> select("banner" )
                      -> where( "idSection", '=', $seccion )
                      -> orderBy('id', 'desc')
                      -> get()
                      -> toArray();
    }
}
