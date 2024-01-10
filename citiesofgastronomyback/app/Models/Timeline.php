<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    use HasFactory;
    protected $table = "timeline";

    public function list($page, $cant)
    {
      $offset = ($page-1) * $cant;

        return $this    -> select("id","tittle","link", "startDate", "endDate")
                      -> where( "active", '=', '1' )
                      -> orderBy("tittle", 'ASC' )
                      -> limit($cant)
                      -> offset($offset)
                      -> get()
                      -> toArray();
    }

    public function searchList($search, $page, $cant)
    {
        $offset = ($page-1) * $cant;

        return $this    -> select("id","tittle","link", "startDate", "endDate")
                        -> where( "active", '=', '1' )
                        -> where( "tittle", 'LIKE', "%{$search}%")
                        -> limit($cant)
                        -> offset($offset)
                        -> get()
                        -> toArray();
    }
    public function serch( $id, $lActivo = true )
    {
        return $this    -> select("id","tittle","link", "startDate", "endDate")
                        -> where( "active", '=', '1' )
                        -> where( "id", '=', $id )
                        -> first()
                        -> toArray();
    }




    public function store(Request $request){
        $status = 200;$mensaje="Timeline has been saved successfully";

            $objTimeline=[];
            try{
                $request->validate ([
                    'tittle' => 'required|string'
                ]);

                $objTimeline = new Timeline;
                $objTimeline->tittle = $request->input("tittle");
                $objTimeline->link = $request->input("link");
                $objTimeline->startDate = $request->input("startDate");
                $objTimeline->endDate = $request->input("endDate");
                $objTimeline->active = '1';
                $objTimeline->created_at = date("Y-m-d H:i:s");
                $objTimeline->updated_at = date("Y-m-d H:i:s");
                $objTimeline -> save();
            //}else{
            } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Error";
            };

            $arrayDatta["datta"] = $objTimeline;
            $arrayDatta["mensaje"] = $mensaje;
            $arrayDatta["status"] = $status;

            return $arrayDatta;
    }





    public function citiesUpdate(Request $request){
        $status = 200;$mensaje="Timeline has been saved successfully";

            $objTimeline=[];
            try{
                $id = $request->input("id");
                $request->validate ([
                    'tittle' => 'required|string'
                ]);

                $objTimeline = Timeline::findOrFail($id);
                $objTimeline->tittle = $request->input("tittle");
                $objTimeline->link = $request->input("link");
                $objTimeline->startDate = $request->input("startDate");
                $objTimeline->endDate = $request->input("endDate");

                //$objTimeline->active = '1';
                //$objTimeline->created_at = date("Y-m-d H:i:s");
                $objTimeline->updated_at = date("Y-m-d H:i:s");
                $objTimeline -> save();
            //}else{
            } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Error";
            };

            $arrayDatta["datta"] = $objTimeline;
            $arrayDatta["mensaje"] = $mensaje;
            $arrayDatta["status"] = $status;

            return $arrayDatta;
      }
}
