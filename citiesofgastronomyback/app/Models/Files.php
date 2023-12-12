<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Files extends Model
{
    use HasFactory;
    protected $table = "files";


    public function storeFILE( $file, $id, $section, $title, $active ){


        $archive =  $file->store('public/files');
        $archive = str_replace('public/', 'storage/', $archive);

        $objCity = new Files;
        $objCity->file = $archive;
        $objCity->idOwner = $id;
        $objCity->title = $title;
        $objCity->active = $active;
        $objCity->idSection = $section;
        $objCity->created_at = date("Y-m-d H:i:s");
        $objCity->updated_at = date("Y-m-d H:i:s");
        $objCity -> save();
        return $objCity;
    }

    public function saveFILE( $id, $file, $idOwner, $section, $title, $active ){

        $objCity = Files::find($id);


        if($file){
            $archive =  $file->store('public/files');
            $archive = str_replace('public/', 'storage/', $archive);
            $objCity->file = $archive;
        };
        $objCity->title = $title;
        $objCity->active = $active;
        $objCity->updated_at = date("Y-m-d H:i:s");
        $objCity -> save();
        return $objCity;
    }

    public function list( $seccion, $idOwner )
    {
        return $this    -> select("file", "title", "id" )
                      -> where( "active", '=', '1' )
                      -> where( "idSection", '=', $seccion )
                      -> where( "idOwner", '=', $idOwner )
                      -> get()
                      -> toArray();
    }
}
