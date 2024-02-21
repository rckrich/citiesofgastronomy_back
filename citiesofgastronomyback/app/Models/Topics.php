<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class Topics extends Model
{
    use HasFactory;
    protected $table = "topics";

    public function list($search)
    {
        return $this    -> select("id", "name")
                        -> where('name', 'LIKE', "%{$search}%")
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


  public function saveTopic(Request $request){
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
            $obj = new Topics;
            $obj->created_at = date("Y-m-d H:i:s");
            }else{
                Log::info("::MODIFICA contacto");
                $obj = Topics::findOrFail( $request->input("id")  );
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
