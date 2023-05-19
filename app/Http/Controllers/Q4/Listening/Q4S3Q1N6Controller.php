<?php
namespace App\Http\Controllers\Q4\Listening;

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
//use Illuminate\Pagination\Paginator;
//use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\QuestionClass\Q4\Listening\Q4S3Q1;
use App\QuestionDatabase\Q4\Listening\Q4Section3Question1;

class Q4S3Q1N6Controller extends Controller
{
    public function showQuestion (){

        $currentId = Session::get('idTester');
        $section3Question1Id = $currentId.".Q4S3Q1";
        $questionData = Session::get($section3Question1Id);

        return view('Q4\Listening\Q4S3Q1N6', ['data' => $questionData]);
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q4S3Q1_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question1Choice = $currentId.".Q4S3Q1_".$questionNumber;

        $sess = Session::get($section3Question1Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }
}