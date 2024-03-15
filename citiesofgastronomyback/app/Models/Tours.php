<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tours extends Model
{
    use HasFactory;
    protected $table = "tours";

    public function list($search, $page, $cant, $type = 'admin')
    {
        $result = [];
        $offset = ($page-1) * $cant;

        try{
            if($type== 'admin'){
                $result =  $this    -> select("tours.id", "cities.name AS cityName", "tours.name" )
                            //-> where( "active", '=', '1' )
                            -> where( "tours.name",  "LIKE", "%$search%" )
                            -> join('cities', "cities.id", "tours.idCity")
                            -> orderBy("tours.name", 'ASC' )
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

}
