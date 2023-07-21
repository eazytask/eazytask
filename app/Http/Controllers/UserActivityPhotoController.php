<?php

namespace App\Http\Controllers;

use App\Models\UserActivityPhoto;
use Carbon\Carbon;
use Image;

class UserActivityPhotoController extends Controller
{
    public function store($userImage,$timekeeper_id,$sign='sign_in'){
        try{
            $folderPath="images/users/activity/";
            $basePath = "/home/eklaw543/api.eazytask.au/public/";
    
            $image_parts = explode(";base64,", $userImage); 
            $image_type_aux = explode("image/", $image_parts[0]); 
            $image_type = $image_type_aux[1]; 
            $image_base64 = base64_decode($image_parts[1]); 
    
            $fileName = $timekeeper_id . '_sign_in_' . Carbon::now()->format('sihdmy.') . $image_type; 
    
            $image_url = $folderPath . $fileName; 
    
            $user_activity= UserActivityPhoto::where('timekeeper_id',$timekeeper_id)->first();
    
            if(!$user_activity){
                $user_activity= new UserActivityPhoto;
            }
    
            $user_activity->timekeeper_id = $timekeeper_id;
            $user_activity->$sign = $image_url;
            if($user_activity->save()){
                Image::make($image_base64)->save($basePath.$image_url);
            }
        } catch (\Throwable $e) {
            
        }
    }
    
    public function storeLocalImage($userImage,$timekeeper_id,$sign='sign_in'){
        $ext=strtolower($userImage->getClientOriginalExtension());
        $basePath = "/home/eklaw543/api.eazytask.au/public/";
        $folderPath="images/users/activity/";
        $img_name=date('sihdmy');
        $full_name= $img_name.'.'.$ext;
        $image_url=$folderPath.$full_name;

        $user_activity= UserActivityPhoto::where('timekeeper_id',$timekeeper_id)->first();
        if(!$user_activity){
            $user_activity= new UserActivityPhoto;
        }

        $user_activity->timekeeper_id = $timekeeper_id;
        $user_activity->$sign = $image_url;
        if($user_activity->save()){
            $userImage->move($basePath.$folderPath,$full_name);
        }
    }
}
