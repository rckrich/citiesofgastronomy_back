<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Cities;
use App\Models\Images;
use Illuminate\Support\Facades\Log;

class Cities extends Model
{

  protected $table = "cities";

  public function list($page, $cant)
  {
    $offset = ($page-1) * $cant;

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
                    -> orderBy("cities.name", 'ASC' )
                    -> limit($cant)
                    -> offset($offset)
                    -> get()
                    -> toArray();
  }

  public function searchList($search, $page, $cant)
  {
    $offset = ($page-1) * $cant;

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
                    -> where( "cities.name", 'LIKE', "%{$search}%")
                    -> limit($cant)
                    -> offset($offset)
                    -> get()
                    -> toArray();
  }

  public function serch( $id, $lActivo = true )
  {
  	return $this    -> select(
                            "cities.id",
                            "cities.name",
                            "cities.country",
                            "cities.population",
                            "cities.restaurantFoodStablishments",
                            "cities.description",
                            "cities.designationyear",
                            "cities.photo",
                            "cities.logo",
                            "cities.banner",
                            "cities.completeDescription",
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
    $image = '';$status = 200;$mensaje="City has been saved successfully";

    //Log::info("##ingreso a STORE :::");
    $photo = '';
        if($request->file("photo")){
            //Log::info("##ingreso a is FILE :::");
            try{
                $request->validate ([
                    'photo' => 'image|max:50000'
                ]);
                //$photo =  $request->file("photo")->store('public/images/cities');
                //$photo = str_replace('public/', 'storage/', $photo);
                $photo = (New Images())->storeResize($request->file("photo"), '756', '456', 'cities');
            } catch ( \Exception $e ) {
                //Log::info("-->Error al cargar la imagen ###");
                Log::info($e);
            }
        };



        $objCity=[];
        try{
            $request->validate ([
                'name' => 'required|string'
            ]);

            $objCity = new Cities;
            $objCity->name = $request->input("name");
            $objCity->country = $request->input("country");
            $objCity->idContinent = $request->input("idContinent");
            $objCity->population = $request->input("population");
            $objCity->restaurantFoodStablishments = $request->input("restaurantFoodStablishments");
            //$objCity->description = $request->input("description");
            $objCity->designationyear = $request->input("designationyear");
            $objCity->photo = $photo;
            $objCity->completeInfo = '0';
            $objCity->active = '1';
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















  public function citiesUpdate(Request $request, $tipo){
    $image = '';$status = 200;$mensaje="City has been saved successfully";

    //Log::info("##ingreso a STORE :::");
    $photo = '';
        if($request->file("photo")){
            Log::info("##ingreso a is FILE :::");
            try{
                $request->validate ([
                    'photo' => 'image|max:50000'
                ]);
                //// GUARDO LA IMAGEN
                //$photo =  $request->file("photo")->store('public/images/cities');
                //$photo = str_replace('public/', 'storage/', $photo);


                $photo = (New Images())->storeResize($request->file("photo"), '756', '456', 'cities');
                Log::info($photo);

            } catch ( \Exception $e ) {
                //Log::info("-->Error al cargar la imagen ###");
                Log::info($e);
            }
        };



        $objCity=[];
        try{
            $request->validate ([
                'name' => 'required|string'
            ]);
            $id = $request->input("id");
            $objCity = Cities::findOrFail($id);
            $objCity->name = $request->input("name");
            $objCity->country = $request->input("country");
            $objCity->idContinent = $request->input("idContinent");
            $objCity->population = $request->input("population");
            $objCity->restaurantFoodStablishments = $request->input("restaurantFoodStablishments");
            $objCity->designationyear = $request->input("designationyear");

            if($tipo == 'complete'){
                $objCity->description = $request->input("description");
                $objCity->completeInfo = '0';
            };

            if($photo){
                $objCity->photo = $photo;
            };
            //$objCity->active = '1';
            //$objCity->created_at = date("Y-m-d H:i:s");
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
