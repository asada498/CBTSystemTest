<?php
namespace App\Http\Controllers\Q1\Vocabulary;

use App\Http\Controllers\Controller;
use App\QuestionClass\Q1\Vocabulary\Q1S1Q1;
use App\QuestionDatabase\Q1\Vocabulary\Q1Section1Question1;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Q1S1Q1Controller extends Controller
{
    public function showQuestion(Request $request)
    {
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }   
        $currentId = Session::get('idTester');        
        $section1Question1Id = $currentId.".Q1S1Q1";
        
        //if (!(Session::has($section1Question1Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question1Id, $questionData);
        //}
               
        $questionDataLoad = Session::get($section1Question1Id);
        $data = $this->paginate($questionDataLoad);
        //dd($data);
        return view('Q1\Vocabulary\paginationQ1S1Q1', compact('data')); //        
        
    }

    
    public function getResultToCalculate(Request $request)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;

        if (!(Session::has('idTester'))) {
            $userIDTemp = "123test";
            Session::put('idTester', $userIDTemp);
        }
        $userID = Session::get('idTester');
        $section1Question1Id = $userID.".Q1S1Q1";

        $questionDataLoad = Session::get($section1Question1Id);
        Session::put($userID.".Q1S1Q1Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q1S1Q1_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment', '5-1-1')->first()->code;

            if ($question->getAnchor() == '1') {
                $anchorFlag = 1;
            } else {
                $anchorFlag = 0;
            }

            if ($question->getCorrectChoice() == $userAnswer) {
                $correctFlag = 1;
                if ($anchorFlag == 1)
                    {
                        $anchorFlagResult = 1;
                        Session::put($userID.".Q1S1Q1Score_anchor", 4/40*60/6);
                    }
                $scoring++;
                array_push($correctAnswer, $question->getId());                
            } 
            else 
            {
                if ($question->getCorrectChoice() == null)
                    $correctFlag = null;
                else {
                    $correctFlag = 0;
                    array_push($incorrectAnswer, $question->getId());
                }
            }
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',1)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',1)->where('number',$questionId)->update(
                    [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_11_01',
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
                        'level' => 1,
                        'section' => 1,
                        'question' => 1,
                        'number' => $questionId,
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_11_01',
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
        Q1Section1Question1::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q1Section1Question1::raw("past_testee_number + 1"),
            "correct_testee_number" => Q1Section1Question1::raw("correct_testee_number + 1")
        ]);
        Q1Section1Question1::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q1Section1Question1::raw("past_testee_number + 1")
        ]);

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q1Section1Question1::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q1Section1Question1::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q1Rate = round($scoring * 100 / 6);
        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q1_correct' => $scoring,
            's1_q1_question' => 6,
            's1_q1_perfect_score' => 4/40*60,
            's1_q1_anchor_pass' => $anchorFlagResult,
            's1_q1_rate' => $s1Q1Rate]);    
       

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q1S1Q1'))
            {
                if ($key !== 'Q1S1Q1Score' && $key !== 'Q1S1Q1Score_anchor')
                {
                    $afterSubmitSession = $userID.'.'.$key;
                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ1S1Q1 = $scoring;
        Session::put($userID.'.Q1S1Q1Score', $scoreQ1S1Q1);
        
        return Redirect::to(url('/Q1VocabularyQ2'));
    }    

    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        // error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q1S1Q1_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }
    
    public static function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section1Question1Choice = $currentId.".Q1S1Q1_".$questionNumber;

        $sess = Session::get($section1Question1Choice);
        // error_log($sess);
        // error_log($section1Question1Choice);

        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }    
    
    function showDataBase()
    {
        $vocabulary_class =['010','010','030','040','050','060080'];
        $kanjiReading_class =['101102','103','104','301302','301302','303'];

        $results = Q1Section1Question1::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get();

        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];

        foreach($array as $val)
        { 
            $value = new Q1S1Q1(
                $val['id'],
                $val['question_id'],
                $val['vocabulary'],
                $val['class_vocabulary'],
                $val['kanji'],
                $val['class_kanji_reading'],                
                $val['correct_answer_rate'],
                $val['past_testee_number'],
                $val['correct_testee_number'],  
                $val['anchor'],
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
            
            $question = [];
            foreach($questionIdArray as $questionId)
            {
                $idValue = static::searchForId($questionId, $resultarray);
                array_push($question, $resultarray[$idValue]->question);
            }
            if($lengArr==$lengClass and  $this->hasDupe($question)==false)
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
            if($len>3){
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
        $anchor = null;
       
        array_unshift($vocabulary_class2,'0');
        array_unshift($kanjiReading_class2,'0');
        //get anchor question
        foreach($array as $val)
        {             
            $index = array_search($val->classVocabulary, $vocabulary_class2);
            $index2 = array_search($val->classKanjiReading, $kanjiReading_class2);
            
            if($index!=null and $index2!=null)
            {                
                if($val->anchor==1 and $anchor==null)
                {  
                    $anchor = array_search($val->classVocabulary, $vocabulary_class2);                  
                    unset($vocabulary_class2[$index]);
                    unset($kanjiReading_class2[$index2]);   
                    array_push($resultarray, $val->id);

                    if($anchor!=null) break;
                    else continue;
                }
            }
        }
        // anchor majburiy bo'lishi uchun anchor topolmagan holda class dan bitta element o'chdi
        // endi anchor yo'q holda massiv bittaga to'lmay qoladi va qaytib keladi
        if(empty($resultarray)){
            unset($vocabulary_class2[1]);
            unset($kanjiReading_class2[1]);  
        }
        //get new question
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

                    if($newQuestion!=null) break;
                    else continue;
                }
            }
        }       
        
        foreach($array as $val)
        { 
            $index = array_search($val->classVocabulary, $vocabulary_class2);
            $index2 = array_search($val->classKanjiReading, $kanjiReading_class2);
            
            if($index!=null and $index2!=null)
            {
                if($val->anchor!=0 ) continue;                
                elseif($val->newQuestion==1) continue;                              
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
        //dd($resultarray);
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
        $section1Question1Id = $currentId.".Q1S1Q1";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question1Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q1\Vocabulary\paginationDataQ1S1Q1', compact('data'))->render(); 
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

    function hasDupe($questionArray)
    {
        if (count($questionArray) !== count(array_unique($questionArray))) {
            return true;
        }
        // $explodeArray = [];
        // foreach ($kanjiArray as $results) {

        //     foreach (explode(",", $results) as $d) {
        //         array_push($explodeArray, $d);
        //     }
        // }
        // if (count($explodeArray) !== count(array_unique($explodeArray))) {
        //     return true;
        // } else {
        //     return false;
        // }
    }

    function hasAnchor($questionArray)
    {
        
    }

}