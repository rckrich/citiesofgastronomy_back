<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timeline;
use App\Models\Banners;
use App\Models\Info;
use App\Models\SocialNetwork;
use Illuminate\Support\Facades\Log;

class AboutController extends Controller
{
    public function list(Request $request){
        $cantItems = 20;
        $paginator = 1;
        $total= 0;
        $page = $request->page;

        $objTimeline =(New Timeline())->list($page, $cantItems);
        $TotalTimeline =(New Timeline())->searchList($request->search, 1, 999999999999999999);
        $total = count($TotalTimeline);

        $objBanners = (New Banners())->list(6, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        return response()->json([
            'timeline' => $objTimeline,
            'tot' => $total,
            'paginator' => $paginator,
            'banner' => $objBanners,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);
    }

    public function listTimeline(Request $request){
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;


        if($request->search){
            $objTimeline =(New Timeline())->searchList($request->search, $page,$cantItems);
            $TotalTimeline =(New Timeline())->searchList($request->search, 1, 999999999999999999);
        }else{
            $objTimeline =(New Timeline())->list($page, $cantItems);
            $TotalTimeline =(New Timeline())->list(1, 999999999999999999);
        };

        $total = count($TotalTimeline);
        if($total > $cantItems){
            $division = $total / $cantItems;
            $paginator = intval($division);
            if($paginator < $division){
                $paginator = $paginator +1;
            };
        };


        $objBanners = (New Banners())->list(6, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        return response()->json([
            'timeline' => $objTimeline,
            'tot' => $total,
            'paginator' => $paginator,
            'banner' => $objBanners,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);
    }


    public function timelineFind($id){
        //Log::info("timeline :: ->");
        $obj = (New Timeline())->serch($id);
        //Log::info($id);
       // Log::info($obj);

        return response()->json([
            'timeline' => $obj
        ]);
    }





  public function timelineSave(Request $request){
    $status = 200;$mensaje="Timeline has been saved successfully";

    Log::info("##ingreso a timelineSave :::");


        $obj=[];
        try{
            $id = $request->input("id");
            if($id){
                $obj = Timeline::findOrFail($id);
            }else{
                $obj = new Timeline;
                $obj->active = 1;
                $obj->created_at = date("Y-m-d H:i:s");
            };
            $obj->tittle = $request->input("title");
            $obj->link = $request->input("link");
            $obj->startDate = $request->input("startDate");
            $obj->endDate = $request->input("endDate");
            $obj->updated_at = date("Y-m-d H:i:s");
            $obj -> save();
        } catch ( \Exception $e ) {
            Log::info($e);
            $status = 400;$mensaje="Error";
        };


        return response()->json([
            'status' => $status,
            'message' => $mensaje,
            'timeline' => $obj
        ]);
  }




}
