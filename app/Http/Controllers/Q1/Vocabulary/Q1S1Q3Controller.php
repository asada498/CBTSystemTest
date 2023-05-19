<?php
namespace App\Http\Controllers\Q1\Vocabulary;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q1\Vocabulary\Q1Section1Question3;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use App\QuestionClass\Q1\Vocabulary\Q1S1Q3;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class Q1S1Q3Controller extends Controller
{
    public function showQuestion (){
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }        
        $currentId = Session::get('idTester');
        
        $section1Question3Id = $currentId.".Q1S1Q3";
        // if (!(Session::has( $section1Question2Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put( $section1Question3Id,$questionData);        
        // }
        $questionDataLoad = Session::get( $section1Question3Id);
        $data = $this->paginate($questionDataLoad);
        //dd($data);
        return view('Q1\Vocabulary\paginationQ1S1Q3', compact('data'));
    }

    
    public function getResultToCalculate (Request $request){

        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        
        if (!(Session::has('idTester'))) {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp); 
        }

        $userID = Session::get('idTester');
        $section1Question3Id = $userID.".Q1S1Q3";
        $questionDataLoad = Session::get($section1Question3Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q1S1Q3_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-1-2')->first()->code;
            $correctFlag = null;
            $passFail = null;

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
            
            //TESTING CODE ONLY. DELETE LATER.
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',3)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',3)->where('number',$questionId)->update(
                    [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_11_03',
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
                        'question' => 3,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_11_03',
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
        
        Q1Section1Question3::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q1Section1Question3::raw("past_testee_number + 1"),
            "correct_testee_number" => Q1Section1Question3::raw("correct_testee_number + 1")
        ]);
        Q1Section1Question3::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q1Section1Question3::raw("past_testee_number + 1")
        ]);

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q1Section1Question3::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q1Section1Question3::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q3Rate = round($scoring * 100 / 6);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q3_correct' => $scoring,
            's1_q3_question' => 6,
            's1_q3_perfect_score' => 6/40*60,
            's1_q3_anchor_pass' => $anchorFlag,
            's1_q3_rate' => $s1Q3Rate]);
            
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q1S1Q3'))
                {
                    if ($key !== 'Q1S1Q3Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
                        Session::forget($afterSubmitSession);
                    }
                }
            }
            
        $scoreQ1S1Q3 = $scoring;
        Session::put($userID.'.Q1S1Q3Score', $scoreQ1S1Q3);
        return Redirect::to(url('/Q1VocabularyQ4'));
        
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        // error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q1S1Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question3Choice = $currentId.".Q1S1Q3_".$questionNumber;

        $sess = Session::get($section1Question3Choice);
        if ($sess == $questionChoice)
            return "checked ";
        else return "";
    }

    function showDataBase()
    {
        $vocabulary_class =['010','020','030','040','050060','070080090100'];
               
        //dd($vocabulary_class2);
        $results = Q1Section1Question3::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get();

        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];  
        
        foreach($array as $val)
        { 
            $value = new Q1S1Q3(
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
            $elements->setQuestionId($key+14);
        }

        return $questionList;    
    }

    function getRandomQuestionId($vocabulary_class, $array){

        $vocabulary_class2 = [];

        //listening classdagi ikkita nuqilay ma'lunotni to'g'irlash
        foreach($vocabulary_class as $class)
        {                
            $len = strlen($class);
            $resultclass=0;
            if($len>9){
                $class1 = substr($class, 0,3);
                $class2 = substr($class, 3, 3);
                $class3 = substr($class, 6, 3);
                $class4 = substr($class, 9, 3);
                $arrclass = [$class1,$class2,$class3,$class4];
                shuffle($arrclass);
                $resultclass =  $arrclass[0];
            }
            elseif($len>3)
            {
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

                    if($newQuestion!=null) break;
                }
            }
        }
        shuffle($array);
        foreach($array as $val)
        { 
            $index = array_search($val->classVocabulary, $vocabulary_class2);
            
            if($index!=null)
            {            
                if($val->newQuestion==1) continue;                           
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

    public function paginate($items, $perPage = 3, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question3Id = $currentId.".Q1S1Q3";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question3Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q1\Vocabulary\paginationDataQ1S1Q3', compact('data'))->render(); 
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
}