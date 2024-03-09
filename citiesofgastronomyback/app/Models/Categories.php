<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $table  = "categories";

    public function list($search)
    {

        return $this
                      ->select("id", "name")
                      -> orderBy("name", 'ASC')
                      -> where( "name", 'LIKE', "%{$search}%")
                      -> get()
                      -> toArray();
    }
    public function findName($name, $id)
    {
        return $this    -> select("id", "name")
                        -> where('id', '!=', $id)
                        -> where('name', 'LIKE', "%{$name}%")
                        -> orderBy("name", 'ASC' )
                        -> get()
                        -> toArray();
    }
}
