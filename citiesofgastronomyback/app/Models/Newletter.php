<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;

class Newletter extends Model
{
    use HasFactory;
    protected $table = "newletter";


    public function store(Request $request){
        $status = 200;$mensaje="Mail has been saved successfully";

            $objItem=[];
            try{
                $objItem = new Newletter;
                $objItem->email = $request->input("newslettermail");
                $objItem->created_at = date("Y-m-d H:i:s");
                $objItem->updated_at = date("Y-m-d H:i:s");
                $objItem -> save();

            } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Error";
            };

            $arrayDatta["datta"] = $objItem;
            $arrayDatta["mensaje"] = $mensaje;
            $arrayDatta["status"] = $status;

            return $arrayDatta;
      }

      public function list($page, $cant)
      {
        $offset = ($page-1) * $cant;

          return $this
                        ->select(DB::raw('id, email, DATE_FORMAT(created_at, "%d %M %Y") AS SuscribeDate'))
                        -> orderBy("created_at", 'DESC')
                        -> limit($cant)
                        -> offset($offset)
                        -> get()
                        -> toArray();
      }
}
