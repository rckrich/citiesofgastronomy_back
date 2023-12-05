<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cities;
use App\Models\Banners;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function home(){

        $objCities =(New Cities())->list(1, 20);

        $objAbout = (New Banners())->list(2, 0);
        Log::info("BAnner controller");
        Log::info($objAbout);
        if(   count($objAbout)>0   ){$bannerAbount = $objAbout[0]["banner"];
        }else{$bannerAbount = '';        };

        $objAbout = (New Banners())->list(4, 0);
        if($objAbout){$bannerNumberAndStats = $objAbout[0]["banner"];
        }else{$bannerNumberAndStats = '';        };

        return response()->json([
            'bannerAbout' => $bannerAbount,
            'bannerNumberAndStats' => $bannerNumberAndStats,
            'recentInitiatives' => [],
            'news' => [],
            'openCalls' => [],
            'coordinator' => '',
            'contactMail' => '',
            'cities' => $objCities
        ]);
    }
}
