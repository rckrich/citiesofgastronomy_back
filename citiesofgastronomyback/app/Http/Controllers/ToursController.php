<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;
use App\Models\Info;
use App\Models\Tours;
use App\Models\Images;
use App\Models\Cities;
use App\Models\SocialNetwork;
use App\Models\SocialNetworkType;
use Illuminate\Support\Facades\Log;

class ToursController extends Controller
{



    public function index(Request $request)
    {
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;
        $objTours = [];
        $total = 0;

        //$objTours =(New Timeline())->searchList($request->search, $page,$cantItems);

        $objBanners = (New Banners())->list(9, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        return response()->json([
            'tours' => $objTours,
            'tot' => $total,
            'paginator' => $paginator,
            'banner' => $objBanners,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);
    }


    public function list(Request $request)
    {


    }

    public function create(Request $request)
    {
        $objsocial = (New SocialNetworkType())->list();
        $objCities =(New Cities())->searchList('', 1, 999999999999999999);

        return response()->json([
            'cities' => $objCities,
            'social' => $objsocial
        ]);
    }

    public function store(Request $request)
    {
        $message = 'The tour was successfully created';

        $photo = '';
        Log::info($request->file("photo") );
            if($request->file("photo")){
                try{
                    $request->validate ([
                        'photo' => 'image|max:50000'
                    ]);
                    //$photo =  $request->file("photo")->store('public/images/Initiatives');
                    //$photo = str_replace('public/', 'storage/', $photo);

                    $photo = (New Images())->storeResize($request->file("photo"), '1158', '845', 'tours');
                } catch ( \Exception $e ) {
                    Log::info($e);
                }
            };
            //Log::info($photo );

        if(  !$request->input("id")  ){
            Log::info("::CREA ");
            $objItem = new Tours;
            $objItem->created_at = date("Y-m-d H:i:s");
            //$objItem->active = '1';
        }else{
            Log::info("::MODIFICA");
            $objItem = Tours::findOrFail( $request->input("id")  );
            $message = 'The tour was successfully edited';
        };
        $objItem->idCity = $request->input("idCity");
        $objItem->name = $request->input("name");
        if($photo != ''){
            Log::info("#si existe la foto");
            $objItem->photo = $photo;
        };
        $objItem->description = $request->input("description");
        $objItem->travelAgency = $request->input("travelAgency");

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
                            $objGallery =(New Images())->storeIMG($image, $id, 9);
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


        $objLink = (New SocialNetwork()) -> storeLink( $request , $id  );

        return response()->json([
            'tour' => $objItem,
            'message' => $message
        ]);
    }

    public function find(Request $request)
    {


    }

    public function show(Request $request)
    {
    }

}
