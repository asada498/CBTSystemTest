<?php

namespace App\Http\Controllers\Q2\Vocabulary;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question5;
use App\QuestionClass\Q2\Vocabulary\Q2S1Q5;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class Q2S1Q5Controller extends Controller
{
    public function showQuestion (){        
        
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }
        $currentId = Session::get('idTester');
        $section1Question5Id = $currentId.".Q2S1Q5";  
          
        //if (!(Session::has($section1Question3Id))) {
            $questionData = $this->showDataBase();
            //$questionData2 = $this->showDataBase2();            

            Session::put($section1Question5Id, $questionData);
        //}     
        $data = Session::get($section1Question5Id);
        
        //$data = $this->paginate($questionDataLoad);
        //dd($data);
        return view('Q2\Vocabulary\paginationQ2S1Q5', compact('data'));

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

        $section1Question5Id = $userID.".Q2S1Q5";
       
        $questionDataLoad = Session::get($section1Question5Id);
        // Session::put($userID.".Q2S1Q5Score_anchor", 0);
                
        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q5_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-3')->first()->code;
                // $correctFlag;
                // $passFail;
                //dd($question->getAnchor());
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',5)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',5)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_05',
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
                     'question' => 5,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_21_05',
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
            
            Q2Section1Question5::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question5::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question5::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question5::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question5::raw("past_testee_number + 1")
            ]);
        }

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q2Section1Question5::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q2Section1Question5::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q3Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q5_correct' => $scoring,
            's1_q5_question' => 5,
            's1_q5_perfect_score' => 5/45*60,
            's1_q5_anchor_pass' => $anchorFlagResult,
            's1_q5_rate' => $s1Q3Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q5'))
                {
                    if ($key !== 'Q2S1Q5Score' && $key !== 'Q2S1Q5Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        
        $scoreQ2S1Q5 = $scoring;    
        Session::put($userID.'.Q2S1Q5Score', $scoreQ2S1Q5);
        // Session::put('idTester',$userID);    

        error_log($scoreQ2S1Q5);
        return Redirect::to(url('/Q2VocabularyQ6'));

    }   
    
    public function saveChoiceRequestPost(Request $request)
    {       
            $currentId = Session::get('idTester');
            $questionNumber = $request->get('name');
            // error_log($questionNumber);

            $answer = $request->get('answer');
            $valueSession = $currentId.".Q2S1Q5_".$questionNumber;
            Session::put($valueSession, $answer);
            return response()->json(['success' => $valueSession]);

    }
    
    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question5Choice = $currentId.".Q2S1Q5_".$questionNumber;

        $sess = Session::get($section1Question5Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }
    
    function showDataBase()
    {
        $vocabulary_class =['010020','030','040','050060','080090'];
        
        //dd($vocabulary_class2);
        $results = Q2Section1Question5::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get();    

        $array = $results->toArray();
        $resultarray = [];
        foreach($array as $val)
        { 
            $value = new Q2S1Q5(
                $val['id'],
                $val['question_id'],
                $val['vocabulary'],
                $val['class_vocabulary'],
                $val['kanji'],               
                $val['correct_answer_rate'],
                $val['past_testee_number'],
                $val['correct_testee_number'],
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
        $i=0;
        while($count==0)
        {
            $questionIdArray = static::getRandomQuestionId($vocabulary_class, $resultarray);
            
            $lengClass = count($vocabulary_class);
            $lengArr = count($questionIdArray);
            
            $kanji = [];
            foreach($questionIdArray as $questionId)
            {
                $idValue = static::searchForId($questionId, $resultarray);
                array_push($kanji, $resultarray[$idValue]->kanji);
            }
            if($lengArr==$lengClass and $this->hasDupe($kanji)==false)
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
            $elements->setQuestionId($key+23);
        } 
        //if($lengthArr>1)dd('database information is not enough for requarement'); 
        return $questionList;    
    }

    function getRandomQuestionId($vocabulary_class, $array){

        $vocabulary_class2 =[];

        //listening classdagi ikkita nuqilay ma'lunotni to'g'irlash
        foreach($vocabulary_class as $class)
        {                
            $len = strlen($class);
            $resultclass=0;
            if($len>3){
                $class1 = substr($class, 0,3);
                $class2 = substr($class, 3, 3);
                $arrclass = [$class1,$class2];
                shuffle($arrclass);
                $resultclass =  $arrclass[0];
            }else  $resultclass = $class;  
            array_push($vocabulary_class2,$resultclass);
        }  
                
        
        shuffle($array);
        $resultarray = [];
        $newQuestion = null;
        $anchor = null;
       
        array_unshift($vocabulary_class2,'0');
        
        foreach($array as $val)
        {      
            $index = array_search($val->classVocabulary, $vocabulary_class2);
            
            if($index!=null)
            { 
                if($val->newQuestion==1 and $newQuestion==null)
                {  
                    $newQuestion = array_search($val->classVocabulary, $vocabulary_class2);                  
                    unset($vocabulary_class2[$index]);  
                    array_push($resultarray, $val->id);

                    if($newQuestion!=null ) break;
                }
            }
        }

        
        foreach($array as $val)
        { 
            $index = array_search($val->classVocabulary, $vocabulary_class2);

            if($index!=null)
            {         
                if($val->classVocabulary==1)
                {
                    continue;
                }                                           
                else
                {
                    array_push($resultarray, $val->id);
                    unset($vocabulary_class2[$index]);
                    $lengthArr = count($vocabulary_class2);
                    if($lengthArr==1){break;}
                }           
                                               
            }
        }

          
        //dd($resultarray,$vocabulary_class2);  
        return $resultarray;      
       
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

    /*
    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question4Id = $currentId.".Q2S1Q4";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question4Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q2\Vocabulary\paginationDataQ2S1Q4', compact('data'))->render(); 
        }
    }

    public function paginate($items, $perPage = 4, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    
    
    public function paginate($items, $perPage = 4, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    
    

    function removeElementFromArray($del_val,$array){
        // dd($del_val,$array);
        if (($key = array_search($del_val, $array)) !== false) {
            unset($array[$key]);
            return $array;
        }
    }
    */
    
}