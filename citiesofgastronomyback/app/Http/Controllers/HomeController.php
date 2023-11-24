<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cities;
use App\Models\Banners;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function home(){

        $objCities =(New Cities())->list();

        $objAbout = (New Banners())->list(2);
        if($objAbout){$bannerAbount = $objAbout[0]["banner"];
        }else{$bannerAbount = '';        };

        $objAbout = (New Banners())->list(4);
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
