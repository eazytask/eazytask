<?php

namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supervisor;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;
use Hash;
use App\Notifications\UserCredential;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //for user profile
    public function userProfile()
    {
        return view('pages.supervisor.profile');
    }
}
