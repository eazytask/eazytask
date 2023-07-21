<?php

use App\Models\UserCompliance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

function avoid_rejected_key($q){
    // $q->where('roaster_status_id','!=',Session::get('roaster_status')['Rejected']);
    // $q->where('roaster_type', 'Schedueled');
    // $q->where('sing_in', '!=', null);
    // $q->orWhere(function ($q) {
    //     $q->where('roaster_type', 'Unschedueled');
    // });
    // $q->orWhere(function ($q) {
    //     $q->where('roaster_status_id','!=', Session::get('roaster_status')['Rejected']);
    //     $q->where('roaster_type', 'Schedueled');
    //     $q->where('shift_end', '>=', Carbon::now());
    // });
}

# check employees expired license
function avoid_expired_license($q){
    if(Auth::user()->company->company_type->id==1){
        $q->where('license_expire_date','>=',Carbon::now()->toDateString());
        $q->where('first_aid_expire_date','>=',Carbon::now()->toDateString());
        $q->whereNotIn('userID',expired_comp_emp());
    }
}
# check employees expired license in left loin
function e_avoid_expired_license($q){
    if(Auth::user()->company->company_type->id==1){
        $q->where('e.license_expire_date','>=',Carbon::now()->toDateString());
        $q->where('e.first_aid_expire_date','>=',Carbon::now()->toDateString());
        $q->whereNotIn('e.userID',expired_comp_emp());
    }
}

#all expired compliance employee ids
function expired_comp_emp(){
    $user_comp = UserCompliance::where('expire_date','<',Carbon::now()->toDateString())->pluck('user_id')->unique();
    return $user_comp;
}

#firebase token message 
function push_notify($notiSubject,$notiBody,$role,$firebase,$type='',$id=''){
    if($firebase->count()){
        sendFirebasePushNotification($notiSubject,$notiBody,$role,$firebase->pluck(['token']),$type,$id);
    }
}

function sendFirebasePushNotification($notiSubject,$notiBody,$role,$tokens,$type='',$id=''){
    $roles=['2'=>'Admin','3'=>'User'];
    $url = 'https://fcm.googleapis.com/fcm/send';
        $data = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $notiSubject,
                "body" => $notiBody,
            ],
            "data" => [
                "role" => $role->id,
                "company" => strtoupper($role->company->company_code) .'-'. $roles[$role->role],
                "type" => $type,
                "id" => $id,
                "click_action"=> "FLUTTER_NOTIFICATION_CLICK"
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . env('FIREBASE_SERVER_KEY'),
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response
        // dd($result);
}