<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Filter;

class Initiatives extends Model
{
    use HasFactory;
    protected $table = "initiatives";

    public function list($search, $page,$cantItems, $order, $filterType, $filterTopic, $filterSDG, $filterConnections)
    {
        $offset = ($page - 1) * $cantItems;


        $initiative =  $this    -> select(  "initiatives.id", "initiatives.name", "initiatives.continent",
                                            "initiatives.startDate", "initiatives.endDate",
                                            "initiatives.description", "initiatives.photo")
                        -> where('initiatives.active', '1')

                        -> join( "filter", "filter.idOwner", '=', "initiatives.id" )
                        -> where('filter.idOwnerSection', '=', '7')

                        ;
        //////SDG
        $initiative -> with('sdgFilter');
        $initiative -> with('sdgFilter.sdgDatta');

        //////TYPE
        $initiative -> with('typeFilter');
        $initiative -> with('typeFilter.typeDatta');

        if($filterType>0){
            $initiative -> whereHas('typeSearch', function (Builder $query ) use ($filterType) {
                $query->where('filter', $filterType);
            });
        };

        /////////////////
        $initiative ->distinct() -> limit($cantItems) -> offset($offset);

        if($order == '' || $order == 'name'){
            $result = $initiative -> orderBy("initiatives.name", 'ASC' ) -> get();
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
    public function typeSearch(){
        return $this->hasOne(Filter::class, 'idOwner', 'id')->where('type', 'TypeOfActivity')->where('idOwnerSection', '7');
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
