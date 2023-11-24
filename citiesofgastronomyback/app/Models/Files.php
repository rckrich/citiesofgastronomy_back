<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;
    protected $table = "files";


    public function storeFILE( $file, $id, $section, $title ){

        $archive =  $file->store('public/files');
        $archive = str_replace('public/', 'storage/', $archive);

        $objCity = new Files;
        $objCity->file = $archive;
        $objCity->idOwner = $id;
        $objCity->title = $title;
        $objCity->active = 1;
        $objCity->idSection = $section;
        $objCity->created_at = date("Y-m-d H:i:s");
        $objCity->updated_at = date("Y-m-d H:i:s");
        $objCity -> save();
    }

    public function list( $seccion, $idOwner )
    {
        return $this    -> select("file", "title" )
                      -> where( "active", '=', '1' )
                      -> where( "idSection", '=', $seccion )
                      -> where( "idOwner", '=', $idOwner )
                      -> get()
                      -> toArray();
    }
}
