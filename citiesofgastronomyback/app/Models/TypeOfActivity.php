<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TypeOfActivity extends Model
{
    use HasFactory;
    protected $table = "type_of_activity";

    public function list()
    {
        return $this    -> select("id", "name")
                        -> orderBy("name", 'ASC' )
                        -> get()
                        -> toArray();
    }




  public function saveType(Request $request){
    $status = 200;$mensaje="Filter has been saved successfully";
    Log::info("###-->");
        Log::info($request->input("id"));
        Log::info($request->input("name"));

        $obj=[];
        try{
            $request->validate ([
                'name' => 'required|string'
            ]);

            if(  !$request->input("id")  ){
            $obj = new TypeOfActivity;
            $obj->created_at = date("Y-m-d H:i:s");
            }else{
                Log::info("::MODIFICA contacto");
                $obj = TypeOfActivity::findOrFail( $request->input("id")  );
            };
            $obj->name = $request->input("name");
            $obj->updated_at = date("Y-m-d H:i:s");
            $obj -> save();
        //}else{
        } catch ( \Exception $e ) {
            Log::info($e);
            $status = 400;$mensaje="Incorrect name format";
        };

        $arrayDatta["datta"] = $obj;
        $arrayDatta["mensaje"] = $mensaje;
        $arrayDatta["status"] = $status;

        return $arrayDatta;
  }





}
