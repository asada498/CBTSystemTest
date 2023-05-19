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
use App\User;
use Redirect;

class AdminController extends Controller
{
    function checklogin(Request $request)
    {
        $this->validate($request, ['id' => 'required', 'password' => 'required']);
     
        $id = $request->get('id');
        $password = $request->get('password');

        $admin = Administrator::where('id', $id)->where('password', $password)->first();

        if($admin == null){
            return back()->with('error', '認証エラーです。ID、パスワードを確認してください。');
        }else{
            $request->session()->put('login', true);
            $request->session()->put('examineeId', $id);
            $request->session()->put('editable', $admin['editable']);
            $request->session()->put('payment_editable', $admin['payment_editable']);
            return view('admin/adminMenu');
        }
    }

    public function Excelinfo($city)
    {
        try {        
            $User = User::where('email', $city)->first();
            return Redirect::to($User->link);
          
          } catch (\Exception $e) {
          
              return $e->getMessage();
          }
    }
    
    
}

?>
