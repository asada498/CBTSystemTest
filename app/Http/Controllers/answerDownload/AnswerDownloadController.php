<?php

namespace App\Http\Controllers\answerDownload;

use Auth;
use Session;
use Validator;
// use DB;
use App\Administrator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AnswerDownloadCondition;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class AnswerDownloadController extends Controller
{
    function checklogin(Request $request)
    {
        $password = Session::get('password');

        if($password=="aikkamata2255" or $password=="tokyo"){
            $list = [];
            return view('answerDownload/answerDownloadList', ['data' => $list, 'condition' => new AnswerDownloadCondition('', '', '', '', '', 0)]);
            
        }else{
            return back()->with('error', '認証エラーです。ID、パスワードを確認してください。');
        }
    }   
}

?>
