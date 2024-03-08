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

    public function list($search, $page, $cant)
    {
        $offset = ($page-1) * $cant;

        $obj = $this  -> select("recipes.id", "recipes.name", "recipes.photo", "recipes.idChef", "recipes.idCategory", "recipes.idCity",
                                "chef.name AS chefName", "categories.name AS categoryName", "cities.name AS cityName")
                      -> join( "chef", "chef.id", '=', "recipes.idChef" )
                      -> join( "categories", "categories.id", '=', "recipes.idCategory" )
                      -> join( "cities", "cities.id", '=', "recipes.idCity" )
                      -> where( "recipes.name", 'LIKE', "%{$search}%")
                      -> orderBy("recipes.id", 'DESC')
                      -> limit($cant)
                      -> offset($offset)
                      -> get()
                      -> toArray();

        return $obj;
    }


}
