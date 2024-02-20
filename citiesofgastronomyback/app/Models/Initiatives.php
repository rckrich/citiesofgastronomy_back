<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Filter;

class Initiatives extends Model
{
    use HasFactory;
    protected $table = "initiatives";

    public function list($search, $page,$cantItems)
    {
        return $this    -> select("id", "name", "continent", "startDate", "startTime", "description")
                        -> where('active', '1')
                        -> orderBy("name", 'ASC' )
                        -> get()
                        -> toArray();
    }




    public function findInitiative($idFilter, $filterType)
    {
        return $this    -> select("initiatives.id", "initiatives.name")
                        -> join( "filter", "filter.idOwner", '=', "initiatives.id" )
                        -> where('filter.idOwnerSection', '=', '7')
                        -> where('filter.type', '=', $filterType)
                        -> where('filter.filter', '=', $idFilter)
                        -> orderBy("name", 'ASC' )
                        -> get()
                        -> toArray();
    }

  public function saveConnection(Request $request){
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
                $obj = new ConnectionsToOther;
                $obj->created_at = date("Y-m-d H:i:s");
            }else{
                Log::info("::MODIFICA ");
                $obj = ConnectionsToOther::findOrFail( $request->input("id")  );
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
