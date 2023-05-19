<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Validator;
// use DB;
use Mail;
use App\Administrator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class MailController extends Controller
{
    function sendmail(Request $request)
    {
        $address = $request->get('address');

        // mail
        $this->mail($address);

        return view('/mailend');
    }

    function mail($toEmail){

        $body = "うけつけを　かいしします。いかに　アクセスして　ください。\n";
        $body .= "Reception will start. Please access to following.\n";
        $body .= "http://www.senmonkyouiku.com:4000?address=".$toEmail."\n";
        try {
            Mail::send([], [],
                function ($message) use ($body, $toEmail) {
                    $message->from('asada@senmonkyouiku.co.jp', 'SENMON KYOUIKU');
                    $message->to($toEmail)->subject('うけつけを　かいしします。Reception will start.');
                    $message->setBody($body);
                });
        } catch (\Throwable $e) {
            // 無視する ignore
        }
    }
}

?>
