<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SDG;
use App\Models\TypeOfActivity;
use App\Models\Topics;
use App\Models\ConnectionsToOther;
use App\Models\Cities;

class Filter extends Model
{
    use HasFactory;
    protected $table = 'filter';

    public function sdgDatta(){
        return $this->hasOne(SDG::class, 'id', 'filter');
    }

    public function typeDatta(){
        return $this->hasOne(TypeOfActivity::class, 'id', 'filter');
    }

    public function topicsDatta(){
        return $this->hasOne(Topics::class, 'id', 'filter');
    }

    public function connectionsDatta(){
        return $this->hasOne(ConnectionsToOther::class, 'id', 'filter');
    }

    public function citiesDatta(){
        return $this->hasOne(Cities::class, 'id', 'filter');
    }

    public function typeSearch(){
        return $this->hasOne(TypeOfActivity::class, 'id', 'filter');
    }
}
