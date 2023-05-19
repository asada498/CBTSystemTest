<?php

namespace App\Http\Controllers\admin;

use Auth;
use Session;
use Validator;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ExamCondition;
use App\TestSiteInformation;
use App\User;
use Illuminate\Support\Facades\Config;

class SupervisorMenuController extends Controller
{
    function menuPush(Request $request)
    {
        $city = Session::get('password');
        $user = User::where('email', $city)->first();
        $testSite = $user->test_site;
        //dd($testSite);

        $info = TestSiteInformation::where('test_site', $testSite)->first();

        $list = [];
        return view('admin/examProgress', ['list' => $list, 'condition' => new ExamCondition($info['country'], $info['city'], date("Y-m-d"), '')]);

        
    }

    public function Get_city()
    {
        // try {
            $country = $_GET['country'];

            $html = "<option>Saaalom 1</option>";   
            
            $cities = TestSiteInformation::where('country', $country)->get();
            
            $html = "";
            foreach($cities as $city)
            {
                $strCity =  str_replace(" ","_",$city->city); 
                $html = $html."<option value = ".$strCity.">".$city->city."</option>"; 
            }

            
            $data = [
                'message'=>'succees',
                'html'=>  $html
            ];
            
            return  $data;
          
        //   } catch (\Exception $e) {
          
        //       return $e->getMessage();
        //   }
    }
}
?>
