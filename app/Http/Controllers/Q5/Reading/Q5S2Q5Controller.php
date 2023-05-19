<?php

namespace App\Http\Controllers\Q5\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question5;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q5\Reading\Q5S2Q5;
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
use Illuminate\Support\Facades\File;
use DateTime;

class Q5S2Q5Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question5Id = $currentId.".Q5S2Q5";

        $dt = new DateTime();
        $result = $dt->format('Y-m-d');
        $userIDNum = substr($currentId, 1);
        $folderPath = base_path().'/testerLog'.'/'.$result.'/'.$userIDNum.'.txt';
        file_put_contents($folderPath,"Q5S2Q5 question search \n",FILE_APPEND);

        // if (!(Session::has($section2Question5Id))) {
            $questionData = $this->showDataBase();
            Session::put($section2Question5Id, $questionData);
        // }

        file_put_contents($folderPath,"User ID no ".$userIDNum." start the 5QS2Q5. \n",FILE_APPEND);

        $questionData = Session::get($section2Question5Id);
        $questionList = $questionData[0];
        $questionText = $questionList->getText();
        
        return view('Q5\Reading\pageQ5S2Q5', compact('questionData','questionText'));

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
        $section2Question5Id = $userID.".Q5S2Q5";
        $questionDataLoad = Session::get($section2Question5Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S2Q5_'.$questionId;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',5)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',5)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_05',
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
                     'level' => 5,
                     'section'=> 2,
                     'question' => 5,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_05',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
        }

        Q5Section2Question5::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section2Question5::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section2Question5::raw("correct_testee_number + 1")
        ]);
        Q5Section2Question5::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section2Question5::raw("past_testee_number + 1")
        ]);

        $s2Q5Rate = round($scoring * 100 / 2);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q5_correct' => $scoring,
            's2_q5_question' => 2,
            's2_q5_perfect_score' => 16,
            's2_q5_anchor_pass' => $anchorFlag,
            's2_q5_rate' => $s2Q5Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q5S2Q5'))
                {
                    if ($key !== 'Q5S2Q5Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ5S2Q5 = $scoring;
        Session::put($userID.'.Q5S2Q5Score', $scoreQ5S2Q5);

        return Redirect::to(url('/Q5ReadingQ6'));

    }

   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S2Q5_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question5Choice = $currentId.".Q5S2Q5_".$questionNumber;

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

        $results = Q5Section2Question5::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->orWhere('new_question', 1)->get();
        $groupByTextNumberArray = [];

        foreach ($results as $question) {
            $value = new Q5S2Q5($question->id,$question->question_id,$question->question_analysis,$question->theme,$question->past_testee_number,$question->correct_testee_number,$question->text_number,
            $question->text,$question->question,$question->choice_a,$question->choice_b,$question->choice_c,$question->choice_d,$question->correct_answer,$question->new_question);
            array_push($valueArray,$value);

            $idQuestion = $question->id;
            $textNumberGroup = $question->text_number;

            if(array_key_exists($textNumberGroup,$groupByTextNumberArray))
                array_push($groupByTextNumberArray[$textNumberGroup],$value);
            else 
                $groupByTextNumberArray[$textNumberGroup] = [$value];    
        }
        // dd($groupByTextNumberArray);
        $questionListKey= array_rand($groupByTextNumberArray, 1);
        $questionList = $groupByTextNumberArray[$questionListKey];
        // dd($aabQuestion,$aacQuestion,$abcQuestion,$bccQuestion,$accQuestion);
        // dd($abQuestion);
        // dd(array_values($groupByTextNumberArray));
        foreach($questionList as $key=>$elements)
        {
            $valueKey = $key;
            $elements->setQuestionId($key+20);
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