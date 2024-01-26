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

        //TODAS LAS CIUDADES
        /*$objContacts = Cities::with(['contacts' => function ( $query) {
                $query->where('active', '1');
            }])->get();
            //*/

        //SOLO LAS CIUDADES QUE AL MENOS TENGAN UN CONTACTO
            $objContacts = Cities::withWhereHas('contacts', function ($query) {
                $query->where('active', '1');
            })
            ->with(['contacts.socialNetwork' => function ( $query) {
                $query->where('idSection', '11');
            }])
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
        if(!$request->cantItems){
            $cantItems = 20;
        }else{
            $cantItems = $request->cantItems;
        };
        Log::info("##Cant Contacts ::".$cantItems);

        $page = $request->page;
        $search = $request->search;
        if(!$page){ $page=1; };

        $offset = ($page-1) * $cantItems;

        $objContacts = Contacts::with(['socialNetwork' => function ( $query ) use ($cantItems) {
            $query  -> where('idSection', '11')
                    -> limit($cantItems)
                    -> offset($offset);
        }])
        ->with('socialNetwork.socialNetworkType')
        ->get();

        $totalcontact = (New Contacts())->list($search, $page, 99999999);
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


        $obj = (New Contacts())->saveContact($request);

        return response()->json([
            'contact' => $obj
        ]);
    }




    public function generalDatta(){


        $objContinent = (New continent())->list();
        $objsocial = (New SocialNetworkType())->list();
        $objCities =(New Cities())->searchList('', 1, 999999999999999999);

        return response()->json([
            'continents' => $objContinent,
            'cities' => $objCities,
            'social' => $objsocial
        ]);
    }


}
