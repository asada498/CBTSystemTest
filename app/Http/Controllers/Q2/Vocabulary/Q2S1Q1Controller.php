<?php
namespace App\Http\Controllers\Q2\Vocabulary;

use App\Http\Controllers\Controller;
use App\QuestionClass\Q2\Vocabulary\Q2S1Q1;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question1;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Q2S1Q1Controller extends Controller
{
    public function showQuestion(Request $request)
    {
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }   
        $currentId = Session::get('idTester');        
        $section1Question1Id = $currentId.".Q2S1Q1";
        
        //if (!(Session::has($section1Question1Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question1Id, $questionData);
        //}
               
        $questionDataLoad = Session::get($section1Question1Id);
        //$data = $this->paginate($questionDataLoad);
        $data = $questionDataLoad;
        return view('Q2\Vocabulary\paginationQ2S1Q1', compact('data')); //        
        
    }

    
    public function getResultToCalculate(Request $request)
    {

        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorPassFlag = 0;

        if (!(Session::has('idTester'))) {
            $userIDTemp = "123test";
            Session::put('idTester', $userIDTemp);
        }
        $userID = Session::get('idTester');
        $section1Question1Id = $userID.".Q2S1Q1";

        $questionDataLoad = Session::get($section1Question1Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q2S1Q1_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment', '5-1-1')->first()->code;

            $anchorFlag = 0;

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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',1)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',1)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_01',
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
                    [
                        'examinee_number' => substr($userID, 1),
                        'level' => 2,
                        'section' => 1,
                        'question' => 1,
                        'number' => $questionId,
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_21_01',
                        'question_id' => $question->getDatabaseQuestionId(),
                        'anchor' => $anchorFlag,
                        'choice' => $userAnswer,
                        'correct_answer' => $question->getCorrectChoice(),
                        'pass_fail' => $correctFlag,
                    ]
                );
            }
            
        }
        //update record on database
        Q2Section1Question1::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q2Section1Question1::raw("past_testee_number + 1"),
            "correct_testee_number" => Q2Section1Question1::raw("correct_testee_number + 1")
        ]);
        Q2Section1Question1::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q2Section1Question1::raw("past_testee_number + 1")
        ]);

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q2Section1Question1::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q2Section1Question1::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q1Rate = round($scoring * 100 / 5);
        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q1_correct' => $scoring,
            's1_q1_question' => 5,
            's1_q1_perfect_score' => 3.5/45*60,
            's1_q1_anchor_pass' => $anchorPassFlag,
            's1_q1_rate' => $s1Q1Rate]);    
       

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q2S1Q1'))
            {
                if ($key !== 'Q2S1Q1Score' )
                {
                    $afterSubmitSession = $userID.'.'.$key;
                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ2S1Q1 = $scoring;
        Session::put($userID.'.Q2S1Q1Score', $scoreQ2S1Q1);
        
        return Redirect::to(url('/Q2VocabularyQ2'));
    }    

    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        // error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q2S1Q1_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }
    
    public static function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section1Question1Choice = $currentId.".Q2S1Q1_".$questionNumber;

        $sess = Session::get($section1Question1Choice);
        // error_log($sess);
        // error_log($section1Question1Choice);

        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }    
    
    function showDataBase()
    {
        $vocabulary_class =['010','040','050','060','080030'];
        $kanjiReading_class =['101102103','104','301','301','302303'];

        

        $results = Q2Section1Question1::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get();
        
        $array = $results->toArray();
        $resultarray = [];
        foreach($array as $val)
        { 
            $value = new Q2S1Q1(
                $val['id'],
                $val['question_id'],
                $val['vocabulary'],
                $val['class_vocabulary'],
                $val['kanji'],
                $val['class_kanji_reading'],                
                $val['correct_answer_rate'],
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
            $questionIdArray = static::getRandomQuestionId($vocabulary_class, $kanjiReading_class, $resultarray);            
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
        


        foreach ($questionList as $key => $elements) {
            $elements->setQuestionId($key+1);
        }   
        //if($lengthArr>1)dd('database information is not enough for requarement'); 
        return $questionList;        
    }

    function getRandomQuestionId($vocabulary_class, $kanjiReading_class, $array){     
        
        $vocabulary_class2 =[];
        $kanjiReading_class2 =[];

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
        
        foreach($kanjiReading_class as $class)
        {                
            $len = strlen($class);
            $resultclass=0;
            if($len>6){
                $class1 = substr($class, 0,3);
                $class2 = substr($class, 3, 3);
                $class3 = substr($class, 6, 3);
                $arrclass = [$class1,$class2,$class3];
                shuffle($arrclass);
                $resultclass =  $arrclass[0];
            }
            elseif($len>3){
                $class1 = substr($class, 0,3);
                $class2 = substr($class, 3, 3);
                $arrclass = [$class1,$class2];
                shuffle($arrclass);
                $resultclass =  $arrclass[0];
            }
            else  $resultclass = $class;  
            array_push($kanjiReading_class2,$resultclass);
        }  

        
       
        shuffle($array);
        $resultarray = [];
        $newQuestion = null;
       
        array_unshift($vocabulary_class2,'0');
        array_unshift($kanjiReading_class2,'0');
        
        foreach($array as $val)
        { 
            $index = array_search($val->classVocabulary, $vocabulary_class2);
            $index2 = array_search($val->classKanjiReading, $kanjiReading_class2);
            
            if($index!=null and $index2!=null)
            {                
                if($val->newQuestion==1 and $newQuestion==null)
                {  
                    $newQuestion = array_search($val->classVocabulary, $vocabulary_class2);                  
                    unset($vocabulary_class2[$index]);
                    unset($kanjiReading_class2[$index2]);   
                    array_push($resultarray, $val->id);
                    break;
                }                             
            }
        }
        foreach($array as $val)
        { 
            $index = array_search($val->classVocabulary, $vocabulary_class2);
            $index2 = array_search($val->classKanjiReading, $kanjiReading_class2);

            
            if($index!=null and $index2!=null)
            {               
                if($val->newQuestion==1) continue;                              
                else
                {
                    //array_push($resultarray,$val);
                    array_push($resultarray, $val->id);
                    unset($vocabulary_class2[$index]);
                    unset($kanjiReading_class2[$index2]);
                    $lengthArr = count($vocabulary_class2);
                    if($lengthArr==1){break;}
                }           
                                               
            }
        }

        shuffle($resultarray);
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

    function searchForId($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val->getId() === $id) {
                return $key;
            }
        }
        return null;
    }    



}