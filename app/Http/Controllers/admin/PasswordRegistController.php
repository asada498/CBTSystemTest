<?php

namespace App\Http\Controllers\admin;

use Auth;
use Session;
use Validator;
// use DB;
use App\Administrator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class PasswordRegistController extends Controller
{
    function regist(Request $request)
    {
        $this->validate($request, ['password1' => 'required', 'password2' => 'required']);
     
        $password1 = $request->get('password1');
        //$password2 = $request->get('password2');
        Administrator::where('id', Session::get('examineeId'))->update(['password' => $password1]);

        return view('admin/adminMenu');    
    }   
}

?>
