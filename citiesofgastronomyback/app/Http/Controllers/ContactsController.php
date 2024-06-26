<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Banners;
use App\Models\Info;
use App\Models\SocialNetwork;
use App\Models\Contacts;
use App\Models\Cities;
use Illuminate\Support\Facades\Log;
use App\Models\continent;
use App\Models\SocialNetworkType;

class ContactsController extends Controller
{


    public function index(Request $request)
    {
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;
        $objContacts = [];
        $total=0;


        $objBanners = (New Banners())->list(11, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);


        //SOLO LAS CIUDADES QUE AL MENOS TENGAN UN CONTACTO
        $socialType = (New Contacts())->socialType();

            $objContacts = Cities::withWhereHas('contacts', function ($query) {
                $query->where('active', '1');
            })
            ->with('contacts.socialNetwork')
            ->with('contacts.socialNetwork.socialNetworkType')
            ->get();


        return response()->json([
            'contacts' => $objContacts,
            'tot' => $total,
            'paginator' => $paginator,
            'banner' => $objBanners,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);//*/
    }





    public function list(Request $request)
    {
        Log::info("#ADMIN Contact List");
        if(!$request->cantItems){
            $cantItems = 20;
        }else{
            $cantItems = $request->cantItems;
        };
        //Log::info("##Cant Contacts ::".$cantItems);

        $page = $request->page;
        $search = $request->search;
        if(!$page){ $page=1; };

        Log::info("#PAGE: ".$page);
        Log::info("#SEARCH: ".$search);

        $offset = ($page-1) * $cantItems;

        $objContacts = Contacts::with(['socialNetwork' => function ( $query ) use ($cantItems) {
            $query  -> where('idSection', '11');
        }])
        -> with('socialNetwork.socialNetworkType')
        -> where('active', '1')
        -> where( "name", 'LIKE', "%{$search}%")
        -> limit($cantItems)
        -> offset($offset)
        -> orderBy("name", "ASC")
        -> get();

        $totalcontact = (New Contacts())->list($search, 1, 99999999);
        //$total = (New Contacts())->where('active', '1');
        $paginator = 1;
        $total = count($totalcontact);
        if($total > $cantItems){
            $division = $total / $cantItems;
            $paginator = intval($division);
            if($paginator < $division){
                $paginator = $paginator + 1;
            };
        };

        return response()->json([
            'tot' => $total,
            'paginator' => $paginator,
            'contact' => $objContacts
        ]);
    }



    public function contactSave(Request $request){

        Log::info("::llego a contacto");
        $obj = (New Contacts())->contactSave($request);
        //Log::info($obj);

        $idOwner = $obj->id;
        //Log::info($idOwner);

        $objLink = (New SocialNetwork()) -> storeLink( $request , $idOwner  );

        return response()->json([
            'contact' => $obj,
            'status' => 200
        ]);
    }





    public function contactFind(Request $request){
        try{
            $id = $request->input("id");
            if($id){
                    $obj = Contacts::findOrFail($id);
                    $objsocialContact = (New SocialNetwork())->list(11, $id);;
                    Log::info("-->redes");
                    Log::info($objsocialContact);
            }else{
                    $obj = [];
                    $objsocialContact = [];
            };
            $objContinent = (New continent())->list();

            $socialType = (New Contacts())->socialType();
            $objsocial = SocialNetworkType::whereIn('id', $socialType)->get();
            //$objsocial = (New SocialNetworkType())->list();
            $objCities =(New Cities())->searchList('', 1, 999999999999999999);

            return response()->json([
                'contact' => $obj,
                'contactSocialNetwork' => $objsocialContact,
                'continents' => $objContinent,
                'cities' => $objCities,
                'social' => $objsocial
            ]);

        } catch ( \Exception $e ) {
            Log::info($e);
            return response()->json([
                'status' => 400,
                'messaje' => 'No contacts find',
                'contact' => [],
                'contactSocialNetwork' => [],
                'continents' => [],
                'cities' => [],
                'social' => []
            ]);
        }
    }




    public function delete($id){
        Log::info("CONTACT Delete ::");
        $status = 400;$mess = 'ok';
        try{
            $obj = Contacts::find($id);
            //$obj->active = 0;
            //$obj->updated_at = date("Y-m-d H:i:s");
            //$obj -> save();

            if($obj != NULL){$obj->delete(); };
        } catch ( \Exception $e ) {
            Log::info($e);
            $status = 400;$mess="Error";
        };
        return response()->json([
            'status' => $status,
            'message' => $mess
        ]);
    }


    public function generalDatta(){


        $objContinent = (New continent())->list();

        $socialType = (New Contacts())->socialType();
        $objsocial = SocialNetworkType::whereIn('id', $socialType)->get();
        //$objsocial = (New SocialNetworkType())->list();
        $objCities =(New Cities())->searchList('', 1, 999999999999999999);

        return response()->json([
            'continents' => $objContinent,
            'cities' => $objCities,
            'social' => $objsocial
        ]);
    }


}
