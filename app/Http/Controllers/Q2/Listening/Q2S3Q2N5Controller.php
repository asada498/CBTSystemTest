<?php
namespace App\Http\Controllers\Q2\Listening;

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
use App\QuestionClass\Q2\Listening\Q2S3Q2;
use App\QuestionDatabase\Q2\Listening\Q2Section3Question2;

class Q2S3Q2N5Controller extends Controller
{
    public function showQuestion (){

        $currentId = Session::get('idTester');
        $section3Question3Id = $currentId.".Q2S3Q2";
        $questionData = Session::get($section3Question3Id);

        return view('Q2\Listening\Q2S3Q2N5', ['data' => $questionData]);
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q2S3Q2_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question2Choice = $currentId.".Q2S3Q2_".$questionNumber;

        $sess = Session::get($section3Question2Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }
}