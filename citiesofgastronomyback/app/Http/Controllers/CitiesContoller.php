<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cities;
use App\Models\Images;
use App\Models\Links;
use App\Models\Files;
use App\Models\Banners;
use Illuminate\Support\Facades\Log;

class CitiesContoller extends Controller
{
    public function list(){
        $objCities =(New Cities())->list();
        $objBanners = (New Banners())->list(1);
        //Log::info($objCities);
        return response()->json([
            'bannerCities' => $objBanners,
            'cities' => $objCities
        ]);
    }

    public function find($cityId){

        $objCity = (New Cities())->serch($cityId);
        $objgallery = (New Images())->list(1, $cityId);
        $objLinks = (New Links())->list(1, $cityId);
        $objFiles = (New Files())->list(1, $cityId);

        return response()->json([
            'cities' => $objCity,
            'gallery' => $objgallery,
            'links' => $objLinks,
            'files' => $objFiles,
            'bannerCities' => []
        ]);
    }

    public function store(Request $request)
    {
        /*
        //Verifica si existe el usuario
        if($request->accessToken){
            $userdatta = (New user_token())->show($request->accessToken);
            if(count($userdatta)>0){
                $user=$userdatta;
                $id_user = $user[0]["id"];
            };
        };
        //*/
        $objCity = (New Cities())->store($request);
        return response()->json([
            'status' =>  $objCity["status"],
            'message' =>  $objCity["mensaje"],
            'datta' =>  $objCity["datta"]
        ]);
    }











    public function update(Request $request, string $id)
    {
        $image = '';$status = 200;$mensaje="La ciudad se ha guardado correctamente";

        $photo = '';
            if($request->file("photo")){
                try{
                    $request->validate ([
                        'photo' => 'image|max:50000'
                    ]);
                    $photo =  $request->file("photo")->store('public/images/cities');
                    $photo = str_replace('public/', 'storage/', $photo);
                } catch ( \Exception $e ) {

                }
            };


            $objCity=[];
            try{
                $request->validate ([
                    'name' => 'required|string'
                ]);

                $objCity = Cities::find($id);
                $objCity->idContinent = $request->input("idContinent");
                $objCity->name = $request->input("name");
                $objCity->country = $request->input("country");
                $objCity->population = $request->input("population");
                $objCity->restaurantFoodStablishments = $request->input("restaurantFoodStablishments");
                $objCity->designationyear = $request->input("designationyear");
                if($photo){
                    $objCity->photo = $photo;
                };
                $objCity->updated_at = date("Y-m-d H:i:s");
                $objCity -> save();
            } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Formato de nombre incorrecto";
            };

        return response()->json([
            'status' =>  $status,
            'message' =>  $mensaje,
            'datta' =>  $objCity
        ]);
    }














    public function updateComplete(Request $request, string $id)
    {
        $image = '';$status = 200;$mensaje="La ciudad se ha guardado correctamente";

        $photo = '';
            if($request->file("photo")){
                try{
                    $request->validate ([
                        'photo' => 'image|max:50000'
                    ]);
                    $photo =  $request->file("photo")->store('public/images/cities');
                    $photo = str_replace('public/', 'storage/', $photo);
                } catch ( \Exception $e ) {

                }
            };

            $logo = '';
            if($request->file("logo")){
                try{
                    $request->validate ([
                        'logo' => 'image|max:50000'
                    ]);
                    $logo =  $request->file("logo")->store('public/images/cities');
                    $logo = str_replace('public/', 'storage/', $logo);
                } catch ( \Exception $e ) {

                }
            };

            $banner = '';
            if($request->file("banner")){
                try{
                    $request->validate ([
                        'banner' => 'image|max:50000'
                    ]);
                    $banner =  $request->file("logo")->store('public/images/cities');
                    $banner = str_replace('public/', 'storage/', $banner);
                } catch ( \Exception $e ) {

                }
            };

            $objCity=[];
            try{
                $request->validate ([
                    'name' => 'required|string'
                ]);

                $objCity = Cities::find($id);
                $objCity->idContinent = $request->input("idContinent");
                $objCity->name = $request->input("name");
                $objCity->country = $request->input("country");
                $objCity->population = $request->input("population");
                $objCity->restaurantFoodStablishments = $request->input("restaurantFoodStablishments");
                $objCity->description = $request->input("description");
                $objCity->completeDescription = $request->input("completeDescription");
                $objCity->designationyear = $request->input("designationyear");
                $objCity->completeInfo = $request->input("completeInfo");
                if($photo){
                    $objCity->photo = $photo;
                };
                if($logo){
                    $objCity->logo =  $logo;

                };
                if($banner){
                    $objCity->banner =  $banner;
                };
                $objCity->updated_at = date("Y-m-d H:i:s");
                $objCity -> save();
            } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Formato de nombre incorrecto";
            };

            /////////////////////////////   GALLERY
            $cant_gallery = $request->input("cant_gallery");

            for($i = 1; $i < $cant_gallery+1; $i++){
                $idg = 'image'.$i;
                $image = $request->file($idg);
                $idg = 'idImage'.$i;
                $idImage = $request->input($idg);
                $idg = 'deleteImage'.$i;
                $deleteImage = $request->input($idg);

                if(!$idImage){
                    if($image){

                        try{
                            $objGallery =(New Images())->storeIMG($image, $id, 1);
                            /*
                            $request->validate ([
                                $idg => 'image|max:50000'
                            ]);
                            $photo =  $request->file($idg)->store('public/images/gallery');
                            $photo = str_replace('public/', 'storage/', $photo);

                            $objCity = new Images;
                            $objCity->image = $photo;
                            $objCity->name = $id;
                            $objCity->active = 1;
                            $objCity->idSection = 1;
                            $objCity->created_at = date("Y-m-d H:i:s");
                            $objCity->updated_at = date("Y-m-d H:i:s");
                            $objCity -> save();//*/
                        } catch ( \Exception $e ) {

                        }
                    };
                }else{
                    if($deleteImage){

                    };
                };

            }
            /////////////////////////////   LINKS
            $cant_links = $request->input("cant_links");

            for($i = 1; $i < $cant_links+1; $i++){
                $idg = 'link'.$i;
                $link = $request->input($idg);
                $idg = 'idLink'.$i;
                $idLink = $request->input($idg);
                $idg = 'deleteLink'.$i;
                $deleteLink = $request->input($idg);

                if(!$idLink){
                    if($link){
                        try{
                            $objGallery =(New Links())->storeLINK($link, $id, 1);
                        } catch ( \Exception $e ) {

                        }
                    };
                }else{
                    if($deleteLink){

                    };
                };

            }



            /////////////////////////////   FILES
            $cant_files = $request->input("cant_files");

            for($i = 1; $i < $cant_files+1; $i++){
                $idg = 'file'.$i;
                $file = $request->file($idg);
                $idg = 'idFile'.$i;
                $idFile = $request->input($idg);
                $idg = 'title'.$i;
                $title = $request->input($idg);
                $idg = 'deleteFile'.$i;
                $deleteFile = $request->input($idg);
                if(!$idFile){
                    if($file){

                        $objGallery =(New Files())->storeFILE($file, $id, 1, $title);
                        try{
                        } catch ( \Exception $e ) {

                        }
                    };
                }else{
                    if($deleteFile){

                    };
                };

            }
            /////////////////////////////////////////////////////////////////


        return response()->json([
            'status' =>  $status,
            'message' =>  $mensaje,
            'datta' =>  $objCity
        ]);
    }















    public function destroy(string $id)
    {
        $objCity = Cities::find($id);
        $objCity->active = '0';
        $objCity -> save();

        return response()->json([
            'status' =>  200,
            'message' =>  'Delete'
        ]);
    }
}
