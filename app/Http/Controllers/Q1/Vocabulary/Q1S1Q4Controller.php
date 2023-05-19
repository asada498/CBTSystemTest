<?php

namespace App\Http\Controllers\Q1\Vocabulary;

use App\Http\Controllers\Controller;
use App\QuestionClass\Q1\Vocabulary\Q1S1Q4;
use App\QuestionDatabase\Q1\Vocabulary\Q1Section1Question4;
use App\AnswerRecord;

use App\QuestionType;
use App\ScoreSummary;
use App\ExamineeLogin;
use App\Grades;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class Q1S1Q4Controller extends Controller
{


    public function showQuestion(Request $request)
    {
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }
        $currentId = Session::get('idTester');
        $section1Question4Id = $currentId.".Q1S1Q4";
        // if (!(Session::has($section1Question5Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question4Id, $questionData);
        // }
        
        $questionDataLoad = Session::get($section1Question4Id);
        $data = $this->paginate($questionDataLoad);
        //dd($data);
        return view('Q1\Vocabulary\paginationQ1S1Q4', compact('data')); //

    }

    public function getResultToCalculate(Request $request)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;

        if (!(Session::has('idTester'))) {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester', $userIDTemp);
        }
        $userID = Session::get('idTester');
        $section1Question4Id = $userID.".Q1S1Q4";

        $questionDataLoad = Session::get($section1Question4Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q1S1Q4_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment', '5-1-4')->first()->code;
            if ($question->getCorrectChoice() == $userAnswer) {
                $correctFlag = 1;
                $scoring++;
                array_push($correctAnswer, $question->getId());
            } else {
                if ($question->getCorrectChoice() == null)
                    $correctFlag = null;
                else {
                    $correctFlag = 0;
                    array_push($incorrectAnswer, $question->getId());
                }
            }
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',4)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',4)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_11_04',
                        'question_id'=>$question->getDatabaseQuestionId(),
                        'anchor'=> 0,
                        'choice'=>$userAnswer,
                        'correct_answer'=>$question->getCorrectChoice(),
                        'pass_fail'=>$correctFlag,
                        ]
                    );
                }
            else
            {
                AnswerRecord::insert(
                    [
                        'examinee_number' => substr($userID, 1),
                        'level' => 1,
                        'section' => 1,
                        'question' => 4,
                        'number' => $questionId,
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_11_04',
                        'question_id' => $question->getDatabaseQuestionId(),
                        'anchor' => 0,
                        'choice' => $userAnswer,
                        'correct_answer' => $question->getCorrectChoice(),
                        'pass_fail' => $correctFlag,
                    ]
                );
            }    
            
        }
        //update record on database
        Q1Section1Question4::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q1Section1Question4::raw("past_testee_number + 1"),
            "correct_testee_number" => Q1Section1Question4::raw("correct_testee_number + 1")
        ]);
        Q1Section1Question4::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q1Section1Question4::raw("past_testee_number + 1")
        ]);

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q1Section1Question4::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q1Section1Question4::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q4Rate = round($scoring * 100 / 6);

        //dd($s1Q1Correct,$s1Q2Correct,$s1Q3Correct,$s1Q4Correct,$s1Q5Correct,$section1Total);
        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q4_correct' => $scoring,
            's1_q4_question' => 6,
            's1_q4_perfect_score' => 6.5/40*60,
            's1_q4_anchor_pass' => 0,
            's1_q4_rate' => $s1Q4Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q1S1Q4'))
            {
                if ($key !== 'Q1S1Q4Score' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
 
        $scoreQ1S1Q4 = $scoring;
        Session::put($userID.'.Q1S1Q4Score', $scoreQ1S1Q4);
        error_log($scoreQ1S1Q4);
        return Redirect::to(url('/Q1VocabularyQ5'));

    }

    function showDataBase()
    {
        $vocabulary_class =['010020','030','040','050','060','080090'];

        $results = Q1Section1Question4::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get();  
        
        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];
        foreach($array as $val)
        { 
            $value = new Q1S1Q4(
                $val['id'],
                $val['question_id'],
                $val['vocabulary'],
                $val['class_vocabulary'],
                $val['kanji'],
                $val['past_testee_number'],
                $val['correct_testee_number'], 
                // $user->dupe,
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
            $questionIdArray = static::getRandomQuestionId($vocabulary_class, $resultarray);
            
            $lengClass = count($vocabulary_class);
            $lengArr = count($questionIdArray);
            
            $kanji = [];
            foreach($questionIdArray as $questionId)
            {
                $idValue = static::searchForId($questionId, $resultarray);
                array_push($kanji, $resultarray[$idValue]->kanji);
            }
            if($lengArr==$lengClass and  $this->hasDupe($kanji)==false)
            //if($lengArr==$lengClass)
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
            $elements->setQuestionId($key+20);
        }
        
        return $questionList;
    }

    function  getRandomQuestionId($vocabulary_class, $array)
    {

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
            }            
            else  $resultclass = $class;  
            array_push($vocabulary_class2,$resultclass);
        }

        
        $resultarray = [];
        $newQuestion = null;
        array_unshift($vocabulary_class2,'0');
        
        shuffle($array);
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

                    if($newQuestion!=null) break;
                    else continue;
                }                             
            }
        }
        
        foreach($array as $val)
        { 
            $index = array_search($val->classVocabulary, $vocabulary_class2);
            if($index!=null)
            {               
                if($val->newQuestion==1) continue;                              
                else
                {
                    //array_push($resultarray,$val);
                    array_push($resultarray, $val->id);
                    unset($vocabulary_class2[$index]);
                    $lengthArr = count($vocabulary_class2);
                    if($lengthArr==1){break;}
                }           
                                               
            }
        }

        
        return $resultarray; 
    }

    function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section1Question4Choice = $currentId.".Q1S1Q4_".$questionNumber;

        $sess = Session::get($section1Question4Choice);

        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        // error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q1S1Q4_".$questionNumber;

        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
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
        $section1Question4Id = $currentId.".Q1S1Q4";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question4Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q1\Vocabulary\paginationDataQ1S1Q4', compact('data'))->render(); //
        }
    }

    function searchForId($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val->getId() === $id) {
                return $key;
            }
        }
        return null;
    }

    function hasDupe($kanjiArray)
    {
        // if (count($array) !== count(array_unique($array))) {
        //     return true;
        // }
        $explodeArray = [];
        foreach ($kanjiArray as $results) {
            if($results == null)
                continue;
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

    /*  
    public function endVocabularyQ4 (){

        return view('endVocabularyQ3');
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
