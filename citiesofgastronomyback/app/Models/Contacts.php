<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SocialNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Contacts extends Model
{
    use HasFactory;
    protected $table = "contacts";

    function socialNetwork()
    {
        return $this->hasMany(SocialNetwork::class, 'idOwner', 'id')->where('idSection', '11' );
    }


    public function list($search, $page, $cant)
    {
      $offset = ($page-1) * $cant;

        return $this
                      ->select("id", "idCity", "name", "position")
                      -> orderBy("created_at", 'DESC')
                      -> where("active", '1')
                      -> where( "name", 'LIKE', "%{$search}%")
                      -> limit($cant)
                      -> offset($offset)
                      -> get()
                      -> toArray();
    }






      public function contactSave(Request $request){
        $status = 200;$mensaje="Contact has been saved successfully";

            $objItem=[];

            try{
                if(  !$request->input("id")  ){
                    Log::info("::CREA contacto");
                    $objItem = new Contacts;
                    $objItem->created_at = date("Y-m-d H:i:s");
                    $objItem->active = '1';
                }else{
                    Log::info("::MODIFICA contacto");
                    $objItem = Contacts::findOrFail( $request->input("id")  );
                };
                $objItem->name = $request->input("name");
                $objItem->idCity = $request->input("idCity");
                $objItem->position = $request->input("position");
                $objItem->updated_at = date("Y-m-d H:i:s");
                $objItem -> save();

            } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Error";
            };


            return $objItem;
      }


}
