<?php

namespace App\Http\Controllers\Q4;
use App\TestResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


class ScoreDetailOptionController extends Controller
{

  public function show(Request $request){

    if (!(Session::has('idTester'))) {
        Session::put('idTester', "Q21020889050844"); // 2020/12/10 TOKYO LEVEL5 0001
    }
    $currentId = Session::get('idTester');
       

    return view('Q4\ScoreDetailOption');
  } 
  
    public function addGradeOption( Request $request){

        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q21020889050844";
            Session::put('idTester',$userIDTemp);        
        }
$userID = Session::get('idTester');

        if($request['grade'] == 'gradeyes'){

        TestResult::where('examinee_id', substr($userID, 1))->update([
           
            'level' => 4,
            'grades'=> 1,
            'grades_print' =>0,
            'grades_shipping' =>0,
          ]);
    
    } 
    elseif ($request['grade'] == 'gradeno')   {

        TestResult::where('examinee_id', substr($userID, 1))->update([
         
            'level' => 4,
            'grades'=> 0,
               ]);
    }   


    if($request['certificate'] == 'certificateyes'){

        TestResult::where('examinee_id', substr($userID, 1))->update([
        
         'level' => 4,
         'certificate'=> 1,
         'certificate_print' =>0,
         'certificate_shipping' =>0,
         'certificate_fee'=>0
 
        ]);
 
 
     } 
     elseif ($request['certificate'] == 'certificateno')   {
 
         TestResult::where('examinee_id', substr($userID, 1))->update([
            'level' => 4,
         'certificate'=> 0,
         ]);
     }   
     
     return Redirect::to(url('/End5Level'));

    }

  



    public function saveChoiceRequestgrade(Request $request)
    {       
        $currentId = Session::get('idTester','');
        $choiceNumber = $request->get('name');
        $choice = $request->get('choice');
        $valueSession = $currentId.".ScoreDetailOption_".$choiceNumber;
        Session::put($valueSession, $choice);
        return response()->json(['success' => $valueSession]);
    }
    public static function checkValue($choiceNumber,$optionChoice)
    {
        $currentId = Session::get('idTester');
        $choiceNumber = $currentId.".ScoreDetailOption_".$choiceNumber;

        $sess = Session::get($choiceNumber);
        if ($sess == $optionChoice)
            return "checked ";

        else return "";
    }
}
