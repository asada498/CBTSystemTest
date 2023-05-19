<?php

namespace App\Http\Controllers\Q1\Vocabulary;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q1\Vocabulary\Q1Section1Question11;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q1\Vocabulary\Q1S1Q11;
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

class Q1S1Q11Controller extends Controller
{
    public function showQuestion (){

        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20061744050201"); // Vetnam Hanoi  LEVEL3 0101
        }        
        $currentId = Session::get('idTester');
        $section1Question11Id = $currentId.".Q1S1Q11";
        // if (!(Session::has($section2Question5Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question11Id, $questionData);
        // }

        $data = Session::get($section1Question11Id);
        //dd($data);
        //mana shu yergacha keldim ertaga qanday qilib viewga yuborishni o'ylayman

        return view('Q1\Vocabulary\paginationQ1S1Q11', compact('data'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;

        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "Q20061744050201";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');

        $section1Question11Id = $userID.".Q1S1Q11";
        $questionDataLoad = Session::get($section1Question11Id);
          
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q1S1Q11_'.$questionId;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',11)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',11)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_11_11',
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
                     'question' => 11,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_11_11',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
        }

        Q1Section1Question11::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q1Section1Question11::raw("past_testee_number + 1"),
            "correct_testee_number" => Q1Section1Question11::raw("correct_testee_number + 1")
        ]);
        Q1Section1Question11::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q1Section1Question11::raw("past_testee_number + 1")
        ]);


        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q1Section1Question11::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q1Section1Question11::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q11Rate = round($scoring * 100 / 3);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q11_correct' => $scoring,
            's1_q11_question' => 3,
            's1_q11_perfect_score' => 11/70*60,
            's1_q11_anchor_pass' => $anchorFlag,
            's1_q11_rate' => $s1Q11Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q1S1Q11'))
                {
                    if ($key !== 'Q1S1Q11Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;

                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ1S1Q11 = $scoring;
        Session::put($userID.'.Q1S1Q11Score', $scoreQ1S1Q11);

        return Redirect::to(url('/Q1VocabularyQ12'));

    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q1S1Q11_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question11Choice = $currentId.".Q1S1Q11_".$questionNumber;

        $sess = Session::get($section1Question11Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $classReadingTheme =['1000','2000','2800','2900','2905','3100','3105','3200','3205','3300',
        '3305','3307','3600','3603','3605','3607','3700','3800','4000','4400','4500','4600','4800',
        '4900','4905','5000','5100','5200','5300','5400','5800','5900','5905','6000','6700','6800',
        '6805','7200','7500','7600','7700','7800','7900','8000','9000','9100'];        


        $results = Q1Section1Question11::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->whereIn('class_reading_theme', $classReadingTheme)
        ->get();        


        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];         


        $groupByTextNumberArray = [];
        foreach($array as $val)
        { 
            $value = new Q1S1Q11(
                $val['id'],
                $val['question_id'],
                $val['class_reading_theme'],
                $val['past_testee_number'],
                $val['correct_testee_number'],
                $val['same_passage'],
                $val['question_number'],
                $val['title'],
                $val['textA'],
                $val['textB'],
                $val['question'],
                $val['choice_a'],
                $val['choice_b'],
                $val['choice_c'],
                $val['choice_d'],
                $val['correct_answer'],
                $val['new_question']
            );         

            $textNumberGroup = $val['same_passage'];

            if(array_key_exists($textNumberGroup,$groupByTextNumberArray))
                array_push($groupByTextNumberArray[$textNumberGroup],$value);
            else 
                $groupByTextNumberArray[$textNumberGroup] = [$value];
        }

        foreach($groupByTextNumberArray as $val)
        { 
            if(count($val)>=3)
            { 
                $newQuestionId=999;
                for($i=0; $i<count($val); $i++)
                {                    
                    if($val[$i]->newQuestion==1)
                    {
                        $newQuestionId = $i; 
                        break;                       
                    } 
                }
                if($newQuestionId!=999)
                {
                    $randArrId = array_rand($val,3);
                    if(array_search($newQuestionId, $randArrId) !== null)
                    {
                        for($i=0; $i<=2; $i++)
                        {
                           array_push($resultarray, $val[$randArrId[$i]]);
                        }
                    }
                    else
                    {
                        array_splice($randArrId,1,1,$newQuestionId);// array, start, length, array
                        for($i=0; $i<=2; $i++)
                        {
                           array_push($resultarray, $val[$randArrId[$i]]);
                        } 
                    }

                }
                if($resultarray!=null)
                {
                    break;
                }

            }

        }
        if($resultarray==null)
        {
            foreach($groupByTextNumberArray as $val)
            {
                if(count($val)>=3)
                {                
                    $randArrId = array_rand($val,3);
                    for($i=0; $i<=2; $i++)
                    {
                        array_push($resultarray, $val[$randArrId[$i]]);
                    }                    
                    break;
                }    

            }    
        }
       
        foreach ($resultarray as $elements) {
            $elements->setQuestionId($elements->questionNumber);
        }
        
        // we have to sort this array
        $resultarray2 = [1,2,3];
        foreach ($resultarray as $elements) {
            if($elements->questionId==63){$resultarray2[0] = $elements;}
            elseif($elements->questionId==64){$resultarray2[1] = $elements;}
            elseif($elements->questionId==65){$resultarray2[2] = $elements;}
        }  
        
        //dd($resultarray, $resultarray2);
        return $resultarray2;


    }

    function cmp($a, $b) {
        return strcmp($a->questionNumber, $b->questionNumber);
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

    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question11Id = $currentId.".Q1S1Q11";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question11Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q1\Vocabulary\paginationDataQ1S1Q11', compact('data'))->render(); //
        }
    }
} 