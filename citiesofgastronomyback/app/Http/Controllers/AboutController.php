<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timeline;
use App\Models\Banners;
use App\Models\Info;
use App\Models\FAQ;
use App\Models\SocialNetwork;
use Illuminate\Support\Facades\Log;

class AboutController extends Controller
{
    public function list(Request $request){

        ///////LISTADO DEL ADMINISTRADOR

        Log::info("#list1");
        $cantItems = 20;
        $paginator = 1;
        $total= 0;
        $page = $request->page;
        $pageFaq = $request->pageFaq;
        $paginatorFAQ = 1;

        $objTimeline =(New Timeline())->list($request->search, $page, $cantItems);
        $TotalTimeline =(New Timeline())->list($request->search, 1, 999999999999999999);
        $total = count($TotalTimeline);

        //$total = count($objTOT);
        if($total > $cantItems){
            $division = $total / $cantItems;
            $paginator = intval($division);
            if($paginator < $division){
                $paginator = $paginator +1;
            };
        };
        ////////////////////////////
        $objFAQ = (New FAQ())->list($request->searchFaq, $pageFaq, $cantItems);
        $TodosFAQ =(New FAQ())->list($request->searchFaq, 1, 999999999999999999);
        $cantTotalFAQ = count($TodosFAQ);

        if($cantTotalFAQ > $cantItems){
            $division = $cantTotalFAQ / $cantItems;
            $paginatorFAQ = intval($division);
            if($paginatorFAQ < $division){
                $paginatorFAQ = $paginatorFAQ +1;
            };
        };
        //////////////////////////



        $objBanners = (New Banners())->list(6, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        $objAbout = (New Banners())->list(2, 0);
        //Log::info("BAnner controller");
        if(   count($objAbout)>0   ){$bannerAbount = $objAbout[0]["banner"];
        }else{$bannerAbount = '';        };

        return response()->json([
            'timeline' => $objTimeline,
            'faq' => $objFAQ,
            'pageFaq' => $pageFaq,
            'totFAQ' => $cantTotalFAQ,
            'paginatorFAQ' => $paginatorFAQ,
            'tot' => $total,
            'paginator' => $paginator,
            'banner' => $objBanners,
            'bannerAbout' => $bannerAbount,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);
    }

    public function listTimeline(Request $request){
        $cantItems = 5;
        $paginator = 1;
        $page = $request->page;
        if(!$page){$page=1;};


        if($request->search){
            $objTimeline =(New Timeline())->searchList($request->search, $page,$cantItems);
            $TotalTimeline =(New Timeline())->searchList($request->search, 1, 999999999999999999);
        }else{
            $objTimeline =(New Timeline())->list($request->search, $page, $cantItems);
            $TotalTimeline =(New Timeline())->list($request->search, 1, 999999999999999999);
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
            'timeline' => $obj,
            'status' => '200'
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





  public function faqFind($id){
        //Log::info("timeline :: ->");
        $obj = (New FAQ())->serch($id);
        //Log::info($id);
    // Log::info($obj);

        return response()->json([
            'faq' => $obj,
            'status' => 200
        ]);
    }




    public function faqSave(Request $request){
      $status = 200;$mensaje="FAQ has been saved successfully";

      Log::info("##ingreso a faqSave :::");


          $obj=[];
          try{
              $id = $request->input("id");
              if($id){
                  $obj = FAQ::findOrFail($id);
              }else{
                  $obj = new FAQ;
                  //$obj->active = 1;
                  $obj->created_at = date("Y-m-d H:i:s");
              };
              $obj->faq = $request->input("faq");
              $obj->answer = $request->input("answer");
              $obj->updated_at = date("Y-m-d H:i:s");
              $obj -> save();
          } catch ( \Exception $e ) {
              Log::info($e);
              $status = 400;$mensaje="Error";
          };


          return response()->json([
              'status' => $status,
              'message' => $mensaje,
              'faq' => $obj
          ]);
    }






    public function aboutDel(Request $request){
        $status = 200;$mensaje="Item has been saved successfully";

        Log::info("##ingreso a delete BOUT :::");
        $type = $request->input("type");

        $obj=[];
        try{
                $id = $request->input("id");
                if($type == 'faq'){
                    $obj = FAQ::findOrFail($id);
                    $obj -> delete();
                }else{
                    $obj = Timeline::findOrFail($id);
                    $obj->active = 0;
                    $obj->updated_at = date("Y-m-d H:i:s");
                    $obj -> save();
                };
        } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Error";
        };


            return response()->json([
                'status' => $status,
                'message' => $mensaje
            ]);
      }

}
