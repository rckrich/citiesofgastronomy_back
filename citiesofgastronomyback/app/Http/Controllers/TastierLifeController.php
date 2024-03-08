<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;
use App\Models\Info;
use App\Models\Chef;
use App\Models\Cities;
use App\Models\Images;
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

        $totalRecipes= ( New Recipes() )->list($search, 1, 999999999999);

        $paginator = 1;
        $total = count($totalRecipes);
        if($total > $cantItems){
            $division = $total / $cantItems;
            $paginator = intval($division);
            if($paginator < $division){
                $paginator = $paginator + 1;
            };
        };
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



    public function findRecipe($id){
        $Chef = (New Chef())->list('', 1, 99999999);
        $categories = (New Categories())->list('');
        $objCities =(New Cities())->searchList('', 1, 999999999999999999);

        $obj = [];$objgallery = [];
        try{
                $obj = Recipes::where('id', $id)->first();

                $objgallery = (New Images())->list(8, $id);
        }catch(\Exception $e){};

        return response()->json([
            'Recipes' => $obj,
            'Gallery' => $objgallery,
            'Chef' => $Chef,
            'Cities' => $objCities,
            'categories' => $categories
        ]);
    }

    public function create(){
        //Categories;Chef
        $Chef = (New Chef())->list('', 1, 99999999);
        $categories = (New Categories())->list('');
        $objCities =(New Cities())->searchList('', 1, 999999999999999999);


        return response()->json([
            'Chef' => $Chef,
            'categories' => $categories,
            'Cities' => $objCities
        ]);
    }

    public function store(Request $request){
        /*
        $messaje = 'The chef was successfully created';

        if(  !$request->input("id")  ){
            Log::info("::CREA Chef");
            $objItem = new Chef;
            $objItem->created_at = date("Y-m-d H:i:s");
            //$objItem->active = '1';
        }else{
            Log::info("::MODIFICA Chef");
            $objItem = Chef::findOrFail( $request->input("id")  );
        };
        $objItem->name = $request->input("name");
        $objItem->updated_at = date("Y-m-d H:i:s");
        $objItem -> save();

        $objLink = (New SocialNetwork()) -> storeLink( $request , $objItem->id, 2  );

        return response()->json([
            'chef' => $objItem,
            'messaje' => $messaje
        ]);
        //*/
    }


}
