<?php

namespace App\Http\Controllers\Q5\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question6;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q5\Reading\Q5S2Q6;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Session;
use Illuminate\Support\Facades\Config;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;
use App\ExamineeLogin;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use DateTime;

class Q5S2Q6ShowAllController extends Controller
{
    public function showQuestion (){
        
        $data = $this->showDataBase();

        // $questionDataLoad = Session::get($section1Question3Id);
        // dd($questionDataLoad);

        // $data = $this->paginate($questionDataLoad);
        // $tempUrl =  Storage::disk('s3')->url('Q5/Vocabulary/N11251030500.jpg');
        // $expiryDate = now()->addHours(4);
        // $temporaryUrl = Storage::disk('s3')->temporaryUrl("Q5/Vocabulary/N11251030500.jpg", $expiryDate);
        // dd($questionData);
        return view('Q5\Reading\showTestQ5S2Q6', compact('data'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;
        // dd(Session::all());
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        $section2Question6Id = $userID.".Q5S2Q6";
        $question = Session::get($section2Question6Id);
        // dd($questionDataLoad);

            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S2Q6_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-6')->first()->code;
            $correctFlag;
            $passFail;

            if ($question->getCorrectChoice() == $userAnswer)
            {
                $correctFlag = 1;
                $scoring++;
                array_push($correctAnswer,$question->getId());
            }
            else 
            {
                if ($question->getCorrectChoice() == null)
                    $correctFlag = null;
                else 
                    {   $correctFlag = 0;
                        array_push($incorrectAnswer,$question->getId());
                    }
            }

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',6)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',6)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_06',
                     'question_id'=>$question->getId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                    ]
                );
            }
            else 
            {
                AnswerRecord::insert(
                    ['examinee_number' => substr($userID, 1), 
                     'level' => 5,
                     'section'=> 2,
                     'question' => 6,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_06',
                     'question_id'=>$question->getId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }

        Q5Section2Question6::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section2Question6::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section2Question6::raw("correct_testee_number + 1")
        ]);
        Q5Section2Question6::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section2Question6::raw("past_testee_number + 1")
        ]);

        $s2Q6Rate = round($scoring * 100 / 1);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q6_correct' => $scoring,
            's2_q6_question' => 1,
            's2_q6_perfect_score' => 11,
            's2_q6_anchor_pass' => $anchorFlag,
            's2_q6_rate' => $s2Q6Rate,
            's2_end_flag' => 1]
        );
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q5S2Q6'))
                {
                    if ($key !== 'Q5S2Q6Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ5S2Q6 = $scoring;
        // Session::put('Q5S1Q1Score',$scoreQ5S1Q1);    
        Session::put($userID.'.Q5S2Q6Score', $scoreQ5S2Q6);

        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 3]);

        return Redirect::to(url('/Q5S3Start'));
    }

   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S2Q6_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question5Choice = $currentId.".Q5S2Q6_".$questionNumber;

        $sess = Session::get($section2Question5Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 

        $results = Q5Section2Question6::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->orWhere('new_question', 1)->get();
        $groupByTextNumberArray = [];

        foreach ($results as $question) {
            $value = new Q5S2Q6($question->id,$question->question_id,$question->theme,$question->past_testee_number,$question->correct_testee_number,$question->title,
            $question->explanation_text,$question->illustration,$question->question,$question->choice_a,$question->choice_b,$question->choice_c,$question->choice_d,$question->correct_answer);
            array_push($valueArray,$value);

        }
        // dd($groupByTextNumberArray);
        foreach($valueArray as $key=>$elements)
        {
            $valueKey = $key;
            $elements->setQuestionId(30);
        }
        return $valueArray;
    }

    function hasDupe($array) {
        return count($array) !== count(array_unique($array));
    }

    public function paginate($items, $perPage = 2, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    function searchForId($id, $array) {
        foreach ($array as $key => $val) {
            if ($val->getId() === $id) {
                return $key;
            }
        }
        return null;
     }
}