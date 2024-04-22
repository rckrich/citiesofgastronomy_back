<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;
use App\Models\Banners;
use App\Models\SocialNetwork;
use App\Models\SocialNetworkType;
use Illuminate\Support\Facades\Log;

class MainSiteContentController extends Controller
{
    public function home(){
        $infoArray = (New Info())->type();

        $objAbout = (New Banners())->list(2, 0);
        //Log::info("BAnner controller");
        if(   count($objAbout)>0   ){$bannerAbount = $objAbout[0]["banner"];
        }else{$bannerAbount = '';        };
        //Log::info($bannerAbount);

        $bannerNumberAndStats = (New Banners())->list(4, 0);
        if($bannerNumberAndStats){$bannerNumberAndStats = $bannerNumberAndStats[0]["banner"];
        }else{$bannerNumberAndStats = '';        };
        //Log::info($bannerNumberAndStats);

        //CITIES
        $bannerCities = (New Banners())->list(1, 0);
        if(!$bannerCities){$bannerCities = [];        };

        //ABOUT
        $About = (New Banners())->list(6, 0);
        if(!$About){$About = [];        };

        //Initiatives
        $Initiatives = (New Banners())->list(7, 0);
        if(!$Initiatives){$Initiatives = [];        };

        //Testier
        $Testier = (New Banners())->list(8, 0);
        if(!$Testier){$Testier = [];        };

        //Tour
        $Tour = (New Banners())->list(9, 0);
        if(!$Tour){$Tour = []; };

        //Calendar
        $Calendar = (New Banners())->list(10, 0);
        if(!$Calendar){$Calendar = [];        };

        //Contact
        $Contact = (New Banners())->list(11, 0);
        if(!$Contact){$Contact = [];        };

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        //foreach($infoArray as $valor){
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            //Log::info($infoArray[$i]);
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $arrResponse = [
            'Contact' => $Contact,
            'Calendar' => $Calendar,
            'Tour' => $Tour,
            'Testier' => $Testier,
            'bannerAbout' => $bannerAbount,
            'About' => $About,
            'Initiatives' => $Initiatives,
            'bannerNumberAndStats' => $bannerNumberAndStats,
            'bannerCities' => $bannerCities,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ];
        return response()->json($arrResponse);
    }
    public function linkStore(Request $request){

        $status = 200;$mensaje = '';$objLink = [];
        Log::info("--------->### SAVE LINKS");
        //if($request->file("banner")){
            try{
                $idOwner = $request->input("idOwner");
                $objLink = (New SocialNetwork())->storeLink( $request ,  $idOwner );
            } catch ( \Exception $e ) {
                Log::info("### ERROR store Link");
                Log::info($e);
            }
        // };
        return response()->json([
            'status' =>  $status,
            'message' =>  $mensaje,
            'datta' =>  $objLink
        ]);
    }

    public function clustersave(Request $request){

        $infoArray = (New Info())->type();

        for($i=0; $i < count($infoArray); $i++){
            $type = $infoArray[$i]["key"];
            Log::info("------------------");
            Log::info($type);
            $description = $request->input($type);
            Log::info($description);
            try{
                $objLink = (New Info())->saveInfo( $type, $description  );
            } catch ( \Exception $e ) {
                Log::info("### ERROR store Link".$type );
                Log::info($e);
            }
        }
        return response()->json([
            'status' => 200,
            'message' =>  'CLUSTER COORDINATOR INFO'
        ]);
    }
}
