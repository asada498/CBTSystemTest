<?php
namespace App\Http\Controllers\Q3\Listening;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class Q3S3Q1N6Controller extends Controller
{
    public function showQuestion (){

        $currentId = Session::get('idTester');
        $section3Question1Id = $currentId.".Q3S3Q1";
        $questionData = Session::get($section3Question1Id);

        return view('Q3\Listening\Q3S3Q1N6', ['data' => $questionData]);
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q3S3Q1_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question1Choice = $currentId.".Q3S3Q1_".$questionNumber;

        $sess = Session::get($section3Question1Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }
}