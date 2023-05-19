<?php
namespace App\Http\Controllers\Q5\Listening;

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
use App\QuestionClass\Q5\Listening\Q5S3Q3;
use App\QuestionDatabase\Q5\Listening\Q5Section3Question3;

class Q5S3Q3N2Controller extends Controller
{
    public function showQuestion (){

        $currentId = Session::get('idTester');
        $section3Question3Id = $currentId.".Q5S3Q3";
        $questionData = Session::get($section3Question3Id);
        return view('Q5\Listening\Q5S3Q3N2', ['data' => $questionData]);
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S3Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question3Choice = $currentId.".Q5S3Q3_".$questionNumber;

        $sess = Session::get($section3Question3Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }
}