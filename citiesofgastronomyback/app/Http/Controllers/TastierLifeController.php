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

        $category = $request->recipeCategoryFilter;
        $chef = $request->recipeChefFilter;
        $city = $request->recipeCityFilter;

        /////////////////////////RECIPES
        $objRecipes= ( New Recipes() )->list($search, $page, $cantItems, $chef, $category, $city);

        $totalRecipes= ( New Recipes() )->list($search, 1, 999999999999, $chef, $category, $city);

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


        $objCities =(New Cities())->searchList('', 1, 999999999999999999);

        return response()->json([
            'recipes' => $objRecipes,
            'tot' => $total,
            'paginator' => $paginator,
            'totCHEF' => $totalCH,
            'paginatorCHEF' => $paginatorCHEF,
            'chef' => $chef,
            'categories' => $categories,
            'cities' => $objCities,
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


    public function showRecipe($id){
        $Chef = (New Chef())->list('', 1, 99999999);
        $categories = (New Categories())->list('');
        $objCities =(New Cities())->searchList('', 1, 999999999999999999);

        $obj = [];$objgallery = [];
        try{
            $obj = (New Recipes() )->findResipe($id);

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

    public function storeRecipe(Request $request){

        $message = 'The Recipe was successfully created';$status = 200;

        $photo = '';
            if($request->file("photo")){
                try{
                    $request->validate ([
                        'photo' => 'image|max:50000'
                    ]);
                    //$photo =  $request->file("photo")->store('public/images/Initiatives');
                    //$photo = str_replace('public/', 'storage/', $photo);

                    $photo = (New Images())->storeResize($request->file("photo"), '1158', '845', 'recipes');
                } catch ( \Exception $e ) {

                }
            };
            Log::info($photo );

        if(  !$request->input("id")  ){
            Log::info("::CREA receta");
            $objItem = new Recipes;
            $objItem->created_at = date("Y-m-d H:i:s");
            $objItem->active = '1';
        }else{
            Log::info("::MODIFICA Crecetaef");
            $objItem = Recipes::findOrFail( $request->input("id")  );
            $message = 'The Recipe was successfully edited';
        };
        $objItem->idChef = $request->input("idChef");
        $objItem->idCity = $request->input("idCity");
        $objItem->idCategory = $request->input("idCategory");
        $objItem->name = $request->input("name");
        if($photo != ''){
            Log::info("#si existe la foto");
            $objItem->photo = $photo;
        };
        $objItem->description = $request->input("description");
        $objItem->difficulty = $request->input("difficulty");
        $objItem->prepTime = $request->input("prepTime");
        $objItem->totalTime = $request->input("totalTime");
        $objItem->servings = $request->input("servings");
        $objItem->ingredients = $request->input("ingredients");
        $objItem->preparations = $request->input("preparations");

        $objItem->updated_at = date("Y-m-d H:i:s");
        $objItem -> save();

        $id = $objItem->id;


        /////////////////////////////   GALLERY
        $cant_gallery = $request->input("cant_gallery");
        Log::info("------------ GALLERY ->");
        Log::info($cant_gallery);

        for($i = 1; $i < $cant_gallery+1; $i++){
            $idg = 'image'.$i;
            $image = $request->file($idg);
            Log::info($image);
            $idg = 'idImage'.$i;
            $idImage = $request->input($idg);
            $idg = 'deleteImage'.$i;
            $deleteImage = $request->input($idg);

            if(!$idImage){
                if(!$deleteImage){
                    if($image){

                        try{
                            $objGallery =(New Images())->storeIMG($image, $id, 8);
                        } catch ( \Exception $e ) {
                            Log::info($e);
                        }
                    };
                };
            }else{
                if($deleteImage){
                    $obsDEL = Images::find($idImage);
                    $obsDEL->active = 2;
                    $obsDEL->updated_at = date("Y-m-d H:i:s");
                    $obsDEL -> save();
                    //Log::info("IMAGEN Borrada");
                    //Log::info($obsDEL);
                };
            };

        }
        ////////////////////////////////////////////////////

        return response()->json([
            'recipe' => $objItem,
            'message' => $message,
            'status' => $status
        ]);

    }





    public function delete($id){
        //Log::info("Categories Delete ::");
        $status = 200;$message = 'The Recipe was successfully deleted';

        //Log::info($obj);

        if( $id ){
            $objRecipe = Recipes::find($id);
            if($objRecipe != NULL){
                $objRecipe->delete();
                //DELETE IMAGES
                $obsDEL = Images::where('idOwner', $id)->where('idSection', '8')->delete();
            };
        };

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }





    public function vote($id){
        //Log::info("Categories Delete ::");
        $status = 200;$message = 'The Recipe was successfully voted';

        //Log::info($obj);
        $vote = 0;
        if( $id ){
            $objRecipe = Recipes::find($id);
            if($objRecipe != NULL){
                $vote = $objRecipe->vote + 1;
                $objRecipe->vote = $vote;
                $objRecipe->save();
            }else{
                $status = 400;$message = 'Recipe not found';
            };
        }else{

            $status = 400;$message = 'ERROR';
        };

        return response()->json([
            'status' => $status,
            'message' => $message,
            'votes' => $vote
        ]);
    }



}
