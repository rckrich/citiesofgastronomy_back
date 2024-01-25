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


}
