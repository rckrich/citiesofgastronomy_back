<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class SDG extends Model
{
    use HasFactory;
    protected $table = "SDG";

    public function list($search)
    {
        return $this    -> select("id", "name", "number")
                        -> where('name', 'LIKE', "%{$search}%")
                        -> orderBy("number", 'ASC' )
                        -> orderBy("name", 'ASC' )
                        -> get()
                        -> toArray();
    }

    public function findName($name, $id)
    {
        return $this    -> select("id", "name")
                        -> where('id', '!=', $id)
                        -> where('name', 'LIKE', "%{$name}%")
                        -> orderBy("name", 'ASC' )
                        -> get()
                        -> toArray();
    }


  public function saveSDG(Request $request){
    $status = 200;$mensaje="Filter has been saved successfully";
    Log::info("###-->");
        Log::info($request->input("id"));
        Log::info($request->input("name"));
        Log::info($request->input("number"));

        $obj=[];
        try{
            $request->validate ([
                'name' => 'required|string'
            ]);

            if(  !$request->input("id")  ){
                $obj = new SDG;
                $obj->created_at = date("Y-m-d H:i:s");
            }else{
                Log::info("::MODIFICA ");
                $obj = SDG::findOrFail( $request->input("id")  );
            };
            $obj->name = $request->input("name");
            $obj->number = $request->input("number");
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
