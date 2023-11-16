<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Cities;
use Illuminate\Support\Facades\Log;

class Cities extends Model
{

  protected $table = "cities";

  public function list()
  {
  	return $this    -> select(
                            "cities.id",
                            "cities.name",
                            "cities.country",
                            "cities.population",
                            "cities.restaurantFoodStablishments",
                            "cities.designationyear",
                            "cities.photo",
                            "cities.completeInfo",
                            "continent.name AS continentName"
                            )
                    -> join( "continent", "continent.id", '=', "cities.idContinent" )
                    -> where( "cities.active", '=', '1' )
                    -> get()
                    -> toArray();
  }

  public function serch( $id, $lActivo = true )
  {
  	return $this    -> select(
                            "cities.name",
                            "cities.country",
                            "cities.population",
                            "cities.restaurantFoodStablishments",
                            "cities.description",
                            "cities.designationyear",
                            "cities.photo",
                            "cities.logo",
                            "cities.banner",
                            "cities.completeInfo",
                            "cities.active",
                            "continent.id AS continentId",
                            "continent.name AS continentName"
                            )
                    -> join( "continent", "continent.id", '=', "cities.idContinent" )
                    -> where( "cities.active", '=', '1' )
                    -> where( "cities.id", '=', $id )
                    -> first()
                    -> toArray();
  }









  public function store(Request $request){
    $image = '';$status = 200;$mensaje="La ciudad se ha guardado correctamente";

    $photo = '';
        if($request->file("photo")){
            try{
                $request->validate ([
                    'photo' => 'image|max:50000'
                ]);
                $photo =  $request->file("photo")->store('public/images/cities');
                $photo = str_replace('public/', 'storage/', $photo);
            } catch ( \Exception $e ) {

            }
        };



        $objCity=[];
        try{
            $request->validate ([
                'name' => 'required|string'
            ]);

            $objCity = new Cities;
            $objCity->idContinent = $request->input("idContinent");
            $objCity->name = $request->input("name");
            $objCity->country = $request->input("country");
            $objCity->population = $request->input("population");
            $objCity->restaurantFoodStablishments = $request->input("restaurantFoodStablishments");
            $objCity->description = $request->input("description");
            $objCity->designationyear = $request->input("designationyear");
            $objCity->completeInfo = '0';
            $objCity->active = '1';
            $objCity->photo = $photo;
            $objCity->created_at = date("Y-m-d H:i:s");
            $objCity->updated_at = date("Y-m-d H:i:s");
            $objCity -> save();
        //}else{
        } catch ( \Exception $e ) {
            Log::info($e);
            $status = 400;$mensaje="Formato de nombre incorrecto";
        };

        $arrayDatta["datta"] = $objCity;
        $arrayDatta["mensaje"] = $mensaje;
        $arrayDatta["status"] = $status;

        return $arrayDatta;
  }














}
