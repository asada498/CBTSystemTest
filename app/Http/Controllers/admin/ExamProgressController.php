<?php

namespace App\Http\Controllers\admin;

use Auth;
use Session;
use Validator;
use DB;
use App\ExamineeLogin;
use App\ExamineeList;
use App\ProgressRecord;
use App\ExamCondition;
use App\User;
use App\Grades;
use App\TestSiteInformation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ExamProgressController extends Controller
{
    function search(Request $request)
    {
        $password = Session::get('password');
       
        $country = $request->get('country');
        $city = str_replace("_"," ",$request->get('city'));
        $testDay = $request->get('testDay');
        $id = $request->get('uid');

        $user = User::where('city', $city)->first();
        
        //dd($country, $city, $testDay, $id, $user);

        $condition = new ExamCondition($country, $city, $testDay, $id);

        // $array = ExamineeList::where('test_site', $testSite)->where('test_day', $testDay)->orderBy('examinee_id')->get();
        if(($password=="aikkamata2255" or $password=="tokyo") and $country=="0")
        {
            $array = ExamineeList::where('examinee_id', $id)->get();
        }
        else
        {
            $testSite = $user->test_site;
            $array = ExamineeList::where('test_site', $testSite)->where('examinee_id', $id)->get();
        }
        

        $list = [];
        foreach($array as $ele){
            $time = null;
            switch($ele['time']){
            case 1:
                $time = '09-12';
                break;
            case 2:
                $time = '12-15';
                break;
            case 3:
                $time = '15-18';
                break;
            }
            $examineeLogin = ExamineeLogin::where('examinee_id', $ele['examinee_id'])->first();
            if($examineeLogin == null){
                $login = '-';
                $progress = '-';
                $reStart = '-----';
                $reStartDisabled = 'disabled';
                $cheatDisabled = '';
                $reason = '';
            }else{
                if($examineeLogin['login'] == 1){
                    $login = 'LOGIN';
                }else{
                    $login = 'LOGOFF';
                }
                switch($examineeLogin['progress']){
                case 1:
                    $progress = 'SEC-1 START';
                    $reStart = 'RE-START FROM SEC-1';
                    $reStartDisabled = '';
                    $cheatDisabled = '';
                    break;
                case 2:
                    $progress = 'SEC-2 START';
                    $reStart = 'RE-START FROM SEC-2';
                    $reStartDisabled = '';
                    $cheatDisabled = '';
                    break;
                case 3:
                    $progress = 'SEC-3 START';
                    $reStart = 'RE-START FROM SEC-3';
                    $reStartDisabled = '';
                    $cheatDisabled = '';
                    break;
                case 4:
                    $progress = 'EXAM END';
                    $reStart = '-----';
                    $reStartDisabled = 'disabled';
                    $cheatDisabled = '';
                    break;
                case 9:
                    $progress = 'CHEAT';
                    $reStart = '-----';
                    $reStartDisabled = 'disabled';
                    $cheatDisabled = 'disabled';
                    break;
                }
               
                $reason = $examineeLogin['reason'];
            }

            $rec = new ProgressRecord($time, $ele['examinee_id'], $ele['password'], $ele['name'], $ele['level'], $login, $progress, $reStart, $reStartDisabled, $cheatDisabled, $reason);

            array_push($list, $rec);
        }
        return view('admin/examProgress', ['list' => $list, 'condition' => $condition]);
    }

    function regist(Request $request)
    {
        $btn = $request->get('btn');
        $id = $request->get('id');
        $rsn = $request->get('rsn');

        switch($btn){
        case "RESTART":
            ExamineeLogin::where('examinee_id', $id)->update(['login' => 0, 'reason' => $rsn]);
            break;
        case "CHEAT":
            if(ExamineeLogin::where('examinee_id', $id)->exists()){
                ExamineeLogin::where('examinee_id', $id)->update(['login' => 0, 'progress' => 9, 'reason' => $rsn]);
            }else{
                ExamineeLogin::insert(['examinee_id' => $id, 'login' => 0, 'progress' => 9, 'reason' => $rsn]);
            }
            Grades::where('examinee_number', $id)->update(['pass_fail' => 9]);
            break;
        }

        return $this->search($request);
    }

    function picture(Request $request){
        $examineeId = $request->get('id');
        $folderIdId = Config::get('constants.folderGoogleDrive.folderId');

        $monthOfTest = static::getFolderMonth($examineeId);
        $dayOfTest = substr($examineeId,4,2);
        $yearOfTest = "20".substr($examineeId,0,2);
        $testDayFolderName = $yearOfTest."-".$monthOfTest."-".$dayOfTest;

        // $testPlace = substr($examineeId,6,3);
        // $query = TestSiteInformation::where('test_site', $testPlace);
        // $testPlaceCountryName = $query->value('country');
        // $testPlaceCityName = $query->value('city');

        // $testPlaceFolderId = static::getInformationFromGoogleDrive($testPlaceCountryName,$folderIdId);
        // $testPlaceCityFolderId = static::getInformationFromGoogleDrive($testPlaceCityName,$testPlaceFolderId);

        // $monthOfTest = static::getFolderMonth($examineeId);
        // $MonthFolderId = static::getInformationFromGoogleDrive($monthOfTest,$testPlaceCityFolderId);
        // $dayOfTest = substr($examineeId,4,2);
        // $dayFolderId = static::getInformationFromGoogleDrive($dayOfTest,$MonthFolderId);
        // $examineeIdTest = $examineeId;

        // $dayFolderId = "1WmheojMvuPjcl80keWWTg7Nq5kB4iBVI";
        // $examineeIdTest = "20072944050201";
        $testDayFolderId = static::getInformationFromGoogleDrive($testDayFolderName,$folderIdId);

        $examineeIdTest = $examineeId;
        $optParams = array(
            'pageSize' => 1,
            'fields' => 'nextPageToken, files(contentHints/thumbnail,fileExtension,id,name,size)',
            'q' =>"mimeType contains 'image/' AND name contains '".$examineeIdTest."' AND '".$testDayFolderId."' in parents"
          );

        $client = Storage::disk('google');
        $serviceGoogleDrive = new \Google_Service_Drive($client);
        $results = $serviceGoogleDrive->files->listFiles($optParams);
        if (count($results->getFiles()) == 0) {
            return back()->with('error', 'Picture not found in database. Please contact receptionist.');
        } else {
            $filesReturn = $results->getFiles();
            $resultFirst = reset($filesReturn);
            $imageId = $resultFirst->getId();
            return view('admin/examPicture', ['imageId'=>$imageId, 'name'=>$request->get('nm')]);
        }
    }
    
    function getFolderMonth($id){
        $monthTest = substr($id,2,2);
        switch ($monthTest) {
            case "01":
                $monthTest = "1";
                break;
            case "02":
                $monthTest = "2";
                break;
            case "03":
                $monthTest = "3";
                break;
            case "04":
                $monthTest = "4";
                break;
            case "05":
                $monthTest = "5";
                break;
            case "06":
                $monthTest = "6";
                break;
            case "07":
                $monthTest = "7";
                break;
            case "08":
                $monthTest = "8";
                break;
            case "09":
                $monthTest = "9";
                break;
            default:
                $monthTest = $monthTest;
        }
        return $monthTest;
    }

    function getInformationFromGoogleDrive($childrenName,$parentFolderId){
        $optParams = array(
            'pageSize' => 1,
            'q' =>"name contains '".$childrenName."' AND '".$parentFolderId."' in parents"
          );
          $client = Storage::disk('google');
          $serviceGoogleDrive = new \Google_Service_Drive($client);
          $results = $serviceGoogleDrive->files->listFiles($optParams);
        if (count($results->getFiles()) == 0) {
            return back()->with('error', 'Picture not found in database. Please contact receptionist.');
        } else {
            $filesReturn = $results->getFiles();
            $resultFirst = reset($filesReturn);
            $imageId = $resultFirst->getId();
            return $imageId;
        }
    }
}

?>