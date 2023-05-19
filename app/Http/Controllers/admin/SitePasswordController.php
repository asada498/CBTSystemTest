<?php

namespace App\Http\Controllers\admin;

use Auth;
use Session;
use Validator;
use DB;
use App\SiteAdministrator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class SitePasswordController extends Controller
{
    public function regist(Request $request)
    {
        $testSite = $request->get('testSite');
        $password = $request->get('password');

        $size = count($testSite);
        for($index = 0; $index < $size; $index++){
            SiteAdministrator::where('test_site', $testSite[$index])
                ->update(['password' => $password[$index]]);
        }

        return view('admin/adminMenu');
    }
}

?>