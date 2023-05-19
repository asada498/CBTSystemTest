<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
// use DB;
use App\TestInformation;
use App\ExamineeInformation;
use App\ExamineeLogin;
use App\ScoreSummary;
use App\TestResult;
use App\AnswerRecord;
use App\TestSiteInformation;
use App\Grades;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use DateTime;

class MainController extends Controller
{
    public function store(Request $request)
    {
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q123test";
            Session::put('idTester',$userIDTemp);        
        }
        $userID = Session::get('idTester');
        $level = substr($userID, 10, 1);
        $examineeId = substr($userID, 1);
        $status = $request->input('status');
        $examineeLogin = ExamineeLogin::where('examinee_id', '=', $examineeId)->first();
        if($examineeLogin != null){
            // リスタートの場合、写真撮影をスキップしているのでYESが押されたことにする
            $status = 'yes';
        }

        switch ($status) {
            case 'yes':

                $levelOfTestQ = TestInformation::where('examinee_id', $examineeId)->value('degree');
                $levelOfTest = substr($levelOfTestQ ,0,1);
                $grades = Grades::where('examinee_number', '=', $examineeId)->first();
                if($grades == null){
                    Grades::insert([
                        'examinee_number' => $examineeId,
                        'level' => $levelOfTest,
                    ]);
                }

                $progress = 0;
                if($examineeLogin == null){
                    $progress = 1;
                    ExamineeLogin::insert(['examinee_id' => $examineeId, 'login' => 1, 'progress' => $progress]);
                }else{
                    ExamineeLogin::where('examinee_id', '=', $examineeId)->update(['login' => 1]);
                    $progress = $examineeLogin['progress'];
                }
                if (ScoreSummary::where('examinee_number', '=', $examineeId)->count() > 0) {
                    // user found
                    switch($progress){
                    case 1:
                        ScoreSummary::where('examinee_number', '=', $examineeId)->where('level', '=', $level)
                            ->update(
                            [
                            's1_q1_correct' => 0, 's1_q1_question' => 0, 's1_q1_perfect_score' => 0, 's1_q1_anchor_pass' => 0,
                            's1_q2_correct' => 0, 's1_q2_question' => 0, 's1_q2_perfect_score' => 0, 's1_q2_anchor_pass' => 0,
                            's1_q3_correct' => 0, 's1_q3_question' => 0, 's1_q3_perfect_score' => 0, 's1_q3_anchor_pass' => 0,
                            's1_q4_correct' => 0, 's1_q4_question' => 0, 's1_q4_perfect_score' => 0, 's1_q4_anchor_pass' => 0,
                            's1_q5_correct' => 0, 's1_q5_question' => 0, 's1_q5_perfect_score' => 0, 's1_q5_anchor_pass' => 0,
                            's1_q6_correct' => 0, 's1_q6_question' => 0, 's1_q6_perfect_score' => 0, 's1_q6_anchor_pass' => 0,
                            's1_q7_correct' => 0, 's1_q7_question' => 0, 's1_q7_perfect_score' => 0, 's1_q7_anchor_pass' => 0,
                            's1_q8_correct' => 0, 's1_q8_question' => 0, 's1_q8_perfect_score' => 0, 's1_q8_anchor_pass' => 0,
                            's1_q9_correct' => 0, 's1_q9_question' => 0, 's1_q9_perfect_score' => 0, 's1_q9_anchor_pass' => 0,
                            's1_q10_correct' => 0, 's1_q10_question' => 0, 's1_q10_perfect_score' => 0, 's1_q10_anchor_pass' => 0,
                            's1_q11_correct' => 0, 's1_q11_question' => 0, 's1_q11_perfect_score' => 0, 's1_q11_anchor_pass' => 0,
                            's1_q12_correct' => 0, 's1_q12_question' => 0, 's1_q12_perfect_score' => 0, 's1_q12_anchor_pass' => 0,
                            's1_q13_correct' => 0, 's1_q13_question' => 0, 's1_q13_perfect_score' => 0, 's1_q13_anchor_pass' => 0,
                            's1_q14_correct' => 0, 's1_q14_question' => 0, 's1_q14_perfect_score' => 0, 's1_q14_anchor_pass' => 0,
                            's1_end_flag' => 0,
                            's1_q1_rate' => 0,
                            's1_q2_rate' => 0,
                            's1_q3_rate' => 0,
                            's1_q4_rate' => 0,
                            's1_q5_rate' => 0,
                            's1_q6_rate' => 0,
                            's1_q7_rate' => 0,
                            's1_q8_rate' => 0,
                            's1_q9_rate' => 0,
                            's1_q10_rate' => 0,
                            's1_q11_rate' => 0,
                            's1_q12_rate' => 0,
                            's1_q13_rate' => 0,
                            's1_q14_rate' => 0
                            ]
                        );
                        AnswerRecord::where('examinee_number', '=', $examineeId)->where('level', '=', $level)->where('section', '=', 1)->delete();
                        break;
                    case 2:
                        ScoreSummary::where('examinee_number', '=', $examineeId)->where('level', '=', $level)
                            ->update(
                            [
                            's2_q1_correct' => 0, 's2_q1_question' => 0, 's2_q1_perfect_score' => 0, 's2_q1_anchor_pass' => 0,
                            's2_q2_correct' => 0, 's2_q2_question' => 0, 's2_q2_perfect_score' => 0, 's2_q2_anchor_pass' => 0,
                            's2_q3_correct' => 0, 's2_q3_question' => 0, 's2_q3_perfect_score' => 0, 's2_q3_anchor_pass' => 0,
                            's2_q4_correct' => 0, 's2_q4_question' => 0, 's2_q4_perfect_score' => 0, 's2_q4_anchor_pass' => 0,
                            's2_q5_correct' => 0, 's2_q5_question' => 0, 's2_q5_perfect_score' => 0, 's2_q5_anchor_pass' => 0,
                            's2_q6_correct' => 0, 's2_q6_question' => 0, 's2_q6_perfect_score' => 0, 's2_q6_anchor_pass' => 0,
                            's2_q7_correct' => 0, 's2_q7_question' => 0, 's2_q7_perfect_score' => 0, 's2_q7_anchor_pass' => 0,
                            's2_q8_correct' => 0, 's2_q8_question' => 0, 's2_q8_perfect_score' => 0, 's2_q8_anchor_pass' => 0,
                            's2_q9_correct' => 0, 's2_q9_question' => 0, 's2_q9_perfect_score' => 0, 's2_q9_anchor_pass' => 0,
                            's2_end_flag' => 0,
                            's2_q1_rate' =>0,
                            's2_q2_rate' =>0,
                            's2_q3_rate' =>0,
                            's2_q4_rate' =>0,
                            's2_q5_rate' =>0,
                            's2_q6_rate' =>0,
                            's2_q7_rate' =>0,
                            's2_q8_rate' =>0,
                            's2_q9_rate' =>0
                            ]
                        );
                        AnswerRecord::where('examinee_number', '=', $examineeId)->where('level', '=', $level)->where('section', '=', 2)->delete();
                        break;
                    case 3;
                        ScoreSummary::where('examinee_number', '=', $examineeId)->where('level', '=', $level)
                            ->update(
                            [
                            's3_q1_correct' => 0, 's3_q1_question' => 0, 's3_q1_perfect_score' => 0, 's3_q1_anchor_pass' => 0,
                            's3_q2_correct' => 0, 's3_q2_question' => 0, 's3_q2_perfect_score' => 0, 's3_q2_anchor_pass' => 0,
                            's3_q3_correct' => 0, 's3_q3_question' => 0, 's3_q3_perfect_score' => 0, 's3_q3_anchor_pass' => 0,
                            's3_q4_correct' => 0, 's3_q4_question' => 0, 's3_q4_perfect_score' => 0, 's3_q4_anchor_pass' => 0,
                            's3_q5_correct' => 0, 's3_q5_question' => 0, 's3_q5_perfect_score' => 0, 's3_q5_anchor_pass' => 0,
                            's3_end_flag' => 0,
                            's3_q1_rate' =>0,
                            's3_q2_rate' =>0,
                            's3_q3_rate' =>0,
                            's3_q4_rate' =>0,
                            's3_q5_rate' =>0,
                            ]
                        );
                        AnswerRecord::where('examinee_number', '=', $examineeId)->where('level', '=', $level)->where('section', '=', 3)->delete();
                        break;
                    }

                 }
                else {
                    ScoreSummary::insert(
                        ['examinee_number' => $examineeId, 'level' => $level]
                    );
                }
                // if (TestResult::where('examinee_id', '=', $examineeId)->count() > 0) {
                //     // user found
                //  }
                // else {
                //     TestResult::insert(
                //         ['examinee_id' => $examineeId, 'level' => substr($userID, 10, 1)]
                //     );
                // }
                $dt = new DateTime();
                $result = $dt->format('Y-m-d');
                $path = base_path().'/testerLog'.'/'.$result;
                File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                $folderPath = $path.'/'.$examineeId.'.txt';
                File::put($folderPath,"");
                switch($progress){
                case 1:
                    switch($levelOfTest){
                        case '5':
                            file_put_contents($folderPath,"User ID no ".$examineeId." start the 5Q test. \n");
                            return Redirect::to(url('/Q5VocabularyWelcome'));
                        case '4':
                            file_put_contents($folderPath,"User ID no ".$examineeId." start the 4Q test. \n");
                            return Redirect::to(url('/Q4VocabularyWelcome'));
                        case '3':
                            file_put_contents($folderPath,"User ID no ".$examineeId." start the 3Q test. \n");
                            return Redirect::to(url('/Q3VocabularyWelcome'));
                        case '2':
                            file_put_contents($folderPath,"User ID no ".$examineeId." start the 2Q test. \n");
                            return Redirect::to(url('/Q2VocabularyWelcome'));
                        case '1':
                            file_put_contents($folderPath,"User ID no ".$examineeId." start the 1Q test. \n");
                            return Redirect::to(url('/Q1VocabularyWelcome'));
                    }
                case 2:
                    switch($level){
                    case 1:
                        return Redirect::to(url('/Q1S3Start'));
                    case 2:
                        return Redirect::to(url('/Q2S3Start'));
                    case 3:
                        return Redirect::to(url('/Q3ReadingWelcome'));
                    case 4:
                        return Redirect::to(url('/Q4ReadingWelcome'));
                    case 5:
                        return Redirect::to(url('/Q5ReadingWelcome'));
                    }
                case 3:
                    switch($level){
                    case 1:
                        return Redirect::to(url('/Q1S3Start'));
                    case 2:
                        return Redirect::to(url('/Q2S3Start'));
                    case 3:
                        return Redirect::to(url('/Q3S3Start'));
                    case 4:
                        return Redirect::to(url('/Q4S3Start'));
                    case 5:
                        return Redirect::to(url('/Q5S3Start'));
                    }
                }
                break;

            case 'no':
                $request->session()->flush();
                return redirect('errorInformationCheck');
                break;
        }
    } 

    public function reading5Q(Request $request){
        return view('Q5\Q5ReadingWelcome');
    }

    public function vocabulary5Q(Request $request){
        return view('levelConfirmation');
    }

    public function reading4Q(Request $request){
        return view('Q4\Q4ReadingWelcome');
    }

    public function vocabulary4Q(Request $request){
        return view('Q4\Q4LevelConfirmation');
    }

    public function vocabulary3Q(Request $request){
        return view('Q3\Q3LevelConfirmation');
    }

    public function reading3Q(Request $request){
        return view('Q3\Q3ReadingWelcome');
    }

    public function vocabulary2Q(Request $request){
        return view('Q2\Q2LevelConfirmation');
    }

    public function vocabulary1Q(Request $request){
        return view('Q1\Q1LevelConfirmation');
    }

    function testDrive(){
        $folderIdId = "1if2wE6UUB_93_gcPkxIzgrc71Ich38LE";
        $imageId = "206221AI11";
        error_log("value is ".$folderIdId."");
        $optParams = array(
            'pageSize' => 1,
            'fields' => 'nextPageToken, files(contentHints/thumbnail,fileExtension,id,name,size)',
            'q' =>"mimeType contains 'image/' AND name contains '".$imageId."' AND '".$folderIdId."' in parents"
          );
          $client = Storage::disk('google');
          $serviceGoogleDrive = new \Google_Service_Drive($client);
          $results = $serviceGoogleDrive->files->listFiles($optParams);
          
        if (count($results->getFiles()) == 0) {
            print "No files found.\n";
        } else {
            print "Files:\n";
            foreach ($results->getFiles() as $file) {
                // printf("%s (%s)\n", $file->getName(), $file->getId());
                error_log($file->getId());
                $image = file_get_contents('https://drive.google.com/uc?export=view&id=1z621QD_gg-XfhUbTjkKwbGXyoLdnn1nS' );
                header('content-type: image/gif');
                echo $image;
            }
        }
        // error_log($results);
        // Storage::disk('google')->put('test.txt', 'Hello World');
        // return $results;
    }

    function index5()
    {
     return view('login5');
    }

    // function index4()
    // {
    //  return view('login4');
    // }
    // function index3()
    // {
    //  return view('login3');
    // }
    // function index2()
    // {
    //  return view('login2');
    // }
    // function index1()
    // {
    //  return view('login1');
    // }
    function index()
    {
     return view('login');
    }

    function showErrorPage()
    {
     return view('errorInformationCheck');
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
        $textQ = "fullText contains '".$childrenName."' and '".$parentFolderId."' in parents";
        // dd($textQ);
        // if($parentFolderId == "1OPhFw9_DBrN8bZIACAGZtk62B8koEVWM")
        //     {
        //         dd("stop");
        //     }
        $optParams = array(
            'pageSize' => 1,
            'q' =>$textQ
          );
          $client = Storage::disk('google');
          $serviceGoogleDrive = new \Google_Service_Drive($client);
        //   dd($childrenName,$parentFolderId);
        // if($parentFolderId == "1OPhFw9_DBrN8bZIACAGZtk62B8koEVWM")
        //     {
        //         $results = $serviceGoogleDrive->files->listFiles($optParams);
        //         $filesReturn = count($results->getFiles());
        //         dd($filesReturn);
        //     }
          $results = $serviceGoogleDrive->files->listFiles($optParams);
        if (count($results->getFiles()) == 0) {
            // return back()->with('error', 'Picture not found in database. Please contact receptionist.');
            return null;
        } else {
            $filesReturn = $results->getFiles();
            $resultFirst = reset($filesReturn);
            $imageId = $resultFirst->getId();
            return $imageId;
        }
    }

    function checklogin(Request $request)
    {
        $this->validate($request, [
        'examineeId'   => 'required',
        'examineeNumber'  => 'required'
        ]);
     
        $examineeId = $request->get('examineeId');
        $restart = false;

        if(TestInformation::where('examinee_id', $request->get('examineeId'))->where('ID_number',$request->get('examineeNumber'))->exists())
        {
            $login = ExamineeLogin::where('examinee_id', $examineeId)->first();
            if($login != null)
            {
                if( $login['login'] == 1 ){
                    // already login
                    return back()->with('error', 'This ID has already been taken. Please use another ID.');
                }
                switch($login['progress']){
                case 4:
                    // EXAM END
                    return back()->with('error', 'This ID has already been taken. Please use another ID.');
                case 9:
                    // disqualification
                    return back()->with('error', 'Exam disqualification due to cheating.');
                }
                $restart = true;
            }

            // 本番環境の場合、ログイン制限をかける
            
            if(app()->isProduction())
            {
                
                // 未入金の場合、受験不可。通常はこのエラーは出ないはず
                $examineeInformation = ExamineeInformation::where('examinee_id',$examineeId)->first();
                if($examineeInformation['payment_done'] == 0){
                    return back()->with('error', 'You cannot take the exam because you have not paid. Please contact the receptionist.');
                }
                
                // リスタートでない場合、時間によるログイン制限
                if(!$restart){
                    $testSite = TestSiteInformation::where('test_site', substr($examineeId, 6 ,3))->first();
                    if($testSite == null){
                        return back()->with('error', 'System error. Please contact the receptionist.');
                    }
                    // TODO 試験開始時刻の許容範囲が？
                    // // 受験会場の現地時刻を取りたいので予め登録してあるタイムゾーンをセット
                    // date_default_timezone_set($testSite['timezone']);
                    // $systemDate = date("ymdHi"); // yymmddhh24mi
                    // $systemYear = substr($systemDate, 0, 2);
                    // $systemMonth = substr($systemDate, 2, 2);
                    // $systemDay = substr($systemDate, 4, 2);
                    // $systemTime = substr($systemDate, 6, 4);
                    // // 日付をチェック
                    // if(substr($examineeId, 0, 2) != $systemYear || substr($examineeId, 2, 2) != $systemMonth || substr($examineeId, 4, 2) != $systemDay){
                    //     return back()->with('error', 'The exam date is incorrect. Please contact the receptionist.');
                    // }
                    // // 時間をチェック
                    // $startTime = '';
                    // $endTime = '';
                    // switch(substr($examineeId, 10, 1)){
                    // case '1':
                    //     $startTime = '0850';
                    //     $endTime = '0930';
                    //     break;
                    // case '2':
                    //     $startTime = '1150';
                    //     $endTime = '1230';
                    //     break;
                    // case '3':
                    //     $startTime = '1450';
                    //     $endTime = '1530';
                    //     break;
                    // }
                    // if($startTime <= $systemTime && $systemTime <= $endTime ){
                    //     // OK
                    // }else{
                    //     return back()->with('error', 'It is out of the examination time range.');
                    // }
                }
            }


            $query = ExamineeInformation::where('examinee_id',$examineeId);
            $realName = $query->value('name');

            // $levelOfTest = TestInformation::where('examinee_id', $examineeId)->value('degree');
            // $folderIdId = Config::get('constants.folderGoogleDrive.folderId');

            // // $testPlaceFolder = static::getFolderTestSite($examineeId);
            // $testPlace = substr($examineeId,6,3);
            // $query = TestSiteInformation::where('test_site', $testPlace);
            // $testPlaceCountryName = $query->value('country');
            // $testPlaceCityName = $query->value('city');

            // $testPlaceFolderId = static::getInformationFromGoogleDrive($testPlaceCountryName,$folderIdId);
            // if ($testPlaceFolderId == null)
            //     return back()->with('error', 'Picture not found in database. Please contact receptionist.');

            // $testPlaceCityFolderId = static::getInformationFromGoogleDrive($testPlaceCityName,$testPlaceFolderId);
            // if ($testPlaceCityFolderId == null)
            //     return back()->with('error', 'Picture not found in database. Please contact receptionist.');

            // $monthOfTest = static::getFolderMonth($examineeId);
            // // dd($monthOfTest,$testPlaceCityFolderId);

            // $MonthFolderId = static::getInformationFromGoogleDrive($monthOfTest,$testPlaceCityFolderId);
            // if ($MonthFolderId == null)
            //     return back()->with('error', 'Picture not found in database. Please contact receptionist.');

            // $dayOfTest = substr($examineeId,4,2);
            // $dayFolderId = static::getInformationFromGoogleDrive($dayOfTest,$MonthFolderId);
            // if ($dayFolderId == null)
            //     return back()->with('error', 'Picture not found in database. Please contact receptionist.');

            // $examineeIdTest = $examineeId;
            // // $realName = "testee_name_test";
            // // $levelOfTest = "Q5";
            // // $dayFolderId = "1WmheojMvuPjcl80keWWTg7Nq5kB4iBVI";
            // // $examineeIdTest = "20072944050201";
            // $optParams = array(
            //     'pageSize' => 1,
            //     'fields' => 'nextPageToken, files(contentHints/thumbnail,fileExtension,id,name,size)',
            //     'q' =>"mimeType contains 'image/' AND name contains '".$examineeIdTest."' AND '".$dayFolderId."' in parents"
            //   );
            //   $client = Storage::disk('google');
            //   $serviceGoogleDrive = new \Google_Service_Drive($client);
            //   $results = $serviceGoogleDrive->files->listFiles($optParams);
            // if (count($results->getFiles()) == 0) {
            //     return back()->with('error', 'Picture not found in database. Please contact receptionist.');
            // } 
            // else {
                // $filesReturn = $results->getFiles();
                // $resultFirst = reset($filesReturn);
                // $imageId = $resultFirst->getId();

                $testerArray = 'Q'.$examineeId.'.nameTester';
                if (!$request->session()->has($examineeId))
                {
                    $request->session()->put($testerArray,$realName);
                }
                $request->session()->put('idTester','Q'.$examineeId);
                $request->session()->put('login',true);

                if($restart){
                    // リスタートの場合、写真撮影をスキップする
                    return $this->store($request);
                }else{
                    // 通常スタートの場合、写真撮影
                    return redirect('testeePicture');
                }

                // return view('pictureScreenshot');
                // return view('successlogin',['id' => $examineeId,'name' => $realName,'degree' => $levelOfTest,'imageId'=>$imageId  ]);

            // }
        }
        else
        {
        if(TestInformation::where('examinee_id', $request->get('examineeId'))->exists())
            return back()->with('error', 'The number does not match with the id. Please check your information again or contact the receptionist.');
        else 
            return back()->with('error', 'The id does not exist. Please check your information again or contact the receptionist.');
        }

    }   

    function logout()
    {
     Auth::logout();
     return redirect('main');
    }

    function returnToMainAfterExam()
    {
        error_log("logging out");
        Session::flush();
        return redirect('main');
    }

    function submitTesteePicture(Request $request)
    {
        $userID = Session::get('idTester');
        $examineeId = substr($userID, 1);

        $img = $_POST['image'];
        $folderPath = base_path().'/userImage'.'/';
  
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
  
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $examineeId . '.png';
  
        $file = $folderPath . $fileName;
        file_put_contents($file, $image_base64);
        
        $data = file_get_contents($file);
        // $client = Storage::disk('google');
        // $serviceGoogleDrive = new \Google_Service_Drive($client);
        // $fileZZZ = new \Google_Service_Drive_DriveFile();
        // $fileZZZ->setName(uniqid().'.png');
        // $fileZZZ->setDescription('A test document');
        // $fileZZZ->setMimeType('image/png');

        // // $parent = new \Google_Service_Drive_DriveFile(); //previously Google_ParentReference
        // // dd("123");

        // $fileZZZ->setParents(array('1Xr2NLJ_75iluSSqCpuzn5lfqkHoBj_zJ'));
        // $createdFile = $serviceGoogleDrive->files->create($fileZZZ, array(
        //     'data' => $data,
        //     'mimeType' => 'image/png',
        //     'uploadType' => 'multipart'
        //   ));
        // print_r($fileName);
        // dd($request);

        $testerArray = 'Q'.$examineeId.'.nameTester';
        $realName = Session::get($testerArray);
        $level = substr($userID, 10, 1);
        $fileZZZ = str_replace('\\', '/',$file);
        return redirect('main/successfulLogin');
        // return view('successlogin',['id' => $examineeId,'name' => $realName,'degree' => $level,'imageId'=>$fileZZZ  ]);
    }

    function testeePicture()
    {
        return view('pictureScreenshot');
    }

    function successfulLogin(){
        $userID = Session::get('idTester');
        $examineeId = substr($userID, 1);
        $fileName = $examineeId . '.png';
        $folderPath = base_path().'/userImage'.'/';
        $testerArray = 'Q'.$examineeId.'.nameTester';
        $realName = Session::get($testerArray);
        $level = substr($userID, 10, 1);
        $file = $folderPath . $fileName;
        $data = file_get_contents($file);        
        $client = Storage::disk('google');        
        $serviceGoogleDrive = new \Google_Service_Drive($client);
        $fileZZZ = new \Google_Service_Drive_DriveFile();
        $fileZZZ->setName($fileName);
        $fileZZZ->setMimeType('image/png');
        

        // $parent = new \Google_Service_Drive_DriveFile(); //previously Google_ParentReference
        // dd("123");
        $query = ExamineeInformation::where('examinee_id',$examineeId);
        $realName = $query->value('name');
        $folderIdId = Config::get('constants.folderGoogleDrive.folderId');

        // $levelOfTest = TestInformation::where('examinee_id', $examineeId)->value('degree');
        // $folderIdId = Config::get('constants.folderGoogleDrive.folderId');
 
        // // $testPlaceFolder = static::getFolderTestSite($examineeId);
        // $testPlace = substr($examineeId,6,3);
        // $query = TestSiteInformation::where('test_site', $testPlace);
        // $testPlaceCountryName = $query->value('country');
        // $testPlaceCityName = $query->value('city');
        

        // $testPlaceFolderId = static::getInformationFromGoogleDrive($testPlaceCountryName,$folderIdId);
        // if ($testPlaceFolderId == null)
        //     return back()->with('error', 'Picture not found in database. Please contact receptionist.');
        
        // $testPlaceCityFolderId = static::getInformationFromGoogleDrive($testPlaceCityName,$testPlaceFolderId);

        // if ($testPlaceCityFolderId == null)
        // {
        //     $folderTestPlaceCity = new \Google_Service_Drive_DriveFile();
        //     $folderTestPlaceCity->setName($testPlaceCityName);
        //     $folderTestPlaceCity->setMimeType('application/vnd.google-apps.folder');
        //     $folderTestPlaceCity->setParents(array($testPlaceFolderId));

        //     $folder = $serviceGoogleDrive->files->create($folderTestPlaceCity);
        // }
        // $testPlaceCityFolderId = static::getInformationFromGoogleDrive($testPlaceCityName,$testPlaceFolderId);
        

        //     // return back()->with('error', 'Picture not found in database. Please contact receptionist.');
        $monthOfTest = static::getFolderMonth($examineeId);

        // $MonthFolderId = static::getInformationFromGoogleDrive($monthOfTest,$testPlaceCityFolderId);
        
        // if ($MonthFolderId == null)
        // {
        //     $folderTestMonth = new \Google_Service_Drive_DriveFile();
        //     $folderTestMonth->setName($monthOfTest);
        //     $folderTestMonth->setMimeType('application/vnd.google-apps.folder');
        //     $folderTestMonth->setParents(array($testPlaceCityFolderId));

        //     $folder = $serviceGoogleDrive->files->create($folderTestMonth);
        // }
        // $MonthFolderId = static::getInformationFromGoogleDrive($monthOfTest,$testPlaceCityFolderId);
        
        $dayOfTest = substr($examineeId,4,2);
        $yearOfTest = "20".substr($examineeId,0,2);
        $testDayFolderName = $yearOfTest."-".$monthOfTest."-".$dayOfTest;

        $testDayFolderId = static::getInformationFromGoogleDrive($testDayFolderName,$folderIdId);
        if ($testDayFolderId == null)
        {
            $folderTestDayMonth = new \Google_Service_Drive_DriveFile();
            $folderTestDayMonth->setName($testDayFolderName);
            $folderTestDayMonth->setMimeType('application/vnd.google-apps.folder');
            $folderTestDayMonth->setParents(array($folderIdId));
            $folder = $serviceGoogleDrive->files->create($folderTestDayMonth);
            $testDayFolderId = $folder->getId();
        }
        // $dayFolderId = static::getInformationFromGoogleDrive($dayOfTest,$MonthFolderId);        

        // old code for filtering.

        // $testPlace = substr($examineeId,6,3);
        // $query = TestSiteInformation::where('test_site', $testPlace);
        // $testPlaceCountryName = $query->value('country');
        // $testPlaceCityName = $query->value('city');

        // $testPlaceFolderId = static::getInformationFromGoogleDrive($testPlaceCountryName,$folderIdId);
        // if ($testPlaceFolderId == null)
        //     return back()->with('error', 'Picture not found in database. Please contact receptionist.');

        // $testPlaceCityFolderId = static::getInformationFromGoogleDrive($testPlaceCityName,$testPlaceFolderId);

        // if ($testPlaceCityFolderId == null)
        // {
        //     $folderTestPlaceCity = new \Google_Service_Drive_DriveFile();
        //     $folderTestPlaceCity->setName($testPlaceCityName);
        //     $folderTestPlaceCity->setMimeType('application/vnd.google-apps.folder');

        //     $folder = $serviceGoogleDrive->files->create($folderTestPlaceCity);
        //     // dd($folder,$folder->getId());
        // }

        //     // return back()->with('error', 'Picture not found in database. Please contact receptionist.');
        // $monthOfTest = static::getFolderMonth($examineeId);

        // $MonthFolderId = static::getInformationFromGoogleDrive($monthOfTest,$testPlaceCityFolderId);

        // if ($MonthFolderId == null)
        // {
        //     $folderTestMonth = new \Google_Service_Drive_DriveFile();
        //     $folderTestMonth->setName($monthOfTest);
        //     $folderTestMonth->setMimeType('application/vnd.google-apps.folder');
        //     $folderTestMonth->setParents(array($testPlaceCityFolderId));

        //     $folder = $serviceGoogleDrive->files->create($folderTestMonth);
        // }
        // $dayOfTest = substr($examineeId,4,2);
        // $dayFolderId = static::getInformationFromGoogleDrive($dayOfTest,$MonthFolderId);
        // if ($dayFolderId == null)
        // {
        //     $folderTestDay = new \Google_Service_Drive_DriveFile();
        //     $folderTestDay->setName($dayOfTest);
        //     $folderTestDay->setMimeType('application/vnd.google-apps.folder');
        //     $folderTestDay->setParents(array($MonthFolderId));

        //     $folder = $serviceGoogleDrive->files->create($folderTestDay);
        //     $dayFolderId = $folder->getId();
        // }

        // $fileZZZ->setParents(array($dayFolderId));
        // $createdFile = $serviceGoogleDrive->files->create($fileZZZ, array(
        //     'data' => $data,
        //     'mimeType' => 'image/png',
        //     'uploadType' => 'multipart'
        //   ));
        // $pictureSource =  $testPlaceCountryName."/".$testPlaceCityName."/".$monthOfTest."/".$dayOfTest;
        //   ExamineeInformation::where('examinee_id', $examineeId)->update([
        //       'picture_source' => $pictureSource
        //   ]);
        // $fileID = $createdFile->getId();
        
        $fileZZZ->setParents(array($testDayFolderId));
        $createdFile = $serviceGoogleDrive->files->create($fileZZZ, array(
            'data' => $data,
            'mimeType' => 'image/png',
            'uploadType' => 'multipart'
          ));
        $pictureSource =  $testDayFolderId;
          ExamineeInformation::where('examinee_id', $examineeId)->update([
              'picture_source' => $pictureSource
          ]);
        $fileID = $createdFile->getId();
        return view('successfulLogin',['id' => $examineeId,'name' => $realName,'degree' => $level,'imageId'=>$fileID  ]);
    }
}

?>
