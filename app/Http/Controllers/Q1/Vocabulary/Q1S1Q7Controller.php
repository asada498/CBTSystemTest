<?php

namespace App\Http\Controllers\Q1\Vocabulary;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q1\Vocabulary\Q1Section1Question7;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q1\Vocabulary\Q1S1Q7;
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

class Q1S1Q7Controller extends Controller
{
    public function showQuestion (){
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }
        $currentId = Session::get('idTester');
        $section1Question7Id = $currentId.".Q1S1Q7";
        // if (!(Session::has($section2Question3Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question7Id, $questionData);
        // }

        $questionData = Session::get($section1Question7Id);
        
        $questionText = $questionData[0]->getText();
        //dd($currentId,$questionData,$questionText, session());
        return view('Q1\Vocabulary\paginationQ1S1Q7', compact('questionData','questionText'));

    }

    function showDataBase()
    {
        $classReadingTheme =['1000','2000','2800','2900','2905','3100','3105','3200','3205','3300',
        '3305','3307','3600','3603','3605','3607','3700','3800','4000','4400','4500','4600','4800',
        '4900','4905','5000','5100','5200','5300','5400','5800','5900','5905','6000','6700','6705',
        '6800','6805','7200','7500','7600','7700','7800','7900','8000','9000','9100'];   
        
        //$classGrammar=['040','040','030060','050','051'];
        //savol kamligi uchun classdan foydalanmayapmiz
        $classGrammar=['040','040','030060','030060','050051'];

        $results = Q1Section1Question7::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->whereIn('class_reading_theme', $classReadingTheme)
        ->orderBy('question')
        ->get();  
        $array = $results->toArray();
        $resultarray = [];     
        foreach($array as $val)
        { 
            $value = new Q1S1Q7(
                $val['id'],
                $val['question_id'],
                $val['grammar'],
                $val['class_grammar'],
                $val['correct_answer_rate'],
                $val['past_testee_number'],
                $val['correct_testee_number'],
                $val['question'],
                $val['text'],
                $val['same_passage'],                 
                $val['choice_a'],
                $val['choice_b'],
                $val['choice_c'],
                $val['choice_d'],
                $val['correct_answer'],
                $val['new_question']
            ); 
            array_push($resultarray, $value);
        }    
        
        $count=0;
        while($count==0)
        {
            $questionIdArray = static::getRandomQuestionId($resultarray, $classGrammar);
            
            $lengClass = count($classGrammar);
            $lengArr = count($questionIdArray);
            
            if($lengArr==$lengClass)
            $count=1;
        }
        
        //dd($questionIdArray);
        return $questionIdArray;        
         
    }

    function getRandomQuestionId($array, $classGrammar)
    {
        $classGrammar2 = [];

        foreach($classGrammar as $class)
        {                
            $len = strlen($class);
            $resultclass=0;
            if($len>3)
            {
                $class1 = substr($class, 0,3);
                $class2 = substr($class, 3, 3);
                $arrclass = [$class1,$class2];
                shuffle($arrclass);
                $resultclass =  $arrclass[0];
            }
            else  $resultclass = $class;  
            array_push($classGrammar2,$resultclass);
        }
        //dd($classGrammar2);
        //shuffle($array);
        $resultarray = [];     
        $groupByTextNumberArray = [];
        foreach($array as $val)
        { 
            $textNumberGroup = $val->textNumber;

            if(array_key_exists($textNumberGroup,$groupByTextNumberArray))
                array_push($groupByTextNumberArray[$textNumberGroup],$val);
            else 
                $groupByTextNumberArray[$textNumberGroup] = [$val];
        }
        //dd($classGrammar2,$groupByTextNumberArray);
        //mana shu siklga men class grammarga javob beradiganlarni qoldirishim kerak
        
        // $classGrammar3=[];
        // foreach($groupByTextNumberArray as $val)
        // {
        //     if(count($val)>=5)
        //     {                
        //         $classGrammar3=$classGrammar2;
        //         array_unshift($classGrammar3,'0');
        //         for($i=0; $i<count($val); $i++)
        //         {
        //             $index = array_search($val[$i]->classGrammar, $classGrammar3);
        //             if($index!=null)
        //             {
        //                 unset($classGrammar3[$index]);
        //             }
        //         }
        //         if(count($classGrammar3)>1)
        //         {
        //             unset($groupByTextNumberArray[$val[0]->textNumber]);
        //         }
        //     }
        //     else
        //     {
        //         unset($groupByTextNumberArray[$val[0]->textNumber]);                
        //     }
        // }

        //dd($groupByTextNumberArray);       
        // ma shu sikni hammasi bitta newQuestion uchun
        foreach($groupByTextNumberArray as $val)
        { 
            if(count($val)>=5)
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
                    $randArrId = array_rand($val,5);
                    if(array_search($newQuestionId, $randArrId) !== null)
                    {
                        for($i=0; $i<=4; $i++)
                        {
                           array_push($resultarray, $val[$randArrId[$i]]);
                        }
                    }
                    else
                    {
                        array_splice($randArrId,1,1,$newQuestionId);// array, start, length, array
                        for($i=0; $i<=4; $i++)
                        {
                           array_push($resultarray, $val[$randArrId[$i]]);
                        } 
                    }
                    
                }
                if($resultarray!=null)
                {
                    unset($groupByTextNumberArray[$val[0]->textNumber]);
                    break;
                }
                
            }
            
        }
        shuffle($groupByTextNumberArray);
        if($resultarray==null)
        {
            foreach($groupByTextNumberArray as $val)
            {
                if(count($val)>=5)
                {                
                    $randArrId = array_rand($val,5);
                    for($i=0; $i<=4; $i++)
                    {
                        array_push($resultarray, $val[$randArrId[$i]]);
                    }
                    break;
                }    
                
            }    
        }
        

        foreach ($resultarray as $key => $elements) {
            //$elements->setQuestionId($key + 41);
            $elements->setQuestionId($elements->question);
        }
        //dd($resultarray);       
        return $resultarray; 
    }

    public function saveChoiceRequestPost(Request $request)
    {       
            $currentId = Session::get('idTester');
            $questionNumber = $request->get('name');
            // error_log($questionNumber);

            $answer = $request->get('answer');
            $valueSession = $currentId.".Q1S1Q7_".$questionNumber;
            Session::put($valueSession, $answer);
            return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question7Choice = $currentId.".Q1S1Q7_".$questionNumber;

        $sess = Session::get($section1Question7Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
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
        $section1Question7Id = $userID.".Q1S1Q7";
        $questionDataLoad = Session::get($section1Question7Id);
        foreach ($questionDataLoad as $question) {

            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q1S1Q7_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-3')->first()->code;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',7)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',7)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_11_07',
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
                     'question' => 7,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_11_07',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
            // }
        }

        Q1Section1Question7::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q1Section1Question7::raw("past_testee_number + 1"),
            "correct_testee_number" => Q1Section1Question7::raw("correct_testee_number + 1")
        ]);
        Q1Section1Question7::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q1Section1Question7::raw("past_testee_number + 1")
        ]);

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q1Section1Question7::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q1Section1Question7::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q7Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q7_correct' => $scoring,
            's1_q7_question' => 5,
            's1_q7_perfect_score' => 7/40*60,
            's1_q7_anchor_pass' => $anchorFlag,
            's1_q7_rate' => $s1Q7Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q1S1Q7'))
                {
                    if ($key !== 'Q1S1Q7Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ1S1Q7 = $scoring;
        Session::put($userID.'.Q1S1Q7Score', $scoreQ1S1Q7);

        return Redirect::to(url('/Q1VocabularyQ8'));

    }

    /*

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
        $section2Question3Id = $userID.".Q3S2Q3";
        $questionDataLoad = Session::get($section2Question3Id);
        foreach ($questionDataLoad as $question) {
            // dd($questionPack);
            // foreach($questionPack as $question)
            // {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q3S2Q3_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-3')->first()->code;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',3)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',3)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_32_03',
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
                     'level' => 3,
                     'section'=> 2,
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_32_03',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
            // }
        }

        Q3Section2Question3::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q3Section2Question3::raw("past_testee_number + 1"),
            "correct_testee_number" => Q3Section2Question3::raw("correct_testee_number + 1")
        ]);
        Q3Section2Question3::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q3Section2Question3::raw("past_testee_number + 1")
        ]);

        foreach($correctAnswer as $correct)
        {
            $question = Q3Section2Question3::where('id', $correct)->first();
            if($question->new_question and $question->past_testee_number >= 400)
            {
                $question->new_question=0;
                $question->save();
            }
        }

        foreach($incorrectAnswer as $incorrect)
        {
            $question = Q3Section2Question3::where('id', $incorrect)->first();
            if($question->new_question and $question->past_testee_number >= 400)
            {
                $question->new_question=0;
                $question->save();
            }
        }

        $s2Q3Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q3_correct' => $scoring,
            's2_q3_question' => 5,
            's2_q3_perfect_score' => 12,
            's2_q3_anchor_pass' => $anchorFlag,
            's2_q3_rate' => $s2Q3Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S2Q3'))
                {
                    if ($key !== 'Q3S2Q3Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ3S2Q3 = $scoring;
        Session::put($userID.'.Q3S2Q3Score', $scoreQ3S2Q3);

        return Redirect::to(url('/Q3ReadingQ4'));

    }

    function hasDupe($array) {
        return count($array) !== count(array_unique($array));
    }

    function correctAnswerRateInRage($array) {
        foreach ($array as $value) {
            if ( (0.2 > $value) || ($value > 0.8))
                return false;
        }    
        return true;
    }

    public function paginate($items, $perPage = 2, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    */
    
}