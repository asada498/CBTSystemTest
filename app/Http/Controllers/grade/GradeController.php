<?php

namespace App\Http\Controllers\grade;

use Auth;
use Session;
use Validator;
// use DB;
use App\Administrator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GradeCondition;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class GradeController extends Controller
{
    function checklogin(Request $request)
    {
        $password = Session::get('password');

        if($password=="aikkamata2255" or $password=="tokyo"){
            $list = [];
            return view('grade/gradeList', ['data' => $list, 'condition' => new GradeCondition('', '', '', '', '', 0)]);
            
        }else{
            return back()->with('error', '認証エラーです。ID、パスワードを確認してください。');
        }
    }   
}

?>
