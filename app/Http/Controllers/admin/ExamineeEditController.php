<?php

namespace App\Http\Controllers\admin;

use Auth;
use Session;
use Validator;
use DB;
use Mail;
use App\Administrator;
use App\ExamineeList;
use App\ExamineeRecord;
use App\ExamineeDetailRecord;
use App\ExamineeInformation;
use App\SearchCondition;
use App\TestInformation;
use App\TestSiteInformation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ExamineeEditController extends Controller
{
    function init(Request $request)
    {
        $examineeId = $request->get('editExamineeId');
        $rec = ExamineeList::where('examinee_id', $examineeId)->first();
        $data = new ExamineeDetailRecord($rec['examinee_id'], $rec['password'], $rec['country'], $rec['city'], $rec['test_day'], $rec['level'], $rec['name'], $rec['payment_done'], $rec['picture_source'], $rec['birthDay'], $rec['country_ad'], $rec['address'], $rec['zipcode'], $rec['email']);
      
        return view('admin/editExaminee', ['data' => $data]);
    }

    function regist(Request $request)
    {
        $examineeId = $request->get('examineeId');
        $name = $request->get('name');
        $paymentDone = $request->get('payment');
        $birthday = $request->get('birthDay');
        $country = $request->get('countryAd');
        $address = $request->get('address');
        $zipcode = $request->get('zipcode');
        $email = $request->get('email');
        $file = $request->file('image');
        if ($request->hasFile('image'))
        {
            $folderIdId = Config::get('constants.folderGoogleDrive.folderId');
            $monthOfTest = static::getFolderMonth($examineeId);
            $dayOfTest = substr($examineeId,4,2);
            $yearOfTest = "20".substr($examineeId,0,2);
            $testDayFolderName = $yearOfTest."-".$monthOfTest."-".$dayOfTest;
            
            // $testPlace = substr($examineeId,6,3);
            // $query = TestSiteInformation::where('test_site', $testPlace);
            // $testPlaceCountryName = $query->value('country');
            // $testPlaceCityName = $query->value('city');

            // $testPlaceFolderId = static::uploadPictureGoogleDriveService($testPlaceCountryName,$folderIdId);
            // $testPlaceCityFolderId = static::uploadPictureGoogleDriveService($testPlaceCityName,$testPlaceFolderId);

            // $monthOfTest = static::getFolderMonth($examineeId);
            // $MonthFolderId = static::uploadPictureGoogleDriveService($monthOfTest,$testPlaceCityFolderId);
            // $dayOfTest = substr($examineeId,4,2);
            // $dayFolderId = static::uploadPictureGoogleDriveService($dayOfTest,$MonthFolderId);

            $dayFolderId = static::uploadPictureGoogleDriveService($testDayFolderName,$folderIdId);
            $optParams = array(
                'pageSize' => 1,
                'fields' => 'nextPageToken, files(contentHints/thumbnail,fileExtension,id,name,size)',
                'q' =>"name contains '".$examineeId."' AND '".$dayFolderId."' in parents"
              );
            $client = Storage::disk('google');
            $serviceGoogleDrive = new \Google_Service_Drive($client);
            $results = $serviceGoogleDrive->files->listFiles($optParams);

            if (count($results->getFiles()) !== 0) {
                $filesReturn = $results->getFiles();
                $resultFirst = reset($filesReturn);
                $imageId = $resultFirst->getId();
                $serviceGoogleDrive->files->delete($imageId);
            }

            $fileUpload = new \Google_Service_Drive_DriveFile();
            $fileUpload->setName($examineeId.".".$file->getClientOriginalExtension());
            $fileUpload->setParents([$dayFolderId]);

            $fileUpload->setDescription('replacement picture for ID '.$examineeId);

            $data = file_get_contents($file->getRealPath());

            $result = $serviceGoogleDrive->files->create($fileUpload, array(
                'data' => $data,
                'uploadType' => 'media'
            ));

            // $imagePath = $testPlaceCountryName."/".$testPlaceCityName."/".$monthOfTest."/".$dayOfTest;
            $imagePath = $testDayFolderName;
            $currentPaymentStatus = ExamineeInformation::where('examinee_id', $examineeId)->first()->payment_done;
            $currentName = ExamineeInformation::where('examinee_id', $examineeId)->first()->name;
            ExamineeInformation::where('examinee_id', $examineeId)->update([
                'name' => $name,
                'birthday' => $birthday,
                'address' => $address,
                'zipcode' => $zipcode,
                'country' => $country,
                'email' => $email,
                'payment_done' => $paymentDone,
                'picture_source' => $imagePath
            ]);
        } else {
            $currentPaymentStatus = ExamineeInformation::where('examinee_id', $examineeId)->first()->payment_done;
            $currentName = ExamineeInformation::where('examinee_id', $examineeId)->first()->name;
            ExamineeInformation::where('examinee_id', $examineeId)->update([
                'name' => $name,
                'birthday' => $birthday,
                'address' => $address,
                'zipcode' => $zipcode,
                'country' => $country,
                'email' => $email,
                'payment_done' => $paymentDone
            ]);
        }
        if ($paymentDone != $currentPaymentStatus)
        {
            static::updateGoogleSheet($examineeId,$paymentDone);
            if($paymentDone == '1'){
                // 入金が未→済になったタイミングで受験者にメールを出す
                $this->mail($examineeId, $email);
            }
        }
        if ($name != $currentName)
        {
            static::updateGoogleSheetName($examineeId,$name);
        }

        $condition = Session::get('ExamineeListCondition');
        $condition->setAutoSearch(1);
        $list = [];
        return view('admin/examineeList', ['data' => $list, 'condition' => $condition]);
    }


    function mail($examineeId, $toEmail){

        $testInformation = TestInformation::where('examinee_id', $examineeId)->first(); //->payment_done;
        $password = $testInformation->ID_number;
        $country = $testInformation->country;
        $testSite = $testInformation->city;

        $year = "20".substr($examineeId, 0, 2);
        $month = substr($examineeId, 2, 2);
        $day = substr($examineeId, 4, 2);
        $level = substr($examineeId, 9, 1);
        $time = '';
        switch(substr($examineeId, 10, 1)){
        case '1':
            $time = '09:00';
            break;
        case '2':
            $time = '12:00';
            break;
        case '3':
            $time = '15:00';
            break;
        }
        $eMonth = '';
        switch($month){
        case '01':
            $eMonth = 'January';
            break;
        case '02':
            $eMonth = 'February';
            break;
        case '03':
            $eMonth = 'March';
            break;
        case '04':
            $eMonth = 'April';
            break;
        case '05':
            $eMonth = 'May';
            break;
        case '06':
            $eMonth = 'June';
            break;
        case '07':
            $eMonth = 'July';
            break;
        case '08':
            $eMonth = 'August';
            break;
        case '09':
            $eMonth = 'September';
            break;
        case '10':
            $eMonth = 'October';
            break;
        case '11':
            $eMonth = 'November';
            break;
        case '12':
            $eMonth = 'December';
            break;
        }

        // $body  = "入金を確認しました。\n";
        // $body .= "Your remittance was confirmed.\n";
        // $body .= "\n";
        // $body .= "あなたの試験日、会場は次の通りです。\n";
        // $body .= "Your test information is as follows,\n";
        // $body .= "\n";
        // $body .= "国:".$country."  会場:".$testSite."  試験日:".$year."年".$month."月".$day."日 ".$time."  ".$level."級\n";
        // $body .= "Country:".$country."  Testsite:".$testSite."  Testday:".$eMonth." ".$day.", '".substr($examineeId, 0, 2)." ".$time."  ".$level."Q\n";
        // $body .= "\n";
        // $body .= "試験時間には必ず、会場に来てください。15分以上遅刻すると受験できない場合があります。\n";
        // $body .= "Come to the test site on time. You can not take a test if you late 15min, or over.\n";
        // $body .= "\n";
        // $body .= "あなたが会場でコンピュータにアクセスする場合は、あなたの受験番号と次の暗証番号が必要です。\n";
        // $body .= "You need to put your examinee number and pin to access to the computer at the test site.\n";
        // $body .= "\n";
        // $body .= "あなたの受験番号と暗証番号は".$examineeId."と".$password."です。必ずメモをしておいてください。\n";
        // $body .= "Your examinee number is ".$examineeId." and your pin is ".$password.". Take a note of these numbers.\n";

        $body  = "にゅうきんを　かくにんしました。\n";
        $body .= "Your remittance was confirmed.\n";
        $body .= "\n";
        $body .= "あなたの　しけんび、　かいじょうは　つぎの　とおりです。\n";
        $body .= "Your test information is as follows,\n";
        $body .= "\n";
        $body .= "くに:".$country."  かいじょう:".$testSite."  しけんび:".$year."ねん".$month."がつ".$day."にち ".$time."  ".$level."きゅう\n";
        $body .= "Country:".$country."  Testsite:".$testSite."  Testday:".$eMonth." ".$day.", '".substr($examineeId, 0, 2)." ".$time."  ".$level."Q\n";
        $body .= "\n";
        $body .= "しけんじかんには　かならず、　かいじょうに　きて　ください。　15ふん　いじょう　ちこくすると　じゅけんできない　ばあいが　あります。\n";
        $body .= "Come to the test site on time. You can not take a test if you late 15min, or over.\n";
        $body .= "\n";
        $body .= "あなたが　かいじょうで　コンピュータに　アクセスする　ばあいは、　あなたの　じゅけんばんごうと　つぎの　あんしょうばんごうが　ひつようです。\n";
        $body .= "You need to put your examinee's number and pin to access to the computer at the test site.\n";
        $body .= "\n";
        $body .= "あなたの　じゅけんばんごうと　あんしょうばんごうは　".$examineeId."と".$password."　です。かならず　メモを　して　おいて　ください。\n";
        $body .= "Your examinee's number is ".$examineeId." and your pin is ".$password.". Take a note of these numbers.\n";

        $attachFile = '';
        switch(substr($examineeId, 6, 3)){
        case '890':
            // TOKYO
            // TODO replace file
            $attachFile = './attachment/tokyo.jpg';
            break;
        default:
            $attachFile = './attachment/nomap.jpg';
            break;
        }

        Mail::send([], [],
            function ($message) use ($body, $toEmail, $attachFile) {
                $message->from('asada@senmonkyouiku.co.jp', 'SENMON KYOUIKU');
                $message->to($toEmail)->subject('にゅうきんを　かくにんしました。Your remittance was confirmed.');
                $message->setBody($body);
                $message->attach($attachFile);
            });
    }


    function updateGoogleSheet($examineeId,$paymentDone){
        $colorFlag = $paymentDone;
        $examineeTestPlaceInformation = TestInformation::where('examinee_id',$examineeId)->first();
        $country = $examineeTestPlaceInformation->country;
        $city = $examineeTestPlaceInformation->city;
        $testDay = $examineeTestPlaceInformation->test_day;
        $folderIdId = Config::get('constants.folderGoogleDrive.sheetFolderId');

        $optParams = array(
            'pageSize' => 1,
            'fields' => 'nextPageToken, files(contentHints/thumbnail,fileExtension,id,name,size)',
            'q' =>"name contains '".$country."' AND '".$folderIdId."' in parents"
          );
          $client = Storage::disk('google');
          $serviceGoogleDrive = new \Google_Service_Drive($client);
          $results = $serviceGoogleDrive->files->listFiles($optParams);
        if (count($results->getFiles()) == 0) {
            print "No files found.\n";
        } else {
            foreach ($results->getFiles() as $file) {
                $countryId = $file->getId();
                $optParams = array(
                    'pageSize' => 1,
                    'fields' => 'nextPageToken, files(contentHints/thumbnail,fileExtension,id,name,size)',
                    'q' =>"name contains '".$city."' AND '".$countryId."' in parents"
                  );
        
                  $results = $serviceGoogleDrive->files->listFiles($optParams);
                  if (count($results->getFiles()) == 0) {
                    print "No files found.\n";
                    } else {
                    foreach ($results->getFiles() as $testPlaceFile) {
                        $spreadSheetId = $testPlaceFile->getId();
                        $spreadSheetName = str_replace("-","/",$testDay);
                        $serviceGoogleSheet = new \Google_Service_Sheets($client);
                        $sheets  = $serviceGoogleSheet->spreadsheets->get($spreadSheetId, ["fields" => "sheets(properties)"])->getSheets();

                        $obj = array();
                        foreach ($sheets as $i => $sheet) {
                            $property = $sheet -> getProperties();
                            $obj[$property -> getTitle()] = $property -> getSheetId();

                        }
                        $determineCellChangeColor = static::findCellToChange($examineeId);
                        $sheetId = $obj[$spreadSheetName];
                        $requests = [
                            new \Google_Service_Sheets_Request([
                                'repeatCell' => [
                                    'cell' => [
                                        'userEnteredFormat' => [
                                            "horizontalAlignment" => "CENTER",
                                            'textFormat' => [
                                                "foregroundColor" => [
                                                    "red" => $colorFlag,
                                                    "green" => 0,
                                                    "blue"=> 0
                                                ],
                                                "bold"=>true
                                            ]
                                        ]
                                    ],
                                    'range' => [
                                        'sheetId' => $sheetId,  
                                        'startRowIndex' => $determineCellChangeColor[0], //11,13...
                                        'endRowIndex' => $determineCellChangeColor[0]+2,
                                        'startColumnIndex' => $determineCellChangeColor[1],// 1,5,9 - 1,2,3
                                        'endColumnIndex' => $determineCellChangeColor[1]+5
                                    ],
                                    'fields' => 'userEnteredFormat(horizontalAlignment,textFormat)'
                                ]
                            ])
                        ]; 
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                            'requests' => $requests
                        ]);
                        $response = $serviceGoogleSheet->spreadsheets->batchUpdate($spreadSheetId,
                            $batchUpdateRequest);
                    }
                }
            }
        }
    }

    function updateGoogleSheetName($examineeId,$name){
        $examineeTestPlaceInformation = TestInformation::where('examinee_id',$examineeId)->first();
        $country = $examineeTestPlaceInformation->country;
        $city = $examineeTestPlaceInformation->city;
        $testDay = $examineeTestPlaceInformation->test_day;
        $folderIdId = Config::get('constants.folderGoogleDrive.sheetFolderId');
        $optParams = array(
            'pageSize' => 1,
            'fields' => 'nextPageToken, files(contentHints/thumbnail,fileExtension,id,name,size)',
            'q' =>"name contains '".$country."' AND '".$folderIdId."' in parents"
          );
          $client = Storage::disk('google');
          $serviceGoogleDrive = new \Google_Service_Drive($client);
          $results = $serviceGoogleDrive->files->listFiles($optParams);
        if (count($results->getFiles()) == 0) {
            print "No files found.\n";
        } else {
            foreach ($results->getFiles() as $file) {
                $countryId = $file->getId();
                $optParams = array(
                    'pageSize' => 1,
                    'fields' => 'nextPageToken, files(contentHints/thumbnail,fileExtension,id,name,size)',
                    'q' =>"name contains '".$city."' AND '".$countryId."' in parents"
                  );
        
                  $results = $serviceGoogleDrive->files->listFiles($optParams);
                  if (count($results->getFiles()) == 0) {
                    print "No files found.\n";
                    } else {
                    foreach ($results->getFiles() as $testPlaceFile) {
                        $spreadSheetId = $testPlaceFile->getId();
                        $spreadSheetName = str_replace("-","/",$testDay);
                        $serviceGoogleSheet = new \Google_Service_Sheets($client);
                        $sheets  = $serviceGoogleSheet->spreadsheets->get($spreadSheetId, ["fields" => "sheets(properties)"])->getSheets();

                        $obj = array();
                        foreach ($sheets as $i => $sheet) {
                            $property = $sheet -> getProperties();
                            $obj[$property -> getTitle()] = $property -> getSheetId();

                        }
                        $determineCellChangeColor = static::findCellToChange($examineeId);
                        $sheetId = $obj[$spreadSheetName];
                        $requests = [
                            new \Google_Service_Sheets_Request([
                                'updateCells' => [
                                    'fields' => 'userEnteredValue',
                                    'rows' => [
                                        'values'=>[
                                            'userEnteredValue'=>[
                                                'stringValue' => $name
                                            ]
                                        ]
                                    ],
                                    'start' => [
                                        'columnIndex' => $determineCellChangeColor[1]+3,
                                        'rowIndex' => $determineCellChangeColor[0],
                                        'sheetId' => $sheetId
                                    ]
                                ]
                            ])
                        ]; 
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                            'requests' => $requests
                        ]);
                        $response = $serviceGoogleSheet->spreadsheets->batchUpdate($spreadSheetId,
                            $batchUpdateRequest);
                        }
                }
            }
        }
    }

    function findCellToChange($examineeId){
        $testTimeSlot = substr($examineeId,10,1) + 0;
        $testComputerNumber = substr($examineeId,11,2) + 0;

        // return index of row and column changing
        $columnIndex;
        switch ($testTimeSlot) {
            case 1:
                $columnIndex = 1;
                break;
            case 2:
                $columnIndex = 6;
                break;
            case 3:
                $columnIndex = 11;
                break;
        }
        $rowIndex = 8+$testComputerNumber*2;
        return [$rowIndex,$columnIndex];
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
            return view('admin/examPicture', ['imageId'=>$imageId]);
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
        //   if($childrenName ==="DANANG")
        //       dd($results->getFiles());
        if (count($results->getFiles()) == 0) {
            return back()->with('error', 'Picture not found in database. Please contact receptionist.');
        } else {
            $filesReturn = $results->getFiles();
            $resultFirst = reset($filesReturn);
            $imageId = $resultFirst->getId();
            return $imageId;
        }
    }

    function uploadPictureGoogleDriveService($childrenName,$parentFolderId){
        $optParams = array(
            'pageSize' => 1,
            'q' =>"name contains '".$childrenName."' AND '".$parentFolderId."' in parents"
          );
          $client = Storage::disk('google');
          $serviceGoogleDrive = new \Google_Service_Drive($client);
          $results = $serviceGoogleDrive->files->listFiles($optParams);
        if (count($results->getFiles()) == 0) {
            $fileMetadata = new \Google_Service_Drive_DriveFile(array(
                'name' => $childrenName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'supportsAllDrives' => true,
                'parents' => [$parentFolderId]
            ));
            $result = $serviceGoogleDrive->files->create($fileMetadata, array(
                'fields' => 'id'));
            return $result->id;
        } else {
            $filesReturn = $results->getFiles();
            $resultFirst = reset($filesReturn);
            $imageId = $resultFirst->getId();
            return $imageId;
        }
    }
}

?>