<?php
namespace App\Http\Helpers;

Class Common{

    public static function getImagePath($image,$type){
        if(!empty($image) && !empty($type)){
            if($type == 'blog_image'){
                return env('APP_URL').'/public/uploads/Blog/'.$image;
              }
        }
    }
}
?>