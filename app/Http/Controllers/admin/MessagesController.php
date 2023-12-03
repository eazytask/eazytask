<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\Project;
use App\Models\TimeKeeper;
use App\Models\Message;
use App\Models\MessageReply;
use App\Models\MessageConfirm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MessagesController extends Controller
{
    public function index()
    {
        $projects = Project::where('company_code', Auth::user()->company_roles->first()->company->id)->orderBy('pName', 'asc')->get();

        $id_projects = clone $projects;
        $id_projects = $id_projects->pluck('id')->toArray();

        $messages = Message::with('replies', 'confirms')->orderBy('created_at', 'DESC')->get();
        foreach ($messages as $key => $message) {
            if (empty(array_diff($message->list_venue, ["all"]))) {
                // Thats all
            }elseif(!empty(array_diff($message->list_venue, $id_projects))) {
                unset($messages[$key]);
            }
            // Access message properties
            $message->purposes = $message->getListVenue();
            $message->replies = $message->replies;
            $message->confirms = $message->confirms;
            if($message->need_confirm == 'Y') {
                $message->my_confirm = MessageConfirm::where('message_id', $message->id)->where('user_id', Auth::user()->id)->count() > 0;
            }else{
                $message->my_confirm = false;
            }
        }
        
        $messages = array_values($messages->toArray()); // 'reindex' array

        return view('pages.Admin.messages.index', compact('messages', 'projects'));
    }

    public function store(Request $request)
    {
        if (empty(array_diff($request->list_venue, ["all"]))) {
            $request->list_venue = Project::where('company_code', Auth::user()->company_roles->first()->company->id)->orderBy('pName', 'asc')->get()->pluck('id')->toArray();
        }
        
        Message::create([
            'user_id' => Auth::user()->id,
            'heading' => $request->heading,
            'text' => $request->text,
            'need_confirm' => $request->need_confirm,
            'published' => 'Y',
            'publish_date' => date('Y-m-d'),
            'list_venue' => $request->list_venue,
        ]);

        return back()->with('message','Success Post Message!');
    }

    public function storeReply(Request $request)
    {
        MessageReply::create([
            'user_id' => Auth::user()->id,
            'message_id' => $request->message_id,
            'text' => $request->text,
            'published' => 'Y',
            'publish_date' => date('Y-m-d'),
        ]);

        return back()->with('message','Success Reply Message!');
    }

    public function confirm(Request $request)
    {
        MessageConfirm::create([
            'user_id' => Auth::user()->id,
            'message_id' => $request->message_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Success confirm!']);
    }

    public function unconfirm(Request $request)
    {
        MessageConfirm::where('user_id', Auth::user()->id)->where('message_id', $request->message_id)->delete();

        return response()->json(['success' => true, 'message' => 'Success unconfirm!']);
    }
    
    public function update(Request $request)
    {
        $message = Message::where('id', $request->message_id)->first();

        if (!empty($request->list_venue)) {
            if (empty(array_diff($request->list_venue, ["all"]))) {
                $request->list_venue = Project::where('company_code', Auth::user()->company_roles->first()->company->id)->orderBy('pName', 'asc')->get()->pluck('id')->toArray();
            }
            
            $message->list_venue = $request->list_venue;
        }
        
        $message->heading = $request->heading;
        $message->text = $request->text;
        $message->need_confirm = $request->need_confirm;
        
        $message->save();        

        return back()->with('message','Success Update Post Message!');
    }

    public function destroy(Request $request) 
    {
        Message::where('id', $request->message_id)->delete();

        return back()->with('message','Success Delete Post Message!');
    }

    public function updateReply(Request $request)
    {
        $message = MessageReply::where('id', $request->message_id)->first();
        $message->text = $request->text;
        
        $message->save();        

        return back()->with('message','Success Update Reply Message!');
    }

    public function destroyReply(Request $request) 
    {
        MessageReply::where('id', $request->message_id)->delete();

        return back()->with('message','Success Delete Reply Message!');
    }
}
