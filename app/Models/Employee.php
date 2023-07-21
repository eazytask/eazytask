<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;

class Employee extends Model
{
    use HasFactory,LogsActivity;
      protected $guarded = [];

      public function getActivitylogOptions(): LogOptions
      {
        return LogOptions::defaults()
          ->setDescriptionForEvent(fn (string $eventName) => "Employee has been {$eventName}")
          ->useLogName(auth()->user()->company_roles->first()->company_code)
        ->logAll()
        ->logExcept(['updated_at','created_at','id','company','user_id','userID'])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
      }

      //notification passing role
    public function employee_role()
    {
        return $this->belongsTo('App\Models\UserRole', 'userID', 'user_id')
        ->where('role',3);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'userID', 'id');
    }
    
    public function admin()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function compliances()
    {
        return $this->hasMany('App\Models\UserCompliance', 'user_id', 'userID');
    }

    public function firebase()
    {
        return $this->hasMany('App\Models\FirebaseToken','user_id','userID');
    }
}
