<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cities;
use App\Models\Banners;
use App\Models\Info;
use App\Models\Newletter;
use App\Models\SocialNetwork;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function home(){

        $objCities =(New Cities())->list(1, 99999);

        $objAbout = (New Banners())->list(2, 0);
        //Log::info("BAnner controller");
        if(   count($objAbout)>0   ){$bannerAbount = $objAbout[0]["banner"];
        }else{$bannerAbount = '';        };
        Log::info($bannerAbount);

        $bannerNumberAndStats = (New Banners())->list(4, 0);
        if($bannerNumberAndStats){$bannerNumberAndStats = $bannerNumberAndStats[0]["banner"];
        }else{$bannerNumberAndStats = '';        };

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        return response()->json([
            'bannerAbout' => $bannerAbount,
            'bannerNumberAndStats' => $bannerNumberAndStats,
            'recentInitiatives' => [],
            'news' => [],
            'openCalls' => [],
            'coordinator' => '',
            'contactMail' => '',
            'cities' => $objCities,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);
    }




    public function newsletter(Request $request){

        $obj = (New Newletter())->store($request);

        return response()->json([
            'datta' => $obj["datta"],
            'mensaje' => $obj["mensaje"],
            'status' => $obj["status"]
        ]);
    }


    public function newsletterList(Request $request){
        $cantItems = 50;
        $paginator = 1;
        $page = $request->page;


        $obj =(New Newletter())->list($page, $cantItems);
        $objTOT =(New Newletter())->list(1, 999999999999999999);

        $total = count($objTOT);
        if($total > $cantItems){
            $division = $total / $cantItems;
            $paginator = intval($division);
            if($paginator < $division){
                $paginator = $paginator +1;
            };
        };

        return response()->json([
            'total' => $total,
            'paginator' => $paginator,
            'maillist' => $obj,
            'page' => $page
        ]);
    }



}
