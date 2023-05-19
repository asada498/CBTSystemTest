<?php

namespace App\Http\Controllers\admin;

use Auth;
use Session;
use Validator;
use DB;
use App\SiteRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SearchCondition;
use Illuminate\Support\Facades\Config;

class AdminMenuController extends Controller
{
    function menuPush(Request $request)
    {
        $mode = $request->get('mode');

        switch($mode){
        case 'edit':
            $list = [];
            return view('admin/examineeList', ['data' => $list, 'condition' => new SearchCondition('', '', '', '', '', '', '', '', '', '', '', 0)]);
        case 'password':
            return view('admin/passwordChange');
        case 'sitepassword':
            return view('admin/sitePasswordChange', ['data' => $this->siteSearch()]);
        }
    }

    public function siteSearch()
    {
        $list = DB::table('test_site_information as a')
        ->where('a.usable', '=', '1')
        ->join('site_administrator as b', 'a.test_site', '=', 'b.test_site')
        ->select('a.test_site', 'a.country', 'a.city', 'b.password')
        ->orderBy('a.test_site')
        ->get();
        $ret = [];
        foreach($list as $ele)
        {
            $record = new SiteRecord($ele->test_site, $ele->country, $ele->city, $ele->password);
            array_push($ret, $record);
        }
        return $ret;
    }
}

?>
