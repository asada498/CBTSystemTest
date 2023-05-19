<?php
namespace App\Http\Controllers\Q3\Listening;

use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use App\TestInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\QuestionClass\Q3\Listening\Q4S3Q4;
use App\QuestionDatabase\Q3\Listening\Q4Section3Question4;

class Q3S3Q4N2Controller extends Controller
{
    public function showQuestion (){

        $currentId = Session::get('idTester');
        $section3Question4Id = $currentId.".Q3S3Q4";
        $questionData = Session::get($section3Question4Id);
        return view('Q3\Listening\Q3S3Q4N2', ['data' => $questionData]);
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q3S3Q4_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question4Choice = $currentId.".Q3S3Q4_".$questionNumber;

        $sess = Session::get($section3Question4Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }
}