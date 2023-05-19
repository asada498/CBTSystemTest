<?php

namespace App\Http\Controllers\Q1\Vocabulary;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q1\Vocabulary\Q1Section1Question8;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q1\Vocabulary\Q1S1Q8;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;
use Illuminate\Support\Facades\Redirect;

class Q1S1Q8Controller extends Controller
{
    public function showQuestion (){
        
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20061744050201"); 
        }        
        $currentId = Session::get('idTester');
        $section1Question8Id = $currentId.".Q1S1Q8";
        // if (!(Session::has($section1Question10Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question8Id, $questionData);
        // }

        $questionData = Session::get($section1Question8Id);
        $data = $this->paginate($questionData);
        //dd($data);
        
        return view('Q1\Vocabulary\paginationQ1S1Q8', compact('data'));
    }

    public function getResultToCalculate (Request $request){        
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;

        if (!(Session::has('idTester')))
                {
                    Session::put('idTester', "Q20061744050201");        
                }
        $userID = Session::get('idTester');
        
        $section1Question8Id = $userID.".Q1S1Q8";
        $questionDataLoad = Session::get($section1Question8Id);        
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q1S1Q8_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-6')->first()->code;
            // $correctFlag;
            // $passFail;

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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',8)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',8)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_11_08',
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
                     'level' => 1,
                     'section'=> 1,
                     'question' => 8,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_11_08',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
        }

        Q1Section1Question8::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q1Section1Question8::raw("past_testee_number + 1"),
            "correct_testee_number" => Q1Section1Question8::raw("correct_testee_number + 1")
        ]);
        Q1Section1Question8::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q1Section1Question8::raw("past_testee_number + 1")
        ]);

        
        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q1Section1Question8::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q1Section1Question8::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }
        
        $s1Q8Rate = round($scoring * 100 / 4);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q8_correct' => $scoring,
            's1_q8_question' => 4,
            's1_q8_perfect_score' => 10/70*60,
            's1_q8_anchor_pass' => $anchorFlag,
            's1_q8_rate' => $s1Q8Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q1S1Q8'))
                {
                    if ($key !== 'Q1S1Q8Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ1S1Q8 = $scoring;
        Session::put($userID.'.Q1S1Q8Score', $scoreQ1S1Q8);

        return Redirect::to(url('/Q1VocabularyQ9'));

    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        // error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q1S1Q8_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question8Choice = $currentId.".Q1S1Q8_".$questionNumber;

        $sess = Session::get($section1Question8Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $classVocabularyTheme =['1000','2000','2800','2900','2905','3100','3105','3200','3205','3300',
        '3305','3307','3600','3603','3605','3607','3700','3800','4000','4400','4500','4600','4800',
        '4900','4905','5000','5100','5200','5300','5400','5800','5900','5905','6000','6700','6705','6800',
        '6805','7200','7500','7600','7700','7800','7900','8000','9000','9100'];        
        

        $results = Q1Section1Question8::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->whereIn('class_reading_theme', $classVocabularyTheme)
        ->get();        

        
        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];         
        $newQuestion = null;

        foreach($array as $val)
        { 
            $value = new Q1S1Q8(
                $val['id'],
                $val['question_id'],
                $val['class_reading_theme'],
                $val['past_testee_number'],
                $val['correct_testee_number'],
                $val['text'],
                $val['question'],
                $val['choice_a'],
                $val['choice_b'],
                $val['choice_c'],
                $val['choice_d'],
                $val['correct_answer'],
                $val['letter'],
                $val['new_question']
            ); 

            if($val['new_question']==1 and $newQuestion==null)
            { 
                array_push($resultarray, $value);
                break;
            }                            
            
        }
        
        foreach($array as $val)
        { 
            $value = new Q1S1Q8(
                $val['id'],
                $val['question_id'],
                $val['class_reading_theme'],
                $val['past_testee_number'],
                $val['correct_testee_number'],
                $val['text'],
                $val['question'],
                $val['choice_a'],
                $val['choice_b'],
                $val['choice_c'],
                $val['choice_d'],
                $val['correct_answer'],
                $val['letter'],
                $val['new_question']
            );       
                           
            if($val['new_question']==1) continue;
            else
            {
                array_push($resultarray, $value);
                $lengthArr = count($resultarray);
                if($lengthArr==4){break;}
            }                         
           
        }
        
        shuffle($resultarray);
        foreach ($resultarray as $key => $elements) {
            $elements->setQuestionId($key + 46);
        }
        //dd($resultarray);        
        return $resultarray;
             
       
    }

    function hasDupe($array) {
        return count($array) !== count(array_unique($array));
    }

    public function paginate($items, $perPage = 1, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question8Id = $currentId.".Q1S1Q8";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question8Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q1\Vocabulary\paginationDataQ1S1Q8', compact('data'))->render(); //
        }
    }
  
}