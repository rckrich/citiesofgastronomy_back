<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;
use App\Models\Cities;
use App\Models\Info;
use App\Models\SocialNetwork;
use App\Models\TypeOfActivity;
use App\Models\Topics;
use App\Models\SDG;
use App\Models\ConnectionsToOther;
use App\Models\Initiatives;
use Illuminate\Support\Facades\Log;

class InitiativesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Log::info("#ini controller");
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;
        $objInitiatives = [];
        $total = 0;

        $objInitiatives =(New Initiatives())->list($request->search, $page,$cantItems);

        $objBanners = (New Banners())->list(7, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        $objType = (New TypeOfActivity())->list();
        $objTopic = (New Topics())->list();
        $objsdg = (New sdg())->list();
        $objConnectionsToOther = (New ConnectionsToOther())->list();

        return response()->json([
            'initiatives' => $objInitiatives,
            'tot' => $total,
            'paginator' => $paginator,
            'banner' => $objBanners,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray,
            'typeOfActivity' => $objType,
            'Topics' => $objTopic,
            'sdg' => $objsdg,
            'ConnectionsToOther' => $objConnectionsToOther
        ]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objCities =(New Cities())->searchList('', 1, 999999999999999999);
        $objType = (New TypeOfActivity())->list();
        $objTopic = (New Topics())->list();
        $objsdg = (New sdg())->list();
        $objConnectionsToOther = (New ConnectionsToOther())->list();

        return response()->json([
            'citiesFilter' => $objCities,
            'typeOfActivityFilter' => $objType,
            'TopicsFilter' => $objTopic,
            'sdgFilter' => $objsdg,
            'ConnectionsToOtherFilter' => $objConnectionsToOther
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $objCities =(New Cities())->searchList('', 1, 999999999999999999);
        $objType = (New TypeOfActivity())->list();
        $objTopic = (New Topics())->list();
        $objsdg = (New sdg())->list();
        $objConnectionsToOther = (New ConnectionsToOther())->list();

        return response()->json([
            'citiesFilter' => $objCities,
            'typeOfActivityFilter' => $objType,
            'TopicsFilter' => $objTopic,
            'sdgFilter' => $objsdg,
            'ConnectionsToOtherFilter' => $objConnectionsToOther
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function typeOfActivity_store(Request $request)
    {
        $status = 200; $message = 'The record has been saved successfully';
        $name = $request->input("name");
        $coincidencia = '';
        $obj = (New TypeOfActivity())->findName($name);
        Log::info($name);
        if( count($obj) > 0){
            $cant1 = strlen($name);
            $cant2 = strlen($obj[0]["name"]);
            if($cant1 == $cant2){
                $coincidencia = 'si';

                $status = 400; $message = 'The name of this filter already exists in the database';
            };
        };
        //TypeOfActivity
        if($coincidencia != 'si'){
            Log::info("::SAVE a typeOfActivity");
            $obj = (New TypeOfActivity())->saveType($request);
        };
        return response()->json([
            'TypeOfActivity' => $obj,
            'status' => $status,
            'message' => $message
        ]);
    }

    public function topic_store(Request $request)
    {
        $status = 200; $message = 'The record has been saved successfully';

        $coincidencia = '';
        $name = $request->input("name");
        $obj = (New Topics())->findName($name);
        Log::info($name);
        if( count($obj) > 0){
            $cant1 = strlen($name);
            $cant2 = strlen($obj[0]["name"]);
            if($cant1 == $cant2){
                $coincidencia = 'si';

                $status = 400; $message = 'The name of this filter already exists in the database';
            };
        };

        if($coincidencia != 'si'){
            Log::info("::SAVE a topic_store");
            $obj = (New Topics())->saveTopic($request);
        };


        return response()->json([
            'Topics' => $obj,
            'status' => $status,
            'message' => $message
        ]);
    }

    public function sdg_store(Request $request)
    {
        $status = 200; $message = 'The record has been saved successfully';

        $coincidencia = '';
        $name = $request->input("name");
        $obj = (New SDG())->findName($name);
        Log::info($name);
        if( count($obj) > 0){
            $cant1 = strlen($name);
            $cant2 = strlen($obj[0]["name"]);
            if($cant1 == $cant2){
                $coincidencia = 'si';

                $status = 400; $message = 'The name of this filter already exists in the database';
            };
        };

        if($coincidencia != 'si'){
            Log::info("::SAVE a SDG_store");
            $obj = (New SDG())->saveSDG($request);
        };

        return response()->json([
            'SDG' => $obj,
            'status' => $status,
            'message' => $message
        ]);
    }

    public function connectionsToOther_store(Request $request)
    {
        $status = 200; $message = 'The record has been saved successfully';

        $coincidencia = '';
        $name = $request->input("name");
        $obj = (New ConnectionsToOther())->findName($name);
        Log::info($name);
        if( count($obj) > 0){
            $cant1 = strlen($name);
            $cant2 = strlen($obj[0]["name"]);
            if($cant1 == $cant2){
                $coincidencia = 'si';

                $status = 400; $message = 'The name of this filter already exists in the database';
            };
        };

        if($coincidencia != 'si'){
            Log::info("::SAVE a CONNECTION_store");
            $obj = (New ConnectionsToOther())->saveConnection($request);
        };

        return response()->json([
            'ConnectionsToOther' => $obj,
            'status' => $status,
            'message' => $message
        ]);
    }
}
