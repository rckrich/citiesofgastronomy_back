<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;
use App\Models\Info;
use App\Models\Chef;
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

        /////////////////////////CHEF
        $page = $request->page;
        $search = $request->search;
        if(!$page){ $page=1; };

        $chef = (New Chef())->list($search, $page, $cantItems);

        $totalChef = (New Chef())->list($search, 1, 99999999);

        $paginatorCHEF = 1;
        $totalCH = count($totalChef);
        if($totalCH > $cantItems){
            $division = $totalCH / $cantItems;
            $paginatorCHEF = intval($division);
            if($paginatorCHEF < $division){
                $paginatorCHEF = $paginatorCHEF + 1;
            };
        };
        ///////////////////////// FIN CHEF

        return response()->json([
            'tastierLife' => $objTastierLife,
            'tot' => $total,
            'paginator' => $paginator,
            'totCHEF' => $totalCH,
            'paginatorCHEF' => $paginatorCHEF,
            'chef' => $chef,
            'banner' => $objBanners,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);
    }



}
