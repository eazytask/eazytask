<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Company;

class SwitchCompanyController extends Controller
{
    public function switch($company_id)
    {
        $company = Company::find($company_id);

        if ($company) {
            $user_id = auth()->user()->id;

            $all_roles = auth()->user()->user_roles->unique('company_code')->sortByDesc('role');
            $define_last = true;
            foreach($all_roles as $role){
                if($role->company_code  == $company->id && $define_last){
                    $last_login=1;
                    $define_last = false;
                }else{
                    $last_login=0;
                }
                $role->last_login = $last_login;
                $role->save();
            }
            if(!$company->sub_domain){
                return redirect('/');
            }else{
                // Auth::logout();
                // return redirect('http://localhost:8888/autologin?id=' . $user_id . '&api_token=token');
                return redirect('https://' . $company->sub_domain . '.easytask.com.au/autologin?id=' . $user_id . '&api_token=token');
            }
            
        }
        return redirect()->back();
    }
}
