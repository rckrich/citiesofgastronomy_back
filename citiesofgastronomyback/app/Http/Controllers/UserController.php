<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function store(Request $request){
        $status = 200; $obj = []; $message = 'The administrator was successfully created';

        $obj = (New User())->mailFind($request->email);
        if( count($obj) > 0 ){

            $status = 400; $obj = []; $message = 'This email is already registered';
        }else{

        $obj = (New User())->userSave($request);
        };

        return response()->json([
            'User' => $obj,
            'message' => $message,
            'status' => $status
        ]);
    }
    public function resetPassword(Request $request){
        //$obj = new Cities;

        $obj = User::where( 'remember_token', $request->token  );
        $obj->password = Hash::make($request->password);
        $obj->updated_at = date("Y-m-d H:i:s");
        $obj -> save();
    }

}
