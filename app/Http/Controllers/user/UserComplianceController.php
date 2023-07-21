<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Compliance;
use App\Models\UserCompliance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserComplianceController extends Controller
{
    public function index()
    {
        $compliances = Compliance::get();
        return view('pages.User.compliance.index', compact('compliances'));
    }

    public function fetch()
    {
        $compliances = UserCompliance::where('user_id',Auth::id())->orderBy('expire_date', 'asc')->get();

        $html = '';
        foreach ($compliances as $loop => $row) {
            $json = json_encode($row->toArray(), false);
            $html .= "
            <tr>
                            <td>" . $loop + 1 . "</td>
                            <td>".$row->compliance->name."</td>
                            <td>$row->certificate_no</td>
                              <td>$row->expire_date</td>
                            <td>$row->comment</td>
                              <td>
                                    <button class='edit-btn btn btn-gradient-primary mb-25' data-row='$json'><i data-feather='edit'></i></button>
                                    <a class='btn btn-gradient-danger text-white del' data-id='$row->id'><i data-feather='trash-2'></i></a>
                              </td>
                          </tr>
            ";
        }
        return response()->json(['data' => $html]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'compliance_id' => 'required',
            'certificate_no' => 'required',
            'expire_date' => 'required'
        ]);
        if ($validator->fails())
            return $validator->errors();
        
        try {
            $exist_comp = UserCompliance::where([
                ['user_id', Auth::id()],
                ['compliance_id', $request->compliance_id]
            ])->first();

            if (!$exist_comp) {
                $user_compliance = new UserCompliance;
                $user_compliance->user_id = Auth::id();
                $user_compliance->email = Auth::user()->email;
                $user_compliance->compliance_id = $request->compliance_id;
                $user_compliance->certificate_no = $request->certificate_no;
                $user_compliance->comment = $request->comment;
                $user_compliance->expire_date = Carbon::parse($request->expire_date);

                $exist_comp = $user_compliance->save();
            } else {
                $exist_comp->id = $exist_comp->id;
                $exist_comp->certificate_no = $request->certificate_no;
                $exist_comp->comment = $request->comment;
                $exist_comp->expire_date = Carbon::parse($request->expire_date);
                $exist_comp->save();
            }
            
            return response()->json([
              'message' => 'compliance updated successfully.',
              'alertType' => 'success'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
              'message' => $e->getMessage(),
              'alertType' => 'warning'
            ]);
        }
    }

    public function distroy($id)
    {
        try {
            $exist_comp = UserCompliance::find($id);
            if ($exist_comp) {
                $exist_comp->delete();
                return response()->json([
                  'message' => 'compliance deleted successfully.',
                  'alertType' => 'success'
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
              'message' => 'something went wrong!',
              'alertType' => 'warning'
            ]);
        }
    }
}
