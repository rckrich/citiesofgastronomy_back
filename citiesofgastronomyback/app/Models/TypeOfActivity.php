<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfActivity extends Model
{
    use HasFactory;
    protected $table = "type_of_activity";

    public function list()
    {
        return $this    -> select("name")
                        -> orderBy("name", 'ASC' )
                        -> get()
                        -> toArray();
    }




  public function TypeOfActivity(Request $request){
    $status = 200;$mensaje="Filter has been saved successfully";

        $objCity=[];
        try{
            $request->validate ([
                'name' => 'required|string'
            ]);

            $objCity = new Cities;
            $objCity->name = $request->input("name");
            $objCity->created_at = date("Y-m-d H:i:s");
            $objCity->updated_at = date("Y-m-d H:i:s");
            $objCity -> save();
        //}else{
        } catch ( \Exception $e ) {
            Log::info($e);
            $status = 400;$mensaje="Incorrect name format";
        };

        $arrayDatta["datta"] = $objCity;
        $arrayDatta["mensaje"] = $mensaje;
        $arrayDatta["status"] = $status;

        return $arrayDatta;
  }





}
