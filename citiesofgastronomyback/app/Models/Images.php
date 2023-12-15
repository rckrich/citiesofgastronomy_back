<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;

class Images extends Model
{
    use HasFactory;
    protected $table = "images";


    public function storeIMG( $image, $id, $section ){

        $photo =  $image->store('public/images/gallery');
        $photo = str_replace('public/', 'storage/', $photo);

        //$photo = (New Images())->storeResize($image, '700', '700', 'gallery');

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

    public function storeResize($image, $width, $height, $folder){
        Log::info("#1");
        // create image manager with desired driver
        $manager = ImageManager::gd();
        Log::info("#2");
        // open an image file
        $image = $manager->read($image);
        Log::info("#3");
        // resize image instance
        //$image->resize(width: $width);
        $image->resize(width: $width);
        $image->resize(height: $height);
        Log::info("#4");

        // insert a watermark
        //$image->place('images/watermark.png');

        // encode edited image
        $encoded = $image->toJpg();
        Log::info("#5");

        //VERIFICO SI exite el DIRECTORIO y si no --> CREA
        if(!is_dir('storage/images/'.$folder.'/')){
            @mkdir('storage/images/'.$folder.'/', 0777);
        }else{
            Log::info("Ya existe ese directorio\n");
        }

        $random = Str::random(10);
        $nombre = date("YmdHmi").$random.'.jpg';
        // save encoded image
        //http://127.0.0.1:8001/storage/images/cities/mtCwfKFbUNfFq0kv8Eatqwb8IAJ7fF0ZVEEnw9lY.jpg
        $encoded->save('storage/images/'.$folder.'/'.$nombre);
        //$encoded->save('storage/images/cities/'.$nombre);

        Log::info("#terminoooo");
        return 'storage/images/'.$folder.'/'.$nombre;
    }

}
