<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SocialNetwork;

class Contacts extends Model
{
    use HasFactory;
    protected $table = "contacts";

    function socialNetwork()
    {
        return $this->hasMany(SocialNetwork::class, 'idOwner', 'id');
    }

    public function list($search, $page, $cant)
    {
      $offset = ($page-1) * $cant;

        return $this
                      ->select("id", "idCity", "name", "position")
                      -> orderBy("created_at", 'DESC')
                      -> where("active", '1')
                      -> limit($cant)
                      -> offset($offset)
                      -> get()
                      -> toArray();
    }




    public function store(Request $request){
        $status = 200;$mensaje="Contact has been saved successfully";

            $objItem=[];
            try{
                $objItem = new Contacts;
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




      public function saveContact(Request $request){
        $status = 200;$mensaje="Contact has been saved successfully";

            $objItem=[];
            try{
                $objItem = new Contacts;
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


}
