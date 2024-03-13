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

                      -> where( "recipes.idChef", 'LIKE', "%{$chef}%")
                      -> where( "recipes.idCity", 'LIKE', "%{$city}%")
                      -> where( "recipes.idCategory", 'LIKE', "%{$category}%")
                      -> orderBy("recipes.id", 'DESC')
                      -> limit($cant)
                      -> offset($offset)
                      -> get()
                      -> toArray();

        return $obj;
    }

    public function findResipe($id)
    {

        $obj = $this -> select("recipes.id", "recipes.name", "recipes.photo",
                "recipes.description", "recipes.difficulty", "recipes.prepTime", "recipes.totalTime",
                "recipes.servings", "recipes.ingredients", "recipes.preparations", "recipes.vote AS votes",
                "chef.name AS chefName", "categories.name AS categoryName", "cities.name AS cityName"
                )
            -> join( "chef", "chef.id", '=', "recipes.idChef" )
            -> join( "categories", "categories.id", '=', "recipes.idCategory" )
            -> join( "cities", "cities.id", '=', "recipes.idCity" )
            -> where('recipes.id', $id)
            -> first();

        return $obj;
    }
}
