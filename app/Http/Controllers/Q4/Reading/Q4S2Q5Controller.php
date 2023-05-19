<?php

namespace App\Http\Controllers\Q4\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question5;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q4\Reading\Q4S2Q5;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Session;
use Illuminate\Support\Facades\Config;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;
use Illuminate\Support\Facades\Redirect;

class Q4S2Q5Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question5Id = $currentId.".Q4S2Q5";
        // if (!(Session::has($section2Question5Id))) {
            $questionData = $this->showDataBase();
            Session::put($section2Question5Id, $questionData);
        // }

        $questionData = Session::get($section2Question5Id);
        $questionList = $questionData[0];
        $questionText = $questionList->getText();
        
        return view('Q4\Reading\pageQ4S2Q5', compact('questionData','questionText'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;

        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        $section2Question5Id = $userID.".Q4S2Q5";
        $questionDataLoad = Session::get($section2Question5Id);
        if ($questionDataLoad == null)
            dd(session()->all());
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q4S2Q5_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-5')->first()->code;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',5)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',5)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_42_05',
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
                     'question' => 5,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_42_05',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
        }

        Q4Section2Question5::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q4Section2Question5::raw("past_testee_number + 1"),
            "correct_testee_number" => Q4Section2Question5::raw("correct_testee_number + 1")
        ]);
        Q4Section2Question5::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q4Section2Question5::raw("past_testee_number + 1")
        ]);

        $s2Q5Rate = round($scoring * 100 / 3);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q5_correct' => $scoring,
            's2_q5_question' => 3,
            's2_q5_perfect_score' => 11.5 / 80 * 120 ,
            's2_q5_anchor_pass' => $anchorFlag,
            's2_q5_rate' => $s2Q5Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q4S2Q5'))
                {
                    if ($key !== 'Q4S2Q5Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ4S2Q5 = $scoring;
        Session::put($userID.'.Q4S2Q5Score', $scoreQ4S2Q5);

        return Redirect::to(url('/Q4ReadingQ6'));

    }

   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q4S2Q5_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question5Choice = $currentId.".Q4S2Q5_".$questionNumber;

        $sess = Session::get($section2Question5Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 
        $particalId = [];
        $conjugationConnectionId = [];
        $sentencePatternsId = [];
        $anchorId = [];

        $results = Q4Section2Question5::where('usable', 1)->where('not_in_use',0)->whereBetween('correct_answer_rate',[0.2,0.8])->get();
        $groupByTextNumberArray = [];

        foreach ($results as $question) {
            $value = new Q4S2Q5(
                $question->id,
                $question->question_id,
                $question->class_reading_theme,
                $question->class_reading,
                $question->past_testee_number,
                $question->correct_testee_number,
                $question->same_passage,
                $question->text,
                $question->question,
                $question->choice_a,
                $question->choice_b,
                $question->choice_c,
                $question->choice_d,
                $question->correct_answer,
                $question->new_question);
            array_push($valueArray,$value);

            $idQuestion = $question->id;
            $textNumberGroup = $question->same_passage;

            if(array_key_exists($textNumberGroup,$groupByTextNumberArray))
                array_push($groupByTextNumberArray[$textNumberGroup],$value);
            else 
                $groupByTextNumberArray[$textNumberGroup] = [$value];    
        }
        // dd($groupByTextNumberArray);
        $questionListKey= array_rand($groupByTextNumberArray, 1);
        $questionList = $groupByTextNumberArray[$questionListKey];

        foreach($questionList as $key=>$elements)
        {
            $valueKey = $key;
            $elements->setQuestionId($key+25);
        }

        return $questionList;
    }

    function hasDupe($array) {
        return count($array) !== count(array_unique($array));
    }

    public function paginate($items, $perPage = 3, $page = null, $options = [])
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