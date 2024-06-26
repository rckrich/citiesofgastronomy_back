<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SocialNetwork;
use Illuminate\Support\Facades\Log;

class Tours extends Model
{
    use HasFactory;
    protected $table = "tours";

    public $socialTypeArr = [1,2,3,4,5,6];

    function socialType(){
        return $this->socialTypeArr;
    }

    function socialNetwork()
    {
        return $this->hasMany(SocialNetwork::class, "idOwner", "id")
        ->where("idSection", '9' )
        //->whereIn('idSocialNetworkType', $this->socialTypeArr);
        ;
    }

    public function list($search, $city, $page, $cant, $type = 'admin')
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
                }else{
                        if($city != ''){
                                $result =  $this    -> select("tours.id", "tours.name", "tours.travelAgency", "tours.photo",
                                                        "tours.description","cities.name AS cityName" )
                                    //-> where( "active", '=', '1' )
                                    -> where( "tours.idCity", $city )
                                    -> join('cities', "cities.id", "tours.idCity")
                                    -> orderBy("tours.id", 'DESC' )
                                    -> limit($cant)
                                    -> offset($offset)
                                    -> get()-> toArray();
                        }else{

                            $result =  $this    -> select("tours.id", "tours.name", "tours.travelAgency", "tours.photo",
                            "tours.description","cities.name AS cityName" )
                                //-> where( "active", '=', '1' )
                                -> join('cities', "cities.id", "tours.idCity")
                                -> orderBy("tours.id", 'DESC' )
                                -> limit($cant)
                                -> offset($offset)
                                -> get()-> toArray();
                         };
                            Log::info("###CITY");
                            Log::info($city);
                    };
        } catch ( \Exception $e ) {
            Log::info($e);
            $result = [];
        };
        return $result;
    }







}
