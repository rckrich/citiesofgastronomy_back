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
use App\Models\Filter;
use App\Models\ConnectionsToOther;
use App\Models\Initiatives;
use App\Models\Images;
use App\Models\Links;
use App\Models\Files;
use App\Models\continent;
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
        $filterType = $request->filterType;
        $filterTopic = $request->filterTopic;
        $filterSDG = $request->filterSDG;
        $filterConnections = $request->filterConnections;
        $objInitiatives = [];
        $total = 0;

        $objInitiatives =(New Initiatives())->list($request->search, $page, $cantItems, 'name', $filterType, $filterTopic, $filterSDG, $filterConnections);
        $objTotalInitiatives =(New Initiatives())->list($request->search, 1, 9999999999, 'name', $filterType, $filterTopic, $filterSDG, $filterConnections);

        $total = count($objTotalInitiatives);
        if($total > $cantItems){
            $division = $total / $cantItems;
            $paginator = intval($division);
            if($paginator < $division){
                $paginator = $paginator +1;
            };
        };
        Log::info("Page:".$page.'  - Paginator: '.$paginator.' - TOTAL: '.$total);


        $objBanners = (New Banners())->list(7, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        $objType = (New TypeOfActivity())->list($request->searchTypeOfActivity);
        $objTopic = (New Topics())->list($request->searchTopics);
        $objsdg = (New sdg())->list($request->searchSDG);
        $objConnectionsToOther = (New ConnectionsToOther())->list($request->searchConnectionsToOther);

        $objCities =(New Cities())->searchList('', 1, 999999999999999999);


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
            'citiesFilter' => $objCities,
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

        $objContinent = (New continent())->list();

        return response()->json([
            'citiesFilter' => $objCities,
            'typeOfActivityFilter' => $objType,
            'TopicsFilter' => $objTopic,
            'sdgFilter' => $objsdg,
            'ConnectionsToOtherFilter' => $objConnectionsToOther,
            'Continent' => $objContinent
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $status = 200;
        $id = $request->input("id");
        $mensaje="The initiative was successfully created";
        if($id){
            $mensaje="The initiative was successfully edited";
        };

        $photo = '';
            if($request->file("photo")){
                try{
                    $request->validate ([
                        'photo' => 'image|max:50000'
                    ]);
                    //$photo =  $request->file("photo")->store('public/images/Initiatives');
                    //$photo = str_replace('public/', 'storage/', $photo);

                    $photo = (New Images())->storeResize($request->file("photo"), '1158', '845', 'initiatives');
                } catch ( \Exception $e ) {

                }
            };

            $obj=[];
            try{
                $request->validate ([
                    'name' => 'required|string'
                ]);

                if($id){
                    $obj = Initiatives::find($id);
                }else{
                    $obj = New Initiatives;
                }
                $obj->continent = $request->input("idContinent");
                $obj->name = $request->input("name");
                $obj->startDate = $request->input("startDate");
                $obj->endDate = $request->input("endDate");
                $obj->description = $request->input("description");
                $obj->active = 1;
                if($photo){
                    $obj->photo = $photo;
                };
                $obj->created_at = date("Y-m-d H:i:s");
                $obj->updated_at = date("Y-m-d H:i:s");
                $obj -> save();
                //Log::info($obj);
                $id = $obj->id;
            } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Incorrect name format";
            };

            if($id != ''){
                //////////////////////////////////////////////////// FILTRO TYPE OF ACTIVITY
                $objType = (New TypeOfActivity())->list();

                foreach($objType AS $item){
                    $objFilter = (New Filter()) -> select('id')
                            ->where('type', 'TypeOfActivity')
                            ->where('idOwner', $id)
                            ->where('filter', $item["id"])
                            ->where('idOwnerSection', '7')
                            ->first();

                    $filterName = 'typeOfActivityFilter'.$item["id"];
                    $value = $request->input($filterName);
                    //Log::info($filterName.': '.$value);
                    /////
                    if($value && !$objFilter){// create filter
                        //Log::info("-> CREATE");
                        $obj = New Filter;
                        $obj->type = 'TypeOfActivity';
                        $obj->idOwnerSection = '7';
                        $obj->idOwner = $id;
                        $obj->filter = $item["id"];
                        $obj->save();
                    }elseif(!$value && $objFilter){//delete filter
                        //Log::info("-> DELETE");
                        //Log::info($objFilter);
                        $objFilterdel = Filter::find($objFilter["id"]);
                        $objFilterdel->delete();
                    };
                }

                ////////////////////////////////////////////////////

                $objTopics = (New Topics())->list();

                foreach($objTopics AS $item){
                    $objFilter = (New Filter()) -> select('id')
                            ->where('type', 'Topics')
                            ->where('idOwner', $id)
                            ->where('idOwnerSection', '7')
                            ->where('filter', $item["id"])
                            ->first();

                    $filterName = 'topicsFilter'.$item["id"];
                    $value = $request->input($filterName);
                    //Log::info($filterName.': '.$value);
                    /////
                    if($value && !$objFilter){// create filter
                        //Log::info("-> CREATE");
                        $obj = New Filter;
                        $obj->type = 'Topics';
                        $obj->idOwnerSection = '7';
                        $obj->idOwner = $id;
                        $obj->filter = $item["id"];
                        $obj->save();
                    }elseif(!$value && $objFilter){//delete filter
                        //Log::info("-> DELETE");
                        //Log::info($objFilter);
                        $objFilterdel = Filter::find($objFilter["id"]);
                        $objFilterdel->delete();
                    };
                }
                ////////////////////////////////////////////////////
                $objsdgs = (New sdg())->list();

                foreach($objsdgs AS $item){
                    $objFilter = (New Filter()) -> select('id')
                            ->where('type', 'SDG')
                            ->where('idOwner', $id)
                            ->where('idOwnerSection', '7')
                            ->where('filter', $item["id"])
                            ->first();

                    $filterName = 'sdgFilter'.$item["id"];
                    $value = $request->input($filterName);
                    //Log::info($filterName.': '.$value);
                    /////
                    if($value && !$objFilter){// create filter
                        //Log::info("-> CREATE");
                        $obj = New Filter;
                        $obj->type = 'SDG';
                        $obj->idOwnerSection = '7';
                        $obj->idOwner = $id;
                        $obj->filter = $item["id"];
                        $obj->save();
                    }elseif(!$value && $objFilter){//delete filter
                        //Log::info("-> DELETE");
                        //Log::info($objFilter);
                        $objFilterdel = Filter::find($objFilter["id"]);
                        $objFilterdel->delete();
                    };
                }
                ////////////////////////////////////////////////////
                $objConnectionsToOther = (New ConnectionsToOther())->list();

                foreach($objConnectionsToOther AS $item){
                    $objFilter = (New Filter()) -> select('id')
                            ->where('type', 'ConnectionsToOther')
                            ->where('idOwner', $id)
                            ->where('idOwnerSection', '7')
                            ->where('filter', $item["id"])
                            ->first();

                    $filterName = 'connectionsToOtherFilter'.$item["id"];
                    $value = $request->input($filterName);
                   // Log::info($filterName.': '.$value);
                    /////
                    if($value && !$objFilter){// create filter
                        //Log::info("-> CREATE");
                        $obj = New Filter;
                        $obj->type = 'ConnectionsToOther';
                        $obj->idOwnerSection = '7';
                        $obj->idOwner = $id;
                        $obj->filter = $item["id"];
                        $obj->save();
                    }elseif(!$value && $objFilter){//delete filter
                        //Log::info("-> DELETE");
                        //Log::info($objFilter);
                        $objFilterdel = Filter::find($objFilter["id"]);
                        $objFilterdel->delete();
                    };
                }

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
                                $objGallery =(New Images())->storeIMG($image, $id, 7);
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

            /////////////////////////////   LINKS
            $cant_links = $request->input("cant_links");
            Log::info("--->CANT LINKS: ".$cant_links);

            for($i = 1; $i < $cant_links+1; $i++){
                $idg = 'link'.$i;
                $link = $request->input($idg);
                $idg = 'titleLink'.$i;
                $titleLink = $request->input($idg);
                $idg = 'idLink'.$i;
                $idLink = $request->input($idg);
                $idg = 'deleteLink'.$i;
                $deleteLink = $request->input($idg);
                if(!$idLink){
                    if($link){
                        try{
                            if(!$deleteLink){
                                $objGallery =(New Links())->storeLINK($link, $titleLink, $id, 7);
                            }else{Log::info("#3.b");};
                        } catch ( \Exception $e ) {
                        }
                    };
                }else{
                    $objLink = Links::find($idLink);
                    if($deleteLink){
                        $objLink->active = 2;
                    }else{
                        $objLink->title = $titleLink;
                        $objLink->image = $link;
                    };
                    $objLink->updated_at = date("Y-m-d H:i:s");
                    $objLink -> save();
                };

            }
            //////////////////////////////////////////////////////////


            /////////////////////////////   FILES
            $cant_files = $request->input("cant_files");
            //Log::info("-->CANT FILES -->".$cant_files);


            for($i = 1; $i < $cant_files+1; $i++){
                //Log::info("#:1");
                $idg = 'file'.$i;
                $file = $request->file($idg);
                $idg = 'idFile'.$i;
                $idFile = $request->input($idg);
                $idg = 'title'.$i;
                $title = $request->input($idg);
                $idg = 'deleteFile'.$i;
                $deleteFile = $request->input($idg);
                //Log::info("-->ID:: ".$idFile);
                if($idFile){
                    Log::info("Modifica::");
                    $objFiles = Files::find($idFile);
                    if($deleteFile){

                    //Log::info("->HAY DEL");
                        $objFiles->active = 2;
                    }else{
                        //Log::info("------------->HABILITA");
                        $objFiles->title = $title;
                        $objFiles->active = 1;
                    };
                    $objFiles->idOwner = $id;
                    $objFiles->updated_at = date("Y-m-d H:i:s");
                    $objFiles -> save();
                    //Log::info($objFiles);
                }else{
                    //Log::info("#:nop");
                    if($deleteFile){

                    }else{
                        if($file){
                            $objFILE = (New Files())->storeFILE($file, $id, 7, $title, 1);
                        };
                    };
                };

            }
            //////////////////////////////////////////////////////////
                $objCities =(New Cities())->searchList('', 1, 999999999999999999);

                foreach($objCities AS $item){
                    $objFilter = (New Filter()) -> select('id')
                            ->where('type', 'Cities')
                            ->where('idOwner', $id)
                            ->where('idOwnerSection', '7')
                            ->where('filter', $item["id"])
                            ->first();

                    $filterName = 'citiesFilter'.$item["id"];
                    $value = $request->input($filterName);
                    //Log::info($filterName.': '.$value);
                    /////
                    if($value && !$objFilter){// create filter
                        //Log::info("-> CREATE");
                        $obj = New Filter;
                        $obj->type = 'Cities';
                        $obj->idOwnerSection = '7';
                        $obj->idOwner = $id;
                        $obj->filter = $item["id"];
                        $obj->save();
                    }elseif(!$value && $objFilter){//delete filter
                        //Log::info("-> DELETE");
                        //Log::info($objFilter);
                        $objFilterdel = Filter::find($objFilter["id"]);
                        $objFilterdel->delete();
                    };
                }
                //////////////////////////////////////////////////////////
            };

            return response()->json([
                'status' =>  $status,
                'message' =>  $mensaje,
                'datta' =>  $obj
            ]);
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
        if($id){
                $objIniciative = Initiatives::select(
                    "initiatives.id",
                    "initiatives.continent",
                    "initiatives.name",
                    "initiatives.startDate",
                    "initiatives.endDate",
                    "initiatives.photo",
                    "initiatives.description",
                    "continent.name AS continentName")
                -> where('initiatives.id', $id)
                -> join( "continent", "continent.id", '=', "initiatives.continent" )
                -> with('sdgFilter')
                -> with('sdgFilter.sdgDatta')
                -> with('typeFilter')
                -> with('typeFilter.typeDatta')

                -> with('topicsFilter')
                -> with('topicsFilter.topicsDatta')
                -> with('conectionsFilter')
                -> with('conectionsFilter.connectionsDatta')
                -> with('citiesFilter')
                -> with('citiesFilter.citiesDatta')

                -> with('images')
                -> with('links')
                -> with('pdf')
                -> first()
                ;
                //Log::info($objIniciative);

                $objgallery = (New Images())->list(7, $id);
                $objLinks = (New Links())->list(7, $id);
                $objFiles = (New Files())->list(7, $id);
                //$objsocialContact = (New SocialNetwork())->list(11, $id);;
        }else{
                $obj = [];
                $objIniciative = [];
        };

        $objCities =(New Cities())->searchList('', 1, 999999999999999999);
        $objType = (New TypeOfActivity())->list();
        $objTopic = (New Topics())->list();
        $objsdg = (New sdg())->list();
        $objConnectionsToOther = (New ConnectionsToOther())->list();

        $objContinent = (New continent())->list();

        return response()->json([
            'iniciative' => $objIniciative,
            'citiesFilter' => $objCities,
            'typeOfActivityFilter' => $objType,
            'TopicsFilter' => $objTopic,
            'sdgFilter' => $objsdg,
            'ConnectionsToOtherFilter' => $objConnectionsToOther,
            'Continent' => $objContinent
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
        $status = 200; $message = 'The Initiative was successfully deleted';
        $tieneIniciativas = '';$objFilter = [];

        $idFilter = $id;
        $obj = (New Initiatives())->findInitiative($idFilter, 'ConnectionsToOther');

        Log::info( "::ID : " );
        Log::info($id );

        if( !$id ){
                $status = 400; $message = 'This record cannot be deleted.';
        }else{
            $objFilter = Filter::where('idOwnerSection', '7')->where('idOwner', $id)->delete ();
            $objImage = Images::where('idSection', '7')->where('idOwner', $id)->delete ();
            $objLinks = Links::where('idSection', '7')->where('idOwner', $id)->delete ();
            $objFiles = Files::where('idSection', '7')->where('idOwner', $id)->delete ();

            $obj = Initiatives::find($id);
            if($obj != NULL){$obj->delete(); };
        };

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }










    public function typeOfActivity_store(Request $request)
    {
        $status = 200; $message = 'The record has been saved successfully';
        $name = $request->input("name");
        $coincidencia = '';

        $id = $request->input("id");
        $obj = (New TypeOfActivity())->findName($name, $id);
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

    public function typeOfActivity_delete($id){
        $status = 200; $message = 'The record has been delete successfully';
        $tieneIniciativas = '';$objFilter = [];

        $idFilter = $id;
        $obj = (New Initiatives())->findInitiative($idFilter, 'TypeOfActivity');

        if( count($obj) > 0){
                $status = 400; $message = 'This filter cannot be deleted because it is being used by an initiative. Please reassign the filter and try again';
        }else{
            $objFilter = TypeOfActivity::find($idFilter);
            if($objFilter != NULL){
                $objFilter->delete();
            };
        };

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }






    public function topic_store(Request $request)
    {
        $status = 200; $message = 'The record has been saved successfully';

        $coincidencia = '';
        $name = $request->input("name");
        $id = $request->input("id");
        $obj = (New Topics())->findName($name, $id);
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




    public function topic_delete($id){
        $status = 200; $message = 'The record has been delete successfully';
        $tieneIniciativas = '';$objFilter = [];

        $idFilter = $id;
        $obj = (New Initiatives())->findInitiative($idFilter, 'Topics');

        if( count($obj) > 0){
                $status = 400; $message = 'This filter cannot be deleted because it is being used by an initiative. Please reassign the filter and try again';
        }else{
            $objFilter = Topics::find($idFilter);
            if($objFilter != NULL){
                $objFilter->delete();
            };
        };

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function sdg_store(Request $request)
    {
        $status = 200; $message = 'The record has been saved successfully';

        $coincidencia = '';
        $name = $request->input("name");

        $id = $request->input("id");
        $obj = (New SDG())->findName($name, $id);
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




    public function sdg_delete($id){
        $status = 200; $message = 'The record has been delete successfully';
        $tieneIniciativas = '';$objFilter = [];

        $idFilter = $id;
        $obj = (New Initiatives())->findInitiative($idFilter, 'SDG');

        if( count($obj) > 0){
                $status = 400; $message = 'This filter cannot be deleted because it is being used by an initiative. Please reassign the filter and try again';
        }else{
            $objFilter = SDG::find($idFilter);
            if($objFilter != NULL){
                $objFilter->delete();
            };
        };

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }


    public function connectionsToOther_store(Request $request)
    {
        $status = 200; $message = 'The record has been saved successfully';

        $coincidencia = '';
        $name = $request->input("name");
        $id = $request->input("id");
        $obj = (New ConnectionsToOther())->findName($name, $id);
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






    public function connectionsToOther_delete($id){
        $status = 200; $message = 'The record has been delete successfully';
        $tieneIniciativas = '';$objFilter = [];

        $idFilter = $id;
        $obj = (New Initiatives())->findInitiative($idFilter, 'ConnectionsToOther');

        if( count($obj) > 0){
                $status = 400; $message = 'This filter cannot be deleted because it is being used by an initiative. Please reassign the filter and try again';
        }else{
            $objFilter = ConnectionsToOther::find($idFilter);
            if($objFilter != NULL){
                $objFilter->delete();
            };
        };

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }



}
