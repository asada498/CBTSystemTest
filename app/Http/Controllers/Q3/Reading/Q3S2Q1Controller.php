<?php

namespace App\Http\Controllers\Q3\Reading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question1;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q3\Reading\Q3S2Q1;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;

class Q3S2Q1Controller extends Controller
{
    public function showQuestion (){
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }   
        $currentId = Session::get('idTester');
        $section2Question1Id = $currentId.".Q3S2Q1";
        // if (!(Session::has($section2Question1Id))) {
            $questionData = $this->showDataBase();
            Session::put($section2Question1Id, $questionData);
        // }
        $questionDataLoad = Session::get($section2Question1Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q3\Reading\pageQ3S2Q1', compact('data'));

    }

    public function getResultToCalculate (Request $request){
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
        $section2Question1Id = $userID.".Q3S2Q1";

        $questionDataLoad = Session::get($section2Question1Id);
        Session::put($userID.".Q3S2Q1Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q3S2Q1_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment', '5-2-1')->first()->code;
            //    $correctFlag;
            //    $passFail;

            if ($question->getAnchor() == '1') {
                $anchorFlag = 1;
            } else {
                $anchorFlag = 0;
            }

            if ($question->getCorrectChoice() == $userAnswer) {
                $correctFlag = 1;
                $scoring++;
                array_push($correctAnswer, $question->getId());

                if ($question->getAnchor() == '1') {
                    $anchorPassFlag += 1;
                }
            } else {
                if ($question->getCorrectChoice() == null)
                    $correctFlag = null;
                else {
                    $correctFlag = 0;
                    array_push($incorrectAnswer, $question->getId());
                }
            }
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',1)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',1)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_32_01',
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
                        'level' => 3,
                        'section' => 2,
                        'question' => 1,
                        'number' => $questionId,
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_32_01',
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
        Q3Section2Question1::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q3Section2Question1::raw("past_testee_number + 1"),
            "correct_testee_number" => Q3Section2Question1::raw("correct_testee_number + 1")
        ]);
        Q3Section2Question1::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q3Section2Question1::raw("past_testee_number + 1")
        ]);

        $s2Q1Rate = round($scoring * 100 / 13);
        Session::put($userID.".Q3S2Q1Score_anchor", 8.5 / 55 * 60 / 13 * $anchorPassFlag);

        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 3)->update([
                's2_q1_correct' => $scoring,
                's2_q1_question' => 13,
                's2_q1_perfect_score' => 8.5/55*60,
                's2_q1_anchor_pass' => $anchorPassFlag,
                's2_q1_rate' => $s2Q1Rate
            ]);

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q3S2Q1'))
            {
                if ($key !== 'Q3S2Q1Score' && $key !== 'Q3S2Q1Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ3S2Q1 = $scoring;
        Session::put($userID.'.Q3S2Q1Score', $scoreQ3S2Q1);

        return Redirect::to(url('/Q3ReadingQ2'));
    }
    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section2Question1Id = $currentId.".Q3S2Q1";
        if($request->ajax())
        {
            $questionDataLoad = Session::get($section2Question1Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q3\Reading\paginationDataQ3S2Q1', compact('data'))->render();
        }
    }
    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q3S2Q1_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);

    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question1Choice = $currentId.".Q3S2Q1_".$questionNumber;

        $sess = Session::get($section2Question1Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $classGrammar =['020','020','020','030','030','030','030','030','030','030',
        '030','040','050051060'];

        $results = Q3Section2Question1::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get(); 

        $array = $results->toArray();
        //shuffle($array);
        $resultarray = [];

        foreach($array as $val)
        { 
            $value = new Q3S2Q1(
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

            array_push($resultarray,  $value);
        
        } 
              
        
        $count = 0;
        while($count==0)
        {
            $questionIdArray = static::getRandomQuestionId($classGrammar, $resultarray);
           

            $lengClass = count($classGrammar);
            $lengArr = count($questionIdArray);

            
            $kanji = [];
            foreach($questionIdArray as $questionId)
            {
                $idValue = static::searchForId($questionId, $resultarray);
                array_push($kanji, $resultarray[$idValue]->kanji);
            }
            if($lengArr==$lengClass and $this->hasDupe($kanji)==false) //if($lengArr==$lengClass)
            $count=1;            
        }

        $questionList = [];
        foreach($questionIdArray as $id)
        {
            $idValue = static::searchForId($id, $resultarray);
            array_push($questionList,$resultarray[$idValue]);
        }


        shuffle($questionList);
        foreach ($questionList as $key => $elements) {
            $elements->setQuestionId($key + 1);
        }  
        
        //dd($questionList);
        return $questionList;
              
       
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

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
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

    function removeElementFromArray($del_val,$array){
        // dd($del_val,$array);
        if (($key = array_search($del_val, $array)) !== false) {
            unset($array[$key]);
            return $array;
        }
    }
    
    function getRandomQuestionId($classGrammar, $resultarray)
    {
        shuffle($resultarray);
        
        $classGrammar2 =[];

        //listening classdagi ikkita nuqilay ma'lunotni to'g'irlash
        foreach($classGrammar as $class)
        {                
            $len = strlen($class);
            $resultclass=0;
            if($len>6)
            {
                $class1 = substr($class, 0,3);
                $class2 = substr($class, 3, 3);
                $class3 = substr($class, 6, 3);
                $arrclass = [$class1,$class2,$class3];
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
            array_push($classGrammar2,$resultclass);
        }
        
        array_unshift($classGrammar2,'0');
        $questionIdArray = [];
        $newQuestion = null;
        $anchor = null;
        foreach($resultarray as $result)
        {
            if(array_search($result->classGrammar, $classGrammar2) != null){
                if($result->anchor==1 and $anchor==null)
                {
                    $anchor = array_search($result->classGrammar, $classGrammar2);
                    unset($classGrammar2[$anchor]);
                    array_push($questionIdArray, $result->id);

                    if($anchor!=null and $newQuestion!=null) break;
                    else continue;
                }

                if($result->newQuestion==1 and $newQuestion==null)
                {
                    $newQuestion = array_search($result->classGrammar, $classGrammar2);
                    unset($classGrammar2[$newQuestion]);
                    array_push($questionIdArray, $result->id);
                }
            }
        }

        foreach($resultarray as $result)
        {
            $index = array_search($result->classGrammar, $classGrammar2);
            if($index!=null)
            {
                if($result->anchor==1) continue;
                elseif($result->newQuestion) continue;
                else
                {
                    array_push($questionIdArray, $result->id);
                    unset($classGrammar2[$index]);
                    $lengthArr = count($classGrammar2);
                    if($lengthArr==1){break;}
                }
            }
        }
        return $questionIdArray;
    }
}