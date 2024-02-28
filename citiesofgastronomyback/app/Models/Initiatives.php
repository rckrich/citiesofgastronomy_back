<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Filter;
use App\Models\Images;
use App\Models\Links;
use App\Models\Files;

class Initiatives extends Model
{
    use HasFactory;
    protected $table = "initiatives";

    public function list($search, $page,$cantItems, $order, $filterType, $filterTopic, $filterSDG, $filterConnections, $filterCities = '')
    {
        $offset = ($page - 1) * $cantItems;


        $initiative =  $this    -> select(  "initiatives.id", "initiatives.name", "initiatives.continent",
                                            "initiatives.startDate", "initiatives.endDate",
                                            "initiatives.description", "initiatives.photo")
                        -> where('initiatives.active', '1')
                        -> where( "initiatives.name", 'LIKE', "%{$search}%")

                        -> join( "filter", "filter.idOwner", '=', "initiatives.id" )
                        -> where('filter.idOwnerSection', '=', '7')

                        ;
        //////CITIES
        //$initiative -> with('citiesFilter');
        //$initiative -> with('citiesFilter.sdgDatta');

        //////SDG
        $initiative -> with('sdgFilter');
        $initiative -> with('sdgFilter.sdgDatta');

        if($filterSDG>0){
            $initiative -> whereHas('typeSDG', function (Builder $query ) use ($filterSDG) {
                $query->where('filter', $filterSDG);
            });
        };

        //////TOPICS
        //$initiative -> with('topicsFilter');
        //$initiative -> with('topicsFilter.topicsDatta');

        if($filterTopic>0){
            $initiative -> whereHas('typeTopics', function (Builder $query ) use ($filterTopic) {
                $query->where('filter', $filterTopic);
            });
        };

        //////CONNECTIONS
        //$initiative -> with('conectionsFilter');
        //$initiative -> with('conectionsFilter.connectionsDatta');

        if($filterConnections>0){
            $initiative -> whereHas('typeConnections', function (Builder $query ) use ($filterConnections) {
                $query->where('filter', $filterConnections);
            });
        };

        //////TYPE
        $initiative -> with('typeFilter');
        $initiative -> with('typeFilter.typeDatta');

        if($filterType>0){
            $initiative -> whereHas('typeSearch', function (Builder $query ) use ($filterType) {
                $query->where('filter', $filterType);
            });
        };

        //////CITIES
        //$initiative -> with('citiesFilter');
        //$initiative -> with('citiesFilter.citiesDatta');

        if($filterCities>0){
            $initiative -> whereHas('filterCities', function (Builder $query ) use ($filterCities) {
                $query->where('filter', $filterCities);
            });
        };
        /////////////////
        $initiative ->distinct() -> limit($cantItems) -> offset($offset);

        if($order == '' || $order == 'name'){
            $result = $initiative -> orderBy("initiatives.startDate", 'ASC' ) -> get();
            //$result = $initiative -> orderBy("initiatives.name", 'ASC' ) -> get();
        }elseif($order == 'id' ){
            $result = $initiative -> orderBy("initiatives.id", 'DESC' ) -> get();
        };

        if($result){
            $result -> toArray();
        }else{
            $result = [];
        };

        return $result;
    }




    public function sdgFilter(){
        return $this->hasMany(Filter::class, 'idOwner', 'id')->where('type', 'SDG')->where('idOwnerSection', '7');
    }
    public function typeFilter(){
        return $this->hasMany(Filter::class, 'idOwner', 'id')->where('type', 'TypeOfActivity')->where('idOwnerSection', '7');
    }
    public function topicsFilter(){
        return $this->hasMany(Filter::class, 'idOwner', 'id')->where('type', 'Topics')->where('idOwnerSection', '7');
    }
    public function conectionsFilter(){
        return $this->hasMany(Filter::class, 'idOwner', 'id')->where('type', 'ConnectionsToOther')->where('idOwnerSection', '7');
    }
    public function citiesFilter(){
        return $this->hasMany(Filter::class, 'idOwner', 'id')->where('type', 'Cities')->where('idOwnerSection', '7');
    }


    public function images(){
        return $this->hasMany(Images::class, 'idOwner', 'id')->where('active', '1')->where('idSection', '7');
    }
    public function links(){
        return $this->hasMany(Links::class, 'idOwner', 'id')->where('active', '1')->where('idSection', '7');
    }
    public function pdf(){
        return $this->hasMany(Files::class, 'idOwner', 'id')->where('active', '1')->where('idSection', '7');
    }
    public function typeSearch(){
        return $this->hasOne(Filter::class, 'idOwner', 'id')->where('type', 'TypeOfActivity')->where('idOwnerSection', '7');
    }
    public function typeSDG(){
        return $this->hasOne(Filter::class, 'idOwner', 'id')->where('type', 'SDG')->where('idOwnerSection', '7');
    }
    public function typeTopics(){
        return $this->hasOne(Filter::class, 'idOwner', 'id')->where('type', 'Topics')->where('idOwnerSection', '7');
    }
    public function typeConnections(){
        return $this->hasOne(Filter::class, 'idOwner', 'id')->where('type', 'ConnectionsToOther')->where('idOwnerSection', '7');
    }
    public function filterCities(){
        return $this->hasOne(Filter::class, 'idOwner', 'id')->where('type', 'Cities')->where('idOwnerSection', '7');
    }



    public function findInitiative($idFilter, $filterType)
    {
        return $this    -> select("initiatives.id", "initiatives.name")
                        -> join( "filter", "filter.idOwner", '=', "initiatives.id" )
                        -> where('filter.idOwnerSection', '=', '7')
                        -> where('filter.type', '=', $filterType)
                        -> where('filter.filter', '=', $idFilter)
                        -> orderBy("name", 'ASC' )
                        -> get()
                        -> toArray();
    }

  public function saveConnection(Request $request){
    $status = 200;$mensaje="Filter has been saved successfully";
    Log::info("###-->");
        Log::info($request->input("id"));
        Log::info($request->input("name"));

        $obj=[];
        try{
            $request->validate ([
                'name' => 'required|string'
            ]);

            if(  !$request->input("id")  ){
                $obj = new ConnectionsToOther;
                $obj->created_at = date("Y-m-d H:i:s");
            }else{
                Log::info("::MODIFICA ");
                $obj = ConnectionsToOther::findOrFail( $request->input("id")  );
            };
            $obj->name = $request->input("name");
            $obj->updated_at = date("Y-m-d H:i:s");
            $obj -> save();
        //}else{
        } catch ( \Exception $e ) {
            Log::info($e);
            $status = 400;$mensaje="Incorrect name format";
        };

        $arrayDatta["datta"] = $obj;
        $arrayDatta["mensaje"] = $mensaje;
        $arrayDatta["status"] = $status;

        return $arrayDatta;
  }
}
