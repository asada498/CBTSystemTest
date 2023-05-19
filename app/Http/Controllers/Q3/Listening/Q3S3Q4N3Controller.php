<?php
namespace App\Http\Controllers\Q3\Listening;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Listening\Q3Section3Question4;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q4\Listening\Q3S3Q4;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Q3S3Q4N3Controller extends Controller
{
    public function showQuestion (){

        $currentId = Session::get('idTester');
        $section3Question4Id = $currentId.".Q3S3Q4";
        $questionData = Session::get($section3Question4Id);

        return view('Q3\Listening\Q3S3Q4N3', ['data' => $questionData]);
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