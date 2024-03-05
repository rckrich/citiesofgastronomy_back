<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chef extends Model
{
    use HasFactory;
    protected $table = "chef";


    public function list($search, $page, $cant)
    {
      $offset = ($page-1) * $cant;

        return $this
                      ->select("id", "name")
                      -> orderBy("name", 'DESC')
                      -> where( "name", 'LIKE', "%{$search}%")
                      -> limit($cant)
                      -> offset($offset)
                      -> get()
                      -> toArray();
    }


}
