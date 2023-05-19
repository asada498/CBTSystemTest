<?php

namespace App\Http\Controllers\admin;

use Auth;
use Session;
use Validator;
// use DB;
use App\SiteAdministrator;
use App\TestSiteInformation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Test;

class SupervisorController extends Controller
{
    function checklogin(Request $request)
    {
        $this->validate($request, ['country' => 'required', 'city' => 'required', 'password' => 'required']);
        $country = $request->get('country');
        $city = $request->get('city');
        $password = $request->get('password');

        $info = TestSiteInformation::where('country', $country)->where('city', $city)->first();
        $testSite = $info['test_site'];
        $admin = SiteAdministrator::where('test_site', $testSite)->where('password', $password)->first();

        if($admin == null){
            return back()->with('error', 'Authentication Error');
        }else{
            $request->session()->put('login', true);
            $request->session()->put('testSite', $testSite);

            return view('admin/supervisorMenu');
        }
    }   
}

?>