<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use Auth;
use Illuminate\Support\Facades\Storage;
use Image;

class ClientController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  //Employee View File
  public function index($id)
  {
    return view('pages.Admin.client.index');
  }

  public function fetch(Request $request)
  {
    $clients = Client::where('company_code', Auth::user()->company_roles->first()->company->id)->orderBy('cName', 'asc')
    ->when($request->status != "" && $request->status != 0 && $request->status != null, function($q) use ($request) {
      $q->where('status', $request->status);
    })->get();

    $html = '';
    if (count($clients) == 0) {
      $html .= '<tr class="">' .
      '<td class="text-center" colspan="7">No data found!</td>' .
      '</tr>';
    }else{
      foreach ($clients as $loop => $row) {
        if (!$row->cimage) {
          $row->cimage = 'images/app/no-image.png';
        }
        $json = json_encode($row->toArray(), false);
  
        if ($row->status == 1) {
          $status = "<span class='badge badge-pill badge-light-success mr-1'>Active</span>";
        } else {
          $status = "<span class='badge badge-pill badge-light-danger mr-1'>Inactive</span>";
        }
  
        $no_of_sites = Project::where('clientName', $row->id)->count();
  
        // <td>
        // <div class='avatar bg-light-primary'>
        //     <div class='avatar-content'>
        //         <img src='" . 'https://api.eazytask.au/' . $row->cimage . "' alt='' height='32px' width='32px'>
        //     </div>
        // </div>
        // <td>$row->caddress</td>
        // <td>$row->cstate</td>
        // <td>$row->cpostal_code</td>
  
        $html .= "
              <tr>
                  <td>". $loop + 1 ."</td>
                  <td>$row->cname</td>
                  <td>$row->cperson</td>
                  <td>$row->cnumber</td>
                  <td>$row->cemail</td>
                  <td>$no_of_sites</td>
                  <td>
                    <div class='dropdown'>
                      <button class='btn btn-soft-info btn-sm' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                        <i class='ri-more-2-fill'></i>
                      </button>
                      <ul class='dropdown-menu'>
                        <li><button data-row='$json'  data-bs-toggle='modal' data-bs-target='#showModal' class='edit-btn dropdown-item' href='#'>Edit</button></li>
                        <li><a class='dropdown-item del' url='/admin/home/client/delete/$row->id' data-id='$row->id'>Delete</a></li>
                      </ul>
                    </div>
                  </td>
              </tr>
              ";
      }
    }
    return response()->json(['data' => $html]);
    // // return view('pages.Admin.client.index', compact('clients'));
    // <button class='edit-btn btn btn-gradient-primary mb-25' data-row='$json'><i data-feather='edit'></i></button>
    // <a class='btn btn-gradient-danger text-white del' url='/admin/home/client/delete/$row->id' data-id='$row->id'><i data-feather='trash-2'></i></a>

  }

  public function store(Request $request)
  {
    $client = new Client;
    $client->user_id = Auth::id();
    $client->cname = $request->cname;
    $client->cemail = $request->cemail;
    $client->cnumber = $request->cnumber;
    $client->caddress = $request->caddress;
    $client->suburb = $request->suburb;
    $client->cstate = $request->cstate;
    $client->status = $request->status;
    $client->cpostal_code = $request->cpostal_code;
    $client->cperson = $request->cperson;
    $client->company_code = Auth::user()->company_roles->first()->company->id;

    $image = $request->file;
    $filename = null;
    if ($image) {
      $basePath = "/home/eazytask-api/htdocs/www.api.eazytask.au/public/";
      $folderPath = "images/clients/";
      $image_parts = explode(";base64,", $image);
      $image_type_aux = explode("image/", $image_parts[0]);
      $image_type = $image_type_aux[1];
      $image_base64 = base64_decode($image_parts[1]);
      $img_name = date('sihdmy') . $image_type;
      $filename = $folderPath . $img_name;
      Image::make($image_base64)->save($basePath . $filename);
    }
    $client->cimage = $filename;
    $client->save();

    return response()->json([
      'message' => 'Client Added Successfully.',
      'alertType' => 'success'
    ]);
  }
  public function update(Request $request)
  {
    // return $request;
    $client = Client::find($request->id);
    $client->user_id = Auth::id();
    $client->cname = $request->cname;
    $client->cemail = $request->cemail;
    $client->cnumber = $request->cnumber;
    $client->caddress = $request->caddress;
    $client->suburb = $request->suburb;
    $client->cstate = $request->cstate;
    $client->status = $request->status;
    $client->cpostal_code = $request->cpostal_code;
    $client->cperson = $request->cperson;

    $img = $request->file;
    $filename = null;
    if ($img) {
      // $img_name = date('sihdmy');
      // $ext = strtolower($img->getClientOriginalExtension());
      // $full_name = $img_name . '.' . $ext;
      // $folderPath = "images/clients/";
      // $filename = $folderPath . $full_name;


      $basePath = "/home/eazytask-api/htdocs/www.api.eazytask.au/public/";
      $folderPath = "images/clients/";
      $image_parts = explode(";base64,", $img);
      $image_type_aux = explode("image/", $image_parts[0]);
      $image_type = $image_type_aux[1];
      $image_base64 = base64_decode($image_parts[1]);
      $img_name = date('sihdmy') . $image_type;

      $filename = $folderPath . $img_name;
      try {
        unlink($basePath . $client->cimage);
      } catch (\Throwable $e) {
      }

      // $img->move($folderPath, $full_name);
      Image::make($image_base64)->save($basePath . $filename);
      $filename = $filename;
    }
    if ($filename) {
      $client->cimage = $filename;
    }

    $client->save();
    return response()->json([
      'message' => 'Client Updated Successfully.',
      'alertType' => 'success'
    ]);
  }
  public function delete($id)
  {
    try {
      $client = Client::find($id);

      $basePath = "/home/eazytask-api/htdocs/www.api.eazytask.au/public/";

      try {
        unlink($basePath . $client->cimage);
      } catch (\Throwable $e) {
      }

      $client->delete();
      return response()->json([
        'message' => 'Client deleted successfully.',
        'alertType' => 'success'
      ]);
    } catch (\Illuminate\Database\QueryException $e) {
      // var_dump($e->errorInfo);
      return response()->json([
        'message' => 'Sorry! This client used somewhere.',
        'alertType' => 'warning'
      ]);
    }
  }
}
