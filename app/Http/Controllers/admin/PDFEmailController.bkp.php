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
use Session;

class PDFEmailController extends Controller
{
    // public function index(Request $request)
    // {

    public function emailpdf(Request $request){
        //dd($request->all());  //to check all the datas dumped from the form
      //if your want to get single element,someName in this case
      //$someName = $request->someName; 
      
      
       //$Name = $request->name;
       ///$Subject = $request->subject; 
       //$Email = $request->email; 
       //$Message = $request->message; 

      $sender = "sweeper83@yahoo.com";
      $recipient = $request->email;
      // the name of sender email
      $name = $request->name;
      //the tittle that gonna be displayed on email
      $subject = $request->subject;
      //message that gonna be send on email
      $message = $request->message;
      //file name
      $attachment = $request->myFile;

       //validate form field for attaching the file
      if ($error > 0) {
        die("Upload error or No files uploaded");
      }

      //read from the uploaded file & base64_encode content
      $handle = fopen($tmp_name, "r"); // set the file handle only for reading the file
      $content = fread($handle, $size); // reading the file
      fclose($handle); // close upon completion

      $encoded_content = chunk_split(base64_encode($content));
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

      //attachment
      $body .= "--$boundary\r\n";
      $body .= "Content-Type: $type; name=" . $name . "\r\n";
      $body .= "Content-Disposition: attachment; filename=" . $name . "\r\n";
      $body .= "Content-Transfer-Encoding: base64\r\n";
      $body .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
      $body .= $encoded_content; // Attaching the encoded file with email

      $sentMailResult = mail($recipient_email, $subject, $body, $headers);

      if ($sentMailResult) {
        echo "<h3>File Sent Successfully.<h3>";
        // unlink($name); // delete the file after attachment sent.
      } else {
          die("Sorry but the email could not be sent.
                    Please go back and try again!");
      }
        
       return Redirect::back()->with('message','Operation Successful !');
      //return view('pages.Admin.pdf.all_report', compact('employees', 'projects', 'clients', 'timekeepers','job_types','roaster_status'));
    }

}
