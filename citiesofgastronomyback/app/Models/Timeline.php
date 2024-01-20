<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use DB;

class Timeline extends Model
{
    use HasFactory;
    protected $table = "timeline";

    public function list($search, $page, $cant)
    {
        $result = [];
        $offset = ($page-1) * $cant;

        try{
            if($search){
                $result =  $this    -> select(DB::raw('id,tittle,link, startDate, endDate,
                                            DATE_FORMAT(startDate, "%d.%b.%Y") AS startDateFormat,
                                            DATE_FORMAT(endDate, "%d.%b.%Y") AS endDateFormat
                                    '))
                            -> where( "active", '=', '1' )
                            -> where( "tittle",  "LIKE", "%$search%" )
                            -> orderBy("startDate", 'ASC' )
                            -> limit($cant)
                            -> offset($offset)
                            -> get()-> toArray();


            }else{
                $result =  $this    -> select(DB::raw('id,tittle,link, startDate, endDate,
                                            DATE_FORMAT(startDate, "%d.%b.%Y") AS startDateFormat,
                                            DATE_FORMAT(endDate, "%d.%b.%Y") AS endDateFormat
                                    '))
                            -> where( "active", '=', '1' )
                            -> orderBy("startDate", 'ASC' )
                            -> limit($cant)
                            -> offset($offset)
                            -> get()-> toArray();
            };

        } catch ( \Exception $e ) {
            Log::info($e);
            $result = [];
        };

        return $result;
    }

    public function searchList($search, $page, $cant)
    {
        return $this->list($search, $page, $cant);
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
