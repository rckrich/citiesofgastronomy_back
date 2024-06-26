<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chef;
use App\Models\Categories;
use Illuminate\Support\Facades\Log;

class Recipes extends Model
{
    use HasFactory;
    protected $table = "recipes";


    public function list($search, $page, $cant, $chef, $category, $city)
    {
        $offset = ($page-1) * $cant;

        $w = array(
			array( "recipes.active", '=', '1' )
		);

        if($chef){
            $arrchef = array("recipes.idChef", '=', $chef);
            array_push($w , $arrchef);
        };

        if($city){
            $arrcity = array("recipes.idCity", '=', $city);
            array_push($w , $arrcity);
        };

        if($category){
            $arrcategory = array("recipes.idCategory", '=', $category);
            array_push($w , $arrcategory);
        };

        $obj = $this  -> select("recipes.id", "recipes.name", "recipes.photo", "recipes.idChef", "recipes.idCategory", "recipes.idCity",
                                "chef.name AS chefName", "categories.name AS categoryName", "cities.name AS cityName",
                                "recipes.vote AS votes")
                      -> join( "chef", "chef.id", '=', "recipes.idChef" )
                      -> join( "categories", "categories.id", '=', "recipes.idCategory" )
                      -> join( "cities", "cities.id", '=', "recipes.idCity" )


                      ->where(function($query) use ($search){
                        $query  -> where( "recipes.name", 'LIKE', "%{$search}%")
                                -> orWhere( "chef.name", 'LIKE', "%{$search}%")
                                -> orWhere( "categories.name", 'LIKE', "%{$search}%")
                                -> orWhere( "cities.name", 'LIKE', "%{$search}%");
                        })
                      ->where( $w )
                      //-> where( "recipes.idChef", $chef)
                      //-> where( "recipes.idCity", $city)
                      //-> where( "recipes.idCategory", $category)

                      -> orderBy("recipes.id", 'DESC')
                      -> limit($cant)
                      -> offset($offset)
                      -> get()
                      -> toArray();

        return $obj;
    }

    function chef()
    {
        return $this->hasOne(Chef::class, 'idChef', 'id');
    }

    public function findResipe($id, $type = 'user')
    {
        if($type == 'user'){
            $obj = $this -> select("recipes.id", "recipes.name", "recipes.photo", "recipes.idChef",
                "recipes.description", "recipes.difficulty", "recipes.prepTime", "recipes.totalTime",
                "recipes.servings", "recipes.ingredients", "recipes.preparations", "recipes.vote AS votes",
                "chef.name AS chefName", "categories.name AS categoryName", "cities.name AS cityName"
                )
            -> join( "chef", "chef.id", '=', "recipes.idChef" )
            -> join( "categories", "categories.id", '=', "recipes.idCategory" )
            -> join( "cities", "cities.id", '=', "recipes.idCity" )
            -> where('recipes.id', $id)
            -> first();

            if($obj){
                $objChef = Chef::select("social_network_type.id", "social_network_type.name", "social_network_type.icon",
                        "social_network.social_network AS value")
                    -> join( "social_network", "social_network.idOwner", '=', "chef.id" )
                    -> join( "social_network_type", "social_network_type.id", '=', "social_network.idSocialNetworkType" )
                    ->where("chef.id", $obj["idChef"])
                    ->where("social_network.idSection", "12")
                    ->get();
                $obj["socialMedia"] = $objChef;
            };
        }else{
            $obj = $this -> select("recipes.id", "recipes.name", "recipes.photo", "recipes.idChef",
                "recipes.description", "recipes.difficulty", "recipes.prepTime", "recipes.totalTime",
                "recipes.servings", "recipes.ingredients", "recipes.preparations", "recipes.vote AS votes",
                "categories.id AS idCategory", "cities.id AS idCity"
                )
            -> join( "chef", "chef.id", '=', "recipes.idChef" )
            -> join( "categories", "categories.id", '=', "recipes.idCategory" )
            -> join( "cities", "cities.id", '=', "recipes.idCity" )
            -> where('recipes.id', $id)
            -> first();

        }
            //-> with("recipes.chef")
            //-> with("chef.socialNetwork")

        return $obj;
    }
}
