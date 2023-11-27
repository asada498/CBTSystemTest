<?php

namespace App\Http\Controllers\Q4\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question6;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q4\Reading\Q4S2Q6;
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
use App\Grades;

class Q4S2Q6Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question6Id = $currentId.".Q4S2Q6";
        // if (!(Session::has($section2Question6Id))) {
            $questionData = $this->showDataBase();
            Session::put($section2Question6Id, $questionData);
        // }

        $questionData = Session::get($section2Question6Id);
        $questionList = $questionData[0];
        // dd($questionList,$questionData);
        // $questionText = $questionList[0]->getText();
        
        return view('Q4\Reading\pageQ4S2Q6', compact('questionData','questionList'));

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
        $section2Question6Id = $userID.".Q4S2Q6";
        $questionDataLoad = Session::get($section2Question6Id);
        // dd($questionDataLoad);
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q4S2Q6_'.$questionId;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',6)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',6)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_42_06',
                     'question_id'=>$question->getDatabaseQuestionId(),
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
                     'level' => 4,
                     'section'=> 2,
                     'question' => 6,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_42_06',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
        }
        Q4Section2Question6::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q4Section2Question6::raw("past_testee_number + 1"),
            "correct_testee_number" => Q4Section2Question6::raw("correct_testee_number + 1")
        ]);
        Q4Section2Question6::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q4Section2Question6::raw("past_testee_number + 1")
        ]);

        $s2Q6Rate = round($scoring * 100 / 2);
        $s2Q1Correct = Session::get($userID.".Q4S2Q1Score");
        $s2Q2Correct = Session::get($userID.".Q4S2Q2Score");
        $s2Q3Correct = Session::get($userID.".Q4S2Q3Score");
        $s2Q4Correct = Session::get($userID.".Q4S2Q4Score");
        $s2Q5Correct = Session::get($userID.".Q4S2Q5Score");
        $s2Q6Correct = $scoring;
        $section2Total = $s2Q1Correct / 13 * 13.5 / 80 * 120 +
                         $s2Q2Correct / 4 * 5.5 / 80 * 120 +
                         $s2Q3Correct / 4 * 8 / 80 * 120 +
                         $s2Q4Correct / 3 * 10.5 / 80 * 120 +
                         $s2Q5Correct / 3 * 11.5 / 80 * 120 +
                         $s2Q6Correct / 2 * 6 / 80 * 120;
        $s2Rate = ($s2Q1Correct+ $s2Q2Correct+ $s2Q3Correct+ $s2Q4Correct+ $s2Q5Correct + $s2Q6Correct)/(13+4+4+3+3+2);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q6_correct' => $scoring,
            's2_q6_question' => 2,
            's2_q6_perfect_score' => 6 / 80 * 120,
            's2_q6_anchor_pass' => $anchorFlag,
            's2_q6_rate' => $s2Q6Rate,
            's2_end_flag' => 1,
            's2_rate'=>$s2Rate,
            's2_score' => $section2Total]
        );
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q4S2Q6'))
                {
                    if ($key !== 'Q4S2Q6Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $anchorScoreQ4S1Q1 =  Session::get( $userID.'.Q4S1Q1Score_anchor');
        $anchorScoreQ4S1Q3 =  Session::get( $userID.'.Q4S1Q3Score_anchor');
        $anchorScoreQ4S2Q1 =  Session::get( $userID.'.Q4S2Q1Score_anchor');
        $currentAnchorScore = $anchorScoreQ4S1Q1+$anchorScoreQ4S1Q3+$anchorScoreQ4S2Q1;
        $currentAnchorPassRate = round($currentAnchorScore /
                                        (5.25 / 80 * 120 / 7 +
                                        6    / 80 * 120 / 8 +
                                        13.5 / 80 * 120 / 13 * 2 +
                                        10.5 / 35 * 60 / 8 +
                                        10.5 / 35 * 60 / 7 +
                                        7.5  / 35 * 60 / 8)
                                        * 100);
        Grades::where('examinee_number', substr($userID, 1))->where('level', 4)->update([
            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec2_score' => $section2Total
            ]);
        $scoreQ4S2Q6 = $scoring;
        // Session::put('Q4S1Q1Score',$scoreQ4S1Q1);    
        Session::put($userID.'.Q4S2Q6Score', $scoreQ4S2Q6);

        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 3]);

        return Redirect::to(url('/Q4S3Start'));
    }

   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q4S2Q6_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question5Choice = $currentId.".Q4S2Q6_".$questionNumber;

        $sess = Session::get($section2Question5Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 

        $results = Q4Section2Question6::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->where('same_passage', '!=' , 0)->get();
        $groupByTextNumberArray = [];

        foreach ($results as $question) {
            $value = new Q4S2Q6(
                $question->id,
                $question->question_id,
                $question->past_testee_number,
                $question->correct_testee_number,
                $question->title,
                $question->explanation_text,
                $question->illustration,
                $question->same_passage,
                $question->question,
                $question->choice_a,
                $question->choice_b,
                $question->choice_c,
                $question->choice_d,
                $question->correct_answer);
            // array_push($valueArray,$value);
            $idQuestion = $question->id;
            $textNumberGroup = $question->same_passage;            
            if(array_key_exists($textNumberGroup,$groupByTextNumberArray))
                array_push($groupByTextNumberArray[$textNumberGroup],$value);
            else 
                $groupByTextNumberArray[$textNumberGroup] = [$value]; 
        }

        $counter = 0;
        $questionList = [];
        while($counter == 0)
        {
            $questionListKey= array_rand($groupByTextNumberArray, 1);
            $questionList = $groupByTextNumberArray[$questionListKey];
            if (count($questionList) == 2)
                $counter = 1;
        }
        // $questionListKey= array_rand($valueArray, 1);
        // $questionList = $valueArray[$questionListKey];

        //     $questionList->setQuestionId("32");
        // return $questionList;
        // $questionListKey= array_rand($groupByTextNumberArray, 1);
        // $questionList = $groupByTextNumberArray[$questionListKey];

        foreach($questionList as $key=>$elements)
        {
            $valueKey = $key;
            $elements->setQuestionId($key+28);
        }
        return $questionList;
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