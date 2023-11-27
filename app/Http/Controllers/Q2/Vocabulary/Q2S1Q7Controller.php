<?php

namespace App\Http\Controllers\Q2\Vocabulary;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question7;
use App\QuestionClass\Q2\Vocabulary\Q2S1Q7;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class Q2S1Q7Controller extends Controller
{
    public function showQuestion (){        
        
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }
        $currentId = Session::get('idTester');
        $section1Question7Id = $currentId.".Q2S1Q7";  
          
        // if (!(Session::has($section1Question7Id))) {
            $questionData = $this->showDataBase();
            //$questionData2 = $this->showDataBase2();            

            Session::put($section1Question7Id, $questionData);
        // }     
        $questionDataLoad = Session::get($section1Question7Id);
        $data = $this->paginate($questionDataLoad);
        //dd($data);
        return view('Q2\Vocabulary\paginationQ2S1Q7', compact('data'));

    }

    
    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "130test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');        

        $section1Question7Id = $userID.".Q2S1Q7";
       
        $questionDataLoad = Session::get($section1Question7Id);
        Session::put($userID.".Q2S1Q7Score_anchor", 0);
                
        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q7_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-3')->first()->code;
                // $correctFlag;
                // $passFail;
                //dd($question->getAnchor());
                if($question->getAnchor() == 0 or $question->getAnchor()==null ) //if($question->getAnchor() == 1)     
                        $anchorFlag = 0;
                else $anchorFlag = 1;
                if ($question->getCorrectChoice() == $userAnswer)
                {
                    $correctFlag = 1;
                    if ($anchorFlag == 1)
                    {
                        $anchorFlagResult += 1;
                    }
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',7)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',7)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_07',
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
                     'question' => 7,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_21_07',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
               }
            }
            //update record on database
            
            Q2Section1Question7::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question7::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question7::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question7::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question7::raw("past_testee_number + 1")
            ]);
        }

        $s1Q7Rate = round($scoring * 100 / 12);
        Session::put($userID.".Q2S1Q7Score_anchor", 7/45*60/12 * $anchorFlagResult);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q7_correct' => $scoring,
            's1_q7_question' => 12,
            's1_q7_perfect_score' => 7/45*60,
            's1_q7_anchor_pass' => $anchorFlagResult,
            's1_q7_rate' => $s1Q7Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q7'))
                {
                    if ($key !== 'Q2S1Q7Score' && $key !== 'Q2S1Q7Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
            //dd(session());
        $scoreQ2S1Q7 = $scoring;    
        Session::put($userID.'.Q2S1Q7Score', $scoreQ2S1Q7);
        // Session::put('idTester',$userID);    

        error_log($scoreQ2S1Q7);
        return Redirect::to(url('/Q2VocabularyQ8'));

    }
    
    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question7Id = $currentId.".Q2S1Q7";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question7Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q2\Vocabulary\paginationDataQ2S1Q7', compact('data'))->render(); 
        }
    }
    
    public function saveChoiceRequestPost(Request $request)
    {       
            $currentId = Session::get('idTester');
            $questionNumber = $request->get('name');
            // error_log($questionNumber);

            $answer = $request->get('answer');
            $valueSession = $currentId.".Q2S1Q7_".$questionNumber;
            Session::put($valueSession, $answer);
            return response()->json(['success' => $valueSession]);

    }
    
    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question7Choice = $currentId.".Q2S1Q7_".$questionNumber;

        $sess = Session::get($section1Question7Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }
    
    function showDataBase()
    {
        $class_grammar =['010','020','020','020','030','030','030','030','030','040','040','051060090'];
        
        
        $results = Q2Section1Question7::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get(); 

        $array = $results->toArray();
        $resultarray = [];
        foreach($array as $val)
        { 
            $value = new Q2S1Q7(
                $val['id'],
                $val['question_id'],
                $val['grammar'],
                $val['class_grammar'],
                $val['kanji'],
                $val['past_testee_number'],
                $val['correct_testee_number'], 
                $val['anchor'],
                $val['question'],
                $val['choice_a'],
                $val['choice_b'],
                $val['choice_c'],
                $val['choice_d'],
                $val['correct_answer'],
                $val['new_question']                
            );
            array_push($resultarray, $value);        
        }    
        $questionIdArray = [];

        $count = 0;
        while($count==0)
        {
            $questionIdArray = static::getRandomQuestionId($class_grammar, $resultarray);
            $lengClass = count($class_grammar);
            $lengArr = count($questionIdArray);
            
            $kanji = [];
            foreach($questionIdArray as $questionId)
            {
                $idValue = static::searchForId($questionId, $resultarray);
                array_push($kanji, $resultarray[$idValue]->kanji);
            }
            
            //if($lengArr==$lengClass and $this->hasDupe($kanji)==false)
            if($lengArr==$lengClass)
            $count=1;
        }
        $questionList = [];
        foreach($questionIdArray as $questionId)
        {
            $idValue = static::searchForId($questionId, $resultarray);
            array_push($questionList,$resultarray[$idValue]);
        }

        shuffle($questionList);
        foreach ($questionList as $key => $elements) {
            $elements->setQuestionId($key+33);
        } 
        //if($lengthArr>1)dd('database information is not enough for requarement'); 
        return $questionList;    
    }

    function getRandomQuestionId($class_grammar, $array){

        $class_grammar2 =[];

        //listening classdagi ikkita nuqilay ma'lunotni to'g'irlash
        foreach($class_grammar as $class)
        {                
            $len = strlen($class);
            $resultclass=0;
            if($len>3){
                $class1 = substr($class, 0,3);
                $class2 = substr($class, 3, 3);
                $arrclass = [$class1,$class2];
                shuffle($arrclass);
                $resultclass =  $arrclass[0];
            }
            elseif($len>6){
                $class1 = substr($class, 0,3);
                $class2 = substr($class, 3, 3);
                $class3 = substr($class, 6, 3);
                $arrclass = [$class1,$class2,$class3];
                shuffle($arrclass);
                $resultclass =  $arrclass[0];
            }
            else  $resultclass = $class;  
            array_push($class_grammar2,$resultclass);
        }  
           
        shuffle($array);
        $resultarray = [];
        $newQuestion = null;
        $anchor = null;
       
        array_unshift($class_grammar2,'0');
        
        foreach($array as $val)
        { 
            $index = array_search($val->classGrammar, $class_grammar2);
            
            if($index!=null)
            { 
                if($val->newQuestion==1 and $newQuestion==null)
                {  
                    $newQuestion = array_search($val->classGrammar, $class_grammar2);                  
                    unset($class_grammar2[$index]);  
                    array_push($resultarray, $val->id);

                    if($newQuestion!=null and $anchor!=null) break;
                    else continue;
                }

                if($val->anchor==1 and $anchor==null)
                {  
                    $anchor = array_search($val->classGrammar, $class_grammar2);                  
                    unset($class_grammar2[$index]);  
                    array_push($resultarray, $val->id);

                    if($newQuestion!=null and $anchor!=null) break;
                    else continue;
                }
            }
        }

        
        foreach($array as $val)
        { 
            $index = array_search($val->classGrammar, $class_grammar2);
            
            if($index!=null)
            {         
                if($val->newQuestion==1 or $val->anchor==1)
                {
                    continue;
                }                                           
                else
                {
                    array_push($resultarray, $val->id);
                    unset($class_grammar2[$index]);
                    $lengthArr = count($class_grammar2);
                    if($lengthArr==1){break;}
                }           
                                               
            }
        }
              
        return $resultarray;      
       
    }

    public function paginate($items, $perPage = 4, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    

    function hasDupe($kanjiArray)
    {
        // if (count($array) !== count(array_unique($array))) {
        //     return true;
        // }
        $explodeArray = [];
        foreach ($kanjiArray as $results) {

            foreach (explode(",", $results) as $d) {
                array_push($explodeArray, $d);
            }
        }
        if (count($explodeArray) !== count(array_unique($explodeArray))) {
            return true;
        } else {
            return false;
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

    function removeElementFromArray($del_val,$array){
        // dd($del_val,$array);
        if (($key = array_search($del_val, $array)) !== false) {
            unset($array[$key]);
            return $array;
        }
    }
    
    
}