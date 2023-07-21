<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\Project;
use App\Models\RoasterStatus;
use App\Models\RoasterType;
use App\Models\TimeKeeper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Log;
use Session;

class PDFEmailController extends Controller
{
    // public function index(Request $request)
    // {

    public function emailpdf(Request $request){
        //dd($request->all());  //to check all the datas dumped from the form
        $from = $from_email= $reply_to_email= "admin@eazytask.au";
        $recipient_email =$to= $request->email;
        // the name of sender email
        $name = $request->name;
        //the tittle that gonna be displayed on email
        $subject = $request->subject;
        //message that gonna be send on email
        $message = $request->message;
        //file name
        $content = $request->pdfHtml;
        $fileName = $request->fileName;
        
        //validate form field for attaching the file
        if (empty($content)) {
            die("Upload error or No files uploaded");
        }
        
        //read from the uploaded file & base64_encode content
        // $handle = fopen($tmp_name, "r"); // set the file handle only for reading the file
        // $content = fread($handle, $size); // reading the file
        // fclose($handle); // close upon completion
        $type= "application/octet-stream";
        
        $imgData = str_replace(' ','+',$content);
        $imgData =  substr($imgData,strpos($imgData,",")+1);
        $fileData = base64_decode($imgData);
        
        // boundary
        $boundary = uniqid();
        
        $encoded_content = chunk_split(base64_encode($fileData));
        $boundary = md5("random"); // define boundary with a md5 hashed value
        
        //header
        $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
        $headers .= "From:" . $from_email . "\r\n"; // Sender Email
        $headers .= "Reply-To: " . $reply_to_email . "\r\n"; // Email address to reach back
        $headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
        $headers .= "boundary = $boundary\r\n"; //Defining the Boundary
        
        //plain text
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($message));

        $name = !empty($fileName) ? $fileName : "report.pdf";
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $type; name=" . $name . "\r\n";
        $body .= "Content-Disposition: attachment; filename=" . $name . "\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
        $body .= $encoded_content; // Attaching the encoded file with email
        
        
        // send email
        $sentMailResult=mail($to, $subject, $body, $headers);
        if ($sentMailResult) {
            return response()->json(['status'=> "Mail sent successfully."]);
        } else {
          return response()->json(['status'=> "Sorry, the email could not be sent.Please go back and try again!"]);
        }
        
        
        
        //return Redirect::back()->with('message','Operation Successful !');
        //return view('pages.Admin.pdf.all_report', compact('employees', 'projects', 'clients', 'timekeepers','job_types','roaster_status'));
    }

}
