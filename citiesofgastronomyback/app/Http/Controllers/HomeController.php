<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cities;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function home(){

        $objCities =(New Cities())->list();

        return response()->json([
            'cities' => $objCities,
            'bannerAbout' => '',
            'bannerNumberAndStats' => '',
            'recentInitiatives' => [],
            'news' => [],
            'openCalls' => [],
            'coordinator' => '',
            'contactMail' => ''
        ]);
    }
}
