<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SDG;
use App\Models\TypeOfActivity;

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
    public function typeSearch(){
        return $this->hasOne(TypeOfActivity::class, 'id', 'filter');
    }
}
