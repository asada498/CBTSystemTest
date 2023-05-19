<?php

namespace App\Http\Controllers\Q2\Vocabulary;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question11;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q2\Vocabulary\Q2S1Q11;
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

class Q2S1Q11Controller extends Controller
{
    public function showQuestion (){
        
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20070144050101"); // Vetnam Hanoi  LEVEL3 0101
        }        
        $currentId = Session::get('idTester');
        $section1Question11Id = $currentId.".Q2S1Q11";
        // if (!(Session::has($section2Question11Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question11Id, $questionData);
        // }

        $questionData = Session::get($section1Question11Id);
        $questionList = $questionData[0];
        $questionText = $questionList->getText();

        
        $data = $this->paginate($questionData);
        //dd($data);
        //mana shu yergacha keldim ertaga qanday qilib viewga yuborishni o'ylayman
        
        return view('Q2\Vocabulary\paginationQ2S1Q11', compact('data'));

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
        
        $section2Question11Id = $userID.".Q2S1Q11";
        $questionDataLoad = Session::get($section2Question11Id);
        
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q2S1Q11_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-5')->first()->code;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',11)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',11)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_21_11',
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
                     'level' => 2,
                     'section'=> 1,
                     'question' => 11,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_21_11',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
        }

        Q2Section1Question11::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q2Section1Question11::raw("past_testee_number + 1"),
            "correct_testee_number" => Q2Section1Question11::raw("correct_testee_number + 1")
        ]);
        Q2Section1Question11::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q2Section1Question11::raw("past_testee_number + 1")
        ]);

        
        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q2Section1Question11::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q2Section1Question11::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }
        
        $s2Q11Rate = round($scoring * 100 / 9);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q11_correct' => $scoring,
            's1_q11_question' => 9,
            's1_q11_perfect_score' => 18/60*60,
            's1_q11_anchor_pass' => $anchorFlag,
            's1_q11_rate' => $s2Q11Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q11'))
                {
                    if ($key !== 'Q2S1Q11Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ2S1Q11 = $scoring;
        Session::put($userID.'.Q2S1Q11Score', $scoreQ2S1Q11);

        return Redirect::to(url('/Q2VocabularyQ12'));

    }

   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        // error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q2S1Q11_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question11Choice = $currentId.".Q2S1Q11_".$questionNumber;

        $sess = Session::get($section2Question11Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $classReadingTheme =['1000','2000','2800','2900','2905','3100','3105','3200','3205','3300',
        '3305','3307','3600','3603','3605','3607','3700','3800','4000','4400','4500','4600','4800',
        '4900','4905','5000','5100','5200','5300','5400','5800','5900','5905','6000','6700','6705',
        '6800','6805','7200','7500','7600','7700','7800','7900','8000','9000','9100'];        
        

        $results = Q2Section1Question11::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->whereIn('class_reading_theme', $classReadingTheme)
        ->get();        

        
        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];         

        $groupByTextNumberArray = [];
        foreach($array as $val)
        { 
            $value = new Q2S1Q11(
                $val['id'],
                $val['question_id'],
                $val['class_reading_theme'],
                $val['past_testee_number'],
                $val['correct_testee_number'],
                $val['same_passage'],
                $val['text'],
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

        

        //dd($groupByTextNumberArray);
        $classReadingTheme = [];
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
                           array_push($classReadingTheme, $val[$randArrId[$i]]->classReadingTheme);
                        }
                    }
                    else
                    {
                        array_splice($randArrId,1,1,$newQuestionId);// array, start, length, array
                        for($i=0; $i<=2; $i++)
                        {
                           array_push($resultarray, $val[$randArrId[$i]]);
                           array_push($classReadingTheme, $val[$randArrId[$i]]->classReadingTheme);
                        } 
                    }
                    
                }
                if($resultarray!=null)
                {
                    unset($groupByTextNumberArray[$val[0]->samePassage]);
                    break;
                }
                
            }
            
        }

        foreach($groupByTextNumberArray as $val)
        {
            if(count($val)>=3 and array_search($val[0]->classReadingTheme, $classReadingTheme) == false)
            {                
                $randArrId = array_rand($val,3);
                for($i=0; $i<=2; $i++)
                {
                    array_push($resultarray, $val[$randArrId[$i]]);
                    array_push($classReadingTheme, $val[$randArrId[$i]]->classReadingTheme);
                } 
                if(count($resultarray)>=9)
                break;
            }    
            
        }    

        foreach ($resultarray as $key => $elements) {
            $elements->setQuestionId($key + 60);
        }
        
        return $resultarray;      
       
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
        $section2Question11Id = $currentId.".Q2S1Q11";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section2Question11Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q2\Vocabulary\paginationDataQ2S1Q11', compact('data'))->render(); //
        }
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