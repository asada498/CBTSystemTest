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
//use Illuminate\Pagination\Paginator;
//use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\QuestionClass\Q5\Listening\Q5S3Q1;
use App\QuestionDatabase\Q5\Listening\Q5Section3Question1;


class Q5S3Q1N4Controller extends Controller
{
    public function showQuestion (){

        $currentId = Session::get('idTester');
        $section3Question1Id = $currentId.".Q5S3Q1";
        $questionData = Session::get($section3Question1Id);

        return view('Q5\Listening\Q5S3Q1N4', ['data' => $questionData]);
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S3Q1_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question1Choice = $currentId.".Q5S3Q1_".$questionNumber;

        $sess = Session::get($section3Question1Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }
}