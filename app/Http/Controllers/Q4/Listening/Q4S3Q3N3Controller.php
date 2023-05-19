<?php
namespace App\Http\Controllers\Q4\Listening;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Listening\Q4Section3Question3;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q4\Listening\Q4S3Q3;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Q4S3Q3N3Controller extends Controller
{
    public function showQuestion (){

        $currentId = Session::get('idTester');
        $section3Question3Id = $currentId.".Q4S3Q3";
        $questionData = Session::get($section3Question3Id);

        return view('Q4\Listening\Q4S3Q3N3', ['data' => $questionData]);
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q4S3Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question3Choice = $currentId.".Q4S3Q3_".$questionNumber;

        $sess = Session::get($section3Question3Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }
}