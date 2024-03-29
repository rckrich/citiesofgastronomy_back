<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;
use App\Models\Info;
use App\Models\Initiatives;
use App\Models\SocialNetwork;
use DB;

class CalendarController extends Controller
{


    public function index(Request $request)
    {
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;
        $objCalendar = [];
        $total=0;

        //$objCalendar =(New Timeline())->searchList($request->search, $page,$cantItems);
        $objCalendar = (New Initiatives()) -> select(
            "id", "name",
            DB::raw(' startDate AS date'),
            DB::raw(' DATE_FORMAT(startDate, "%d %M %Y") AS startDate'),
            DB::raw(' DATE_FORMAT(endDate, "%d %M %Y") AS endDate'),
            DB::raw(' DATE_FORMAT(startDate, "%c") AS month')
            )
        -> where('active', '1')
        -> where( "endDate", '>=', date("Y-m-d"))
        -> orderBy('date', 'ASC')
        -> get() -> toArray()

        ;


        $objBanners = (New Banners())->list(10, 0);

        $infoArray = (New Info())->type();
        for($i=0; $i < count($infoArray); $i++){
            $infoValue='';
            $objInfoCoordinator = (New Info())->list($infoArray[$i]["key"]);
            if($objInfoCoordinator){ $infoValue = $objInfoCoordinator["description"]; };
            $infoArray[$i]["value"] = $infoValue;
        }

        $SocialNetworkType = (New SocialNetwork())->list(5, 0);

        return response()->json([
            'calendar' => $objCalendar,
            'tot' => $total,
            'paginator' => $paginator,
            'banner' => $objBanners,
            'SocialNetworkType' => $SocialNetworkType,
            'info' => $infoArray
        ]);
    }


}
