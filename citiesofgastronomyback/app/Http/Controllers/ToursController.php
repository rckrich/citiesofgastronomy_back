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



    public function index(Request $request, $type = 'user')
    {
        $message = '';$status='200';
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;
        $objTours = [];
        $total = 0;

        $objTours =(New Tours())->list($request->search, $page, $cantItems, $type);
        $totalTours =(New Tours())->list($request->search, 1, 99999999999, $type);
        $paginator = 1;
        $total = count($totalTours);
        if($total > $cantItems){
            $division = $total / $cantItems;
            $paginator = intval($division);
            if($paginator < $division){
                $paginator = $paginator + 1;
            };
        };
        if($total == 0){$message = "No results found";}

        $objBanners = (New Banners())->list(9, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        if($type == 'admin'){
            return response()->json([
                'tours' => $objTours,
                'tot' => $total,
                'paginator' => $paginator,
                'message' => $message,
                'status' => $status
            ]);
        }else{
            return response()->json([
                'tours' => $objTours,
                'tot' => $total,
                'paginator' => $paginator,
                'banner' => $objBanners,
                'SocialNetworkType' => $SocialNetworkType,
                'info' => $infoArray,
                'message' => $message,
                'status' => $status
            ]);
        };
    }


    public function list(Request $request)
    {

        return $this->index($request, 'admin');

    }

    public function create(Request $request)
    {
        $objsocial = (New SocialNetworkType())->list();
        $objCities =(New Cities())->searchList('', 1, 999999999999999999);

        return response()->json([
            'cities' => $objCities,
            'socialType' => $objsocial
        ]);
    }

    public function store(Request $request)
    {
        $message = 'The tour was successfully created';$status = 200;

        $photo = '';
        try{
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
                        $message = 'The tour was successfully created but there was a problem saving the image';
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
            Log::info("El Id es:");
            Log::info($objItem->id);


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
        } catch ( \Exception $e ) {
            if(!$request->file("photo") && !$request->input("id")){
                $message = 'Tour photo is required';
            }else{
                $message = 'There was a problem saving, try again later.';
            }
            Log::info($e);
            $objItem = [];
            $status=400;
        }//*/

        return response()->json([
            'tour' => $objItem,
            'message' => $message,
            'status' => $status
        ]);
    }

    public function find($id, $type="Admin")
    {
        $status = 200;$mess = '';
        if($id){
            if($type=="Admin"){
                $obj = Tours::select("tours.id", "tours.name", "tours.photo", "tours.travelAgency", "tours.description",
                            "tours.idCity")
                        ->join('cities', "cities.id", "tours.idCity")
                        ->where("tours.id", $id)
                        ->first();
            }else{

                $obj = Tours::select("tours.id", "tours.name", "tours.photo", "tours.travelAgency", "tours.description",
                            "cities.name AS cityName")
                        ->join('cities', "cities.id", "tours.idCity")
                        ->where("tours.id", $id)
                        ->with("socialNetwork")
                        ->get();
            };
            //$obj = Tours::findOrFail($id);
            $objsocialTours = (New SocialNetwork())->list(9, $id);
            $objgallery = (New Images())->list(9, $id);
        }else{
                $obj = [];
                $objsocialTours = [];
                $status = 200;$mess = 'The tour was not found';
        };

        $objsocial = (New SocialNetworkType())->list();
        $objCities =(New Cities())->searchList('', 1, 999999999999999999);

        $objBanners = (New Banners())->list(9, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        if($type=="Admin"){
            return response()->json([
                'tour' => $obj,
                'toursSocialNetwork' => $objsocialTours,
                'socialType' => $objsocial,
                'gallery' => $objgallery,
                'cities' => $objCities,
                'status' => $status,
                'message' => $mess
            ]);
        }else{
            return response()->json([
                'tour' => $obj,
                'gallery' => $objgallery,
                'info' => $infoArray,
                'status' => $status,
                'message' => $mess
            ]);
        }

    }

    public function show($id)
    {
        return $this->find($id, "user");
    }

    public function delete($id){
        //Log::info(" Delete ::");
        $status = 200;$message = 'The Recipe was successfully deleted';

        //Log::info($obj);

        if( $id ){
            $objRecipe = Tours::find($id);
            if($objRecipe != NULL){
                $objRecipe->delete();
                //DELETE gallery
                $obsDEL = Images::where('idOwner', $id)->where('idSection', '9')->delete();
                //SocialNetwork
                $obsDEL = SocialNetwork::where('idOwner', $id)->where('idSection', '9')->delete();
            };
        }else{
            $status = 400;$message = 'The Tour was not found';
        };

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

}
