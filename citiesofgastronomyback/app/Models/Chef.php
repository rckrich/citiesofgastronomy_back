<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\SocialNetwork;

class Chef extends Model
{
    use HasFactory;
    protected $table = "chef";

    function socialNetwork()
    {
        return $this->hasMany(SocialNetwork::class, 'idOwner', 'id')->where('idSection', '12' );
    }

    public function list($search, $page, $cant)
    {
      $offset = ($page-1) * $cant;

        return $this
                      ->select("id", "name")
                      -> orderBy("name", 'ASC')
                      -> where( "name", 'LIKE', "%{$search}%")
                      -> with("socialNetwork")
                      -> limit($cant)
                      -> offset($offset)
                      -> get()
                      -> toArray();
    }



}
