<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public   $rules = [

        'name' => 'required',
    ];
    protected $guarded = [
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'company'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //notification passing role
    public function admin_role()
    {
        return $this->belongsTo('App\Models\UserRole', 'id', 'user_id')
            ->whereIn('role', [2, 4, 5, 6, 7]);
    }

    public function user_roles()
    {
        return $this->hasMany('App\Models\UserRole')->orderBy('role', 'desc');
    }
    public function company_roles()
    {
        return $this->hasMany('App\Models\UserRole')
            ->orderBy('role', 'asc')
            ->where('company_code', auth()->user()->user_roles->unique('company_code')->sortByDesc('last_login')->first()->company_code);
    }

    public function getCompanyAttribute()
    {
        // return $this->belongsTo('App\Models\Company', 'id', 'user_id')
        //     ->where('id', auth()->user()->company_roles->first()->company_code);
        $item = Company::where('id', auth()->user()->company_roles->first()->company_code)->first();    
        return $item;
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'id', 'userID')->where([
            ['company', auth()->user()->company_roles->first()->company->id]
        ])->whereIn('role', [3,4,7]);
    }
    public function supervisor()
    {
        return $this->belongsTo('App\Models\Employee', 'id', 'userID')->where([
            ['company', auth()->user()->company_roles->first()->company->id],
            ['role', 4]
        ]);
    }

    public function firebase()
    {
        return $this->hasMany('App\Models\FirebaseToken', 'user_id', 'id');
    }

    // public function employee()
    // {
    //     return $this->belongsTo('App\Models\Employee', 'id', 'userID')->where([
    //         ['company',auth()->user()->company_roles->first()->company->sub_domain],
    //         ['role',auth()->user()->company_roles->first()->role]
    //     ]);
    // }
    // public function supervisor()
    // {
    //     return $this->belongsTo('App\Models\Supervisor', 'id', 'userID')->where([
    //         ['company',auth()->user()->company_roles->first()->company->sub_domain],
    //         ['role',auth()->user()->company_roles->first()->role]
    //     ]);
    // }
}
