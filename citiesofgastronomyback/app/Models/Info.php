<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;
    protected $table = "info";
    public function type(){

        return array(
            0 => array(
                'key' => "coordinator",
                'nombre'  => "Coordinator",
                'placeholder'  => ""
            ),
            1 => array(
                'key' => "clusterMail",
                'nombre'  => "Contact Mail",
                'placeholder'  => ""
            ),
            2 => array(
                'key' => "clusterContactCities",
                'nombre'  => "Contact Cities",
                'placeholder'  => ""
            )
        );

    }

    public function list( $type )
    {
            $obj = $this    -> select("id", "description" )
                            -> orderBy('id', 'desc')
                            -> where("type", $type)
                            -> first()
                            ;
        if($obj ){$obj -> toArray();
        }else{
            $obj  = [];
        };
        return $obj;

    }

    public function saveInfo($type, $description){

        $objInfo = $this  -> select("description", "type", "id" )
            -> where( "type", '=', $type )
            -> orderBy('id', 'desc')-> first();
            //Log::info("##id : ".$objLink->id);
            if(!$objInfo){
                $objInfo = new Info;
                $objInfo->type = $type;
                $objInfo->created_at = date("Y-m-d H:i:s");
            };
            if($description != '' && $description != NULL){
                $objInfo->description = $description;
                $objInfo->updated_at = date("Y-m-d H:i:s");
            };
            $objInfo -> save();

    }





}
