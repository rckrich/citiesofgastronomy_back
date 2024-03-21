<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{


    public function list(Request $request){


        $message = '';$status='200';
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;

        $obj =(New User())->list($request->search, $page, $cantItems);
        $totalUsers =(New User())->list($request->search, 1, 99999999999999);

        $total = count($totalUsers);
        if($total > $cantItems){
            $division = $total / $cantItems;
            $paginator = intval($division);
            if($paginator < $division){
                $paginator = $paginator + 1;
            };
        };
        if($total == 0){$message = "No results found";}

        return response()->json([
                'Users' => $obj,
                'tot' => $total,
                'paginator' => $paginator,
                'message' => $message,
                'status' => $status
            ]);
    }


}
