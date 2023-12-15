<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cities;
use App\Models\Images;
use App\Models\Links;
use App\Models\Files;
use App\Models\Banners;
use App\Models\continent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;


class CitiesContoller extends Controller
{

    public function resise(Request $request){
        $file = $request->file("photo");
        Log::info($file);
        Log::info("#0::");

        //VERIFICO SI exite el DIRECTORIO y si no --> CREA
        if(!is_dir('storage/images/cities/')){
            @mkdir('storage/images/cities/', 0777);
        }else{
            Log::info("Ya existe ese directorio\n");
        }

        // create image manager with desired driver
        $manager = ImageManager::gd();
        //$manager = ImageManager::imagick();
        //$manager = new ImageManager(new Driver());

        Log::info("#1->");
        Log::info(  $file->getPathName()  );
        echo '-->imagen:: ';
        echo $file->getPathName();
        echo ':::##';
        ///echo file_get_contents($file);
        Log::info("#2_");
        //echo phpinfo();
        // open an image file
        //http://db.walook.com.mx:8033/storage/images/gallery/VeQe17ZIdL0DWZJVyP7vFFXTHzRfUNExThgH2NvT.jpg
        //$image = $manager->read('http://db.walook.com.mx:8033/storage/images/gallery/VeQe17ZIdL0DWZJVyP7vFFXTHzRfUNExThgH2NvT.jpg');
        $image = $manager->read(        $request->file("photo")     );
        Log::info("#3");
        // resize image instance
        $image->resize(height: 300);
        Log::info("#4");

        // insert a watermark
        //$image->place('images/watermark.png');

        // encode edited image
        $encoded = $image->toJpg();
        Log::info("#5");

        // save encoded image
        $encoded->save('storage/images/cities/example.jpg');

        Log::info("#terminoooo");
        //*/
    }


    public function citiesStore(Request $request){
        //Log::info("Guarda City");
        //Log::info($request);

        $objCity = (New Cities())->store($request);

        $objBanners = (New Banners())->list(1, 0);
        return response()->json([
            'cities' => $objCity
        ]);
    }


    public function citiesUpdate(Request $request){
        //Log::info("Guarda City");
        //Log::info($request);

        $objCity = (New Cities())->citiesUpdate($request, 'base');

        $objBanners = (New Banners())->list(1, 0);
        return response()->json([
            'cities' => $objCity
        ]);
    }




    public function list(Request $request){
        $cantItems = 20;
        $paginator = 1;
        $page = $request->page;


        if($request->search){
            $objCities =(New Cities())->searchList($request->search, $page,$cantItems);
            $citiesTotal =(New Cities())->searchList($request->search, 1, 999999999999999999);
        }else{
            $objCities =(New Cities())->list($page, $cantItems);
            $citiesTotal =(New Cities())->list(1, 999999999999999999);
        };

        $total = count($citiesTotal);
        if($total > $cantItems){
            $division = $total / $cantItems;
            $paginator = intval($division);
            if($paginator < $division){
                $paginator = $paginator +1;
            };
        };


        $objBanners = (New Banners())->list(1, 0);
        $objContinent = (New continent())->list();

        return response()->json([
            'bannerCities' => $objBanners,
            'cities' => $objCities,
            'continents' => $objContinent,
            'tot' => $total,
            'paginator' => $paginator
        ]);
    }

    public function find($cityId){

        $objCity = (New Cities())->serch($cityId);
        $objgallery = (New Images())->list(1, $cityId);
        $objLinks = (New Links())->list(1, $cityId);
        $objFiles = (New Files())->list(1, $cityId);
        $objBanners = (New Banners())->list(1, $cityId);
        $objContinent = (New continent())->list();

        return response()->json([
            'cities' => $objCity,
            'gallery' => $objgallery,
            'links' => $objLinks,
            'files' => $objFiles,
            'bannerCities' => $objBanners,
            'continents' => $objContinent
        ]);
    }


    public function delete($id){
        $status = 400;$mess = 'ok';
        try{
            $objCity = Cities::find($id);
            $objCity->active = 0;
            $objCity->updated_at = date("Y-m-d H:i:s");
            $objCity -> save();
        } catch ( \Exception $e ) {
            Log::info($e);
            $status = 400;$mess="Error";
        };
        return response()->json([
            'status' => $status,
            'message' => $mess
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
                    //$photo =  $request->file("photo")->store('public/images/cities');
                    //$photo = str_replace('public/', 'storage/', $photo);

                    $photo = (New Images())->storeResize($request->file("photo"), '756', '456', 'cities');
                } catch ( \Exception $e ) {

                }
            };

            $logo = '';
            if($request->file("logo")){
                try{
                    $request->validate ([
                        'logo' => 'image|max:50000'
                    ]);
                    //$logo =  $request->file("logo")->store('public/images/cities');
                    //$logo = str_replace('public/', 'storage/', $logo);
                    $logo = (New Images())->storeResize($request->file("logo"), '617', '312', 'cities');
                } catch ( \Exception $e ) {

                }
            };

            $banner = '';
            if($request->file("banner")){
                try{
                    $request->validate ([
                        'banner' => 'image|max:50000'
                    ]);
                    //$banner =  $request->file("logo")->store('public/images/cities');
                    //$banner = str_replace('public/', 'storage/', $banner);
                    $banner = (New Images())->storeResize($request->file("banner"), '1920', '480', 'cities');
                } catch ( \Exception $e ) {

                }
            };

            $objCity=[];
            try{
                $request->validate ([
                    'name' => 'required|string'
                ]);
                Log::info("description:: - ::");
                Log::info($request->input("description"));

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
                Log::info($objCity);
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
                //Log::info("ID IMAGE: ".$idImage);
                //Log::info("DEL IMAGE: ".$deleteImage);

                if(!$idImage){
                    if(!$deleteImage){
                        if($image){

                            try{
                                $objGallery =(New Images())->storeIMG($image, $id, 1);
                            } catch ( \Exception $e ) {

                            }
                        };
                    };
                }else{
                    if($deleteImage){
                        $obsDEL = Images::find($idImage);
                        $obsDEL->active = 2;
                        $obsDEL->updated_at = date("Y-m-d H:i:s");
                        $obsDEL -> save();
                        //Log::info("IMAGEN Borrada");
                        //Log::info($obsDEL);
                    };
                };

            }
            /////////////////////////////   LINKS
            $cant_links = $request->input("cant_links");
            Log::info("--->CANT LINKS: ".$cant_links);

            for($i = 1; $i < $cant_links+1; $i++){
                $idg = 'link'.$i;
                $link = $request->input($idg);
                $idg = 'titleLink'.$i;
                $titleLink = $request->input($idg);
                $idg = 'idLink'.$i;
                $idLink = $request->input($idg);
                $idg = 'deleteLink'.$i;
                $deleteLink = $request->input($idg);
                if(!$idLink){
                    if($link){
                        try{
                            if(!$deleteLink){
                                $objGallery =(New Links())->storeLINK($link, $titleLink, $id, 1);
                            }else{Log::info("#3.b");};
                        } catch ( \Exception $e ) {
                        }
                    };
                }else{
                    $objLink = Links::find($idLink);
                    if($deleteLink){
                        $objLink->active = 2;
                    }else{
                        $objLink->title = $titleLink;
                        $objLink->image = $link;
                    };
                    $objLink->updated_at = date("Y-m-d H:i:s");
                    $objLink -> save();
                };

            }



            /////////////////////////////   FILES
            $cant_files = $request->input("cant_files");
            //Log::info("-->CANT FILES -->".$cant_files);


            for($i = 1; $i < $cant_files+1; $i++){
                //Log::info("#:1");
                $idg = 'file'.$i;
                $file = $request->file($idg);
                $idg = 'idFile'.$i;
                $idFile = $request->input($idg);
                $idg = 'title'.$i;
                $title = $request->input($idg);
                $idg = 'deleteFile'.$i;
                $deleteFile = $request->input($idg);
                //Log::info("-->ID:: ".$idFile);
                if($idFile){
                    //Log::info("Modifica::");
                    $objLink = Files::find($idFile);
                    if($deleteFile){

                    //Log::info("->HAY DEL");
                        $objLink->active = 2;
                    }else{
                        //Log::info("------------->HABILITA");
                        $objLink->title = $title;
                        $objLink->active = 1;
                    };
                    $objLink->updated_at = date("Y-m-d H:i:s");
                    $objLink -> save();
                    //Log::info($objLink);
                }else{
                    //Log::info("#:nop");
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
