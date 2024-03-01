<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Initiatives;
use App\Models\Tours;
use App\Models\Recipes;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function generalSearch(Request $request){

        $search = $request->input("search");

        $Tours = (New Tours()) -> select(
            "id",
            "name",
            "photo",
            "description",
            DB::raw(' DATE_FORMAT(created_at, "%Y-%m-%d") AS date'),
            DB::raw('"Tours" AS type')
            )
            //-> where('active', '1')
            -> where( "name", 'LIKE', "%{$search}%")
            ;
        $Recipes = (New Recipes()) -> select(
            "id",
            "name",
            "photo",
            "description",
            DB::raw(' DATE_FORMAT(created_at, "%Y-%m-%d") AS date'),
            DB::raw('"Recipes" AS type')
            )
            //-> where('active', '1')
            -> where( "name", 'LIKE', "%{$search}%")
            ;

        $searchResult = (New Initiatives()) -> select(
            "id",
            "name",
            "photo",
            "description",
            DB::raw('startDate AS date'),
            DB::raw('"Initiatives" AS type')
            )
            -> where('active', '1')
            -> where( "name", 'LIKE', "%{$search}%")
            ->union($Tours)
            ->union($Recipes)
            -> orderBy('date', 'ASC')
            -> orderBy('name', 'ASC')
            -> get()
            -> toArray()
            ;
        //Log::info("-->aqui llego");
        return response()->json(['search' => $searchResult]);
    }
}
