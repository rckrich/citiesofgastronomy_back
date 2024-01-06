<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\SocialNetworkType;
use Illuminate\Support\Facades\Log;

class SocialNetwork extends Model
{
    use HasFactory;
    protected $table = "social_network";


    public function storeLink(Request $request){
        $idSection = $request->input("idSection");
        $idOwner = $request->input("idOwner");


        $SocialNetworkType = (New SocialNetworkType())->list();
        foreach($SocialNetworkType as $type){
            $idSocial = $type["id"];
            $idReq = $type["codde"].'_link';
            $socialValue = $request->input($idReq);


            $objLink = $this  -> select("social_network", "id" )
            -> where( "idOwner", '=', $idOwner )
            -> where( "idSection", '=', $idSection )
            -> where( "idSocialNetworkType", '=', $idSocial )
            -> orderBy('id', 'desc')-> first();
            //Log::info("##id : ".$objLink->id);
            if(!$objLink){
                $objLink = new SocialNetwork;
                $objLink->idOwner = $idOwner;
                $objLink->idSocialNetworkType = $idSocial;
                $objLink->idSection = $idSection;
                $objLink->created_at = date("Y-m-d H:i:s");
            };
            //if($socialValue != '' && $socialValue != NULL){
                $objLink->social_network = $socialValue;
                $objLink->updated_at = date("Y-m-d H:i:s");
                $objLink -> save();

            //};

            //Instagram_link
        }


    }

    public function list($idSection, $idOwner){
        $SocialNetworkType = (New SocialNetworkType())->list();

        //foreach($SocialNetworkType as $type){
        for($i = 0; $i < count($SocialNetworkType) ; $i++){
            $SocialNetworkType[$i]["value"] = '';

            $objLink = $this  -> select("social_network", "id")
            -> where( "idOwner", '=', $idOwner )
            -> where( "idSection", '=', $idSection )
            -> where( "idSocialNetworkType", '=', $SocialNetworkType[$i]["id"] )
            -> orderBy('id', 'desc')-> first();

            if($objLink){
                $SocialNetworkType[$i]["value"] = $objLink["social_network"];
            };

        }
        return $SocialNetworkType;
    }
}
