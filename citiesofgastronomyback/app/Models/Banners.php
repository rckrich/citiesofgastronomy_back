<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Images;
use App\Models\Sections;

class Banners extends Model
{
    use HasFactory;
    protected $table = "banners";

    public function storeBanner(  $banner, $section, $idOwner ){
        Log::info("###--------------> INGRESO A STORE BANNER");
        //$banner =  $image->store('public/images/banners');
        //$banner = str_replace('public/', 'storage/', $banner);
        $width = '1920';
        $heigth = '480';
        if($section == '2'){    //ABOUT
            $width = '1158';
            $heigth = ' 845';
        }elseif($section == '4'){   //NUMBER OF STATS
            $width = '1920';
            $heigth = ' 447';
        };
        //1920 x 480

        $sectionCode = Sections::find($section);
        $folder = $sectionCode->code;
        //Log::info("carpeta-->");
        //Log::info($section);
        //Log::info($sectionCode);
        //Log::info($folder);
        if($folder == ''){$folder = 'banners';};

        $banner = (New Images())->storeResize( $banner, $width, $heigth, $sectionCode->code);

        $objCity = new Banners;
        $objCity->banner = $banner;
        $objCity->idOwner = $idOwner;
        //$objCity->active = 1;
        $objCity->idSection = $section;
        $objCity->created_at = date("Y-m-d H:i:s");
        $objCity->updated_at = date("Y-m-d H:i:s");
        $objCity -> save();
        return $objCity;
    }


    public function changeBanner(  $banner, $idBanner ){
        Log::info("###--------------> INGRESO A     CHANGE BANNER");

        $objCity = Banners::find($idBanner);
        $section = $objCity->idSection;


        //$banner =  $image->store('public/images/banners');
        //$banner = str_replace('public/', 'storage/', $banner);
        $width = '1920';
        $heigth = '480';
        if($section == '2'){    //ABOUT
            $width = '1158';
            $heigth = ' 845';
        }elseif($section == '4'){   //NUMBER OF STATS
            $width = '1920';
            $heigth = ' 447';
        };
        //1920 x 480

        $sectionCode = Sections::find($section);
        $folder = $sectionCode->code;
        if($folder == ''){$folder = 'banners';};

        $banner = (New Images())->storeResize( $banner, $width, $heigth, $sectionCode->code);

        $objCity->banner = $banner;
        $objCity->updated_at = date("Y-m-d H:i:s");
        $objCity -> save();
    }
    public function list( $seccion, $idOwner )
    {
       // $objBanner = $this;
        //if($idOwner > 0){
            $objBanner = $this  -> select("banner", "id" )
                                -> where( "idOwner", '=', $idOwner )
                                -> where( "idSection", '=', $seccion )-> orderBy('id', 'desc')-> get()-> toArray();
        //}else{
        //    $objBanner = $this  -> select("banner" )-> where( "idSection", '=', $seccion )-> orderBy('id', 'desc')-> get()-> toArray();
        //};

        return $objBanner;

    }
}
