<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;
use App\Models\Info;
use App\Models\SocialNetwork;

class ContactsController extends Controller
{
    //


    public function index(Request $request)
    {
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;
        $objContacts = [];
        $total=0;

        //$objContacts =(New Timeline())->searchList($request->search, $page,$cantItems);

        $objBanners = (New Banners())->list(11, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        return response()->json([
            'contacts' => $objContacts,
            'tot' => $total,
            'paginator' => $paginator,
            'banner' => $objBanners,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);
    }


}
