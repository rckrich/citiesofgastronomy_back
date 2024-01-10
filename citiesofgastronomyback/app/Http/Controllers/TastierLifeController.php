<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;
use App\Models\Info;
use App\Models\SocialNetwork;

class TastierLifeController extends Controller
{



    public function index(Request $request)
    {
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;
        $objTastierLife = [];
        $total = 0;

        //$objTastierLife =(New Timeline())->searchList($request->search, $page,$cantItems);

        $objBanners = (New Banners())->list(8, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        return response()->json([
            'tastierLife' => $objTastierLife,
            'tot' => $total,
            'paginator' => $paginator,
            'banner' => $objBanners,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);
    }



}
