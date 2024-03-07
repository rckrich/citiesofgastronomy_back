<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;
use App\Models\Info;
use App\Models\Chef;
use App\Models\SocialNetwork;
use App\Models\Recipes;
use App\Models\Categories;
use Illuminate\Support\Facades\Log;

class TastierLifeController extends Controller
{



    public function index(Request $request)
    {
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;
        if($page == ''){$page = 1;};
        $search = $request->search;
        $objRecipes = [];
        $total = 0;


        /////////////////////////RECIPES
        $objRecipes= ( New Recipes() )->list($search, $page, $cantItems);
        /////////////////////////END RECIPES




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
        $pageChef = $request->pageChef;
        $searchChef = $request->searchChef;
        if(!$pageChef){ $pageChef=1; };

        $chef = (New Chef())->list($searchChef, $pageChef, 20);

        $totalChef = (New Chef())->list($searchChef, 1, 99999999);

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

        /////////////////////////CATEGORIES
        $searchCAT = $request->searchCAT;

        $categories = (New Categories())->list($searchCAT);

        ///////////////////////// FIN CATEGORIES

        return response()->json([
            'recipes' => $objRecipes,
            'tot' => $total,
            'paginator' => $paginator,
            'totCHEF' => $totalCH,
            'paginatorCHEF' => $paginatorCHEF,
            'chef' => $chef,
            'categories' => $categories,
            'banner' => $objBanners,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);
    }



}
