<?php

namespace App\Http\Controllers\Q2\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question8;
use App\QuestionClass\Q2\Vocabulary\Q2S1Q8;
use Illuminate\Support\Facades\Session;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;

class Q2S1Q8Controller extends Controller
{
    public function showQuestion (){
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }
        $currentId = Session::get('idTester');
        $section1Question8Id = $currentId.".Q2S1Q8";
        // if (!(Session::has($section2Question1Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question8Id, $questionData);
        // }
        $data = Session::get($section1Question8Id);
        //$data = $this->paginate($questionDataLoad);
        //dd($data);
        return view('Q2\Vocabulary\paginationQ2S1Q8', compact('data'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorPassFlag = 0;
        
        $userID = Session::get('idTester');
        $section1Question8Id = $userID.".Q2S1Q8";

        $questionDataLoad = Session::get($section1Question8Id);
        // Session::put($userID.".Q2S1Q8Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q2S1Q8_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment', '5-2-1')->first()->code;
            //    $correctFlag;
            //    $passFail;

            
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
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',8)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',8)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                    'question_table_name'=>'q_21_08',
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
                        'question' => 8,
                        'number' => $questionId,
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_21_08',
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
        Q2Section1Question8::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q2Section1Question8::raw("past_testee_number + 1"),
            "correct_testee_number" => Q2Section1Question8::raw("correct_testee_number + 1")
        ]);
        Q2Section1Question8::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q2Section1Question8::raw("past_testee_number + 1")
        ]);

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q2Section1Question8::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q2Section1Question8::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q8Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 2)->update([
            's1_q8_correct' => $scoring,
            's1_q8_question' => 5,
            's1_q8_perfect_score' => 5/45*60,
            's1_q8_rate' => $s1Q8Rate
            ]);
        

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q2S1Q8'))
            {
                if ($key !== 'Q2S1Q8Score' && $key !== 'Q2S1Q8Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ2S1Q8 = $scoring;
        Session::put($userID.'.Q2S1Q8Score', $scoreQ2S1Q8);

        return Redirect::to(url('/Q2VocabularyQ9'));
    }

    function showDataBase()
    {
        $classGrammar =['030','030','030','030','010020'];
        
        $results = Q2Section1Question8::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get();   
        
        $array = $results->toArray();
        $resultarray = [];

        foreach($array as $val)
        { 
            $value = new Q2S1Q8(
                $val['id'],
                $val['question_id'],
                $val['grammar'],
                $val['class_grammar'],
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
        $count = 0;
        while($count==0)
        {
            $questionIdArray = static::getRandomQuestionId($resultarray, $classGrammar);
            
            $lengClass = count($classGrammar);
            $lengArr = count($questionIdArray);
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
            $elements->setQuestionId($key + 45);
        } 
        //dd($questionIdArray);
        return $questionList;
              
       
    }

    function getRandomQuestionId($array, $classGrammar)
    {

        $classGrammar2 =[];

        //listening classdagi ikkita nuqilay ma'lunotni to'g'irlash
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

        shuffle($array);
        $resultarray = [];
        $newQuestion = null;
        
        array_unshift($classGrammar2,'0');

        foreach($array as $val)
        { 
            $index = array_search($val->classGrammar, $classGrammar2);

            if($index!=null)
            {
                if($val->newQuestion==1 and $newQuestion==null)
                {  
                    $newQuestion = array_search($val->classGrammar, $classGrammar2);                  
                    unset($classGrammar2[$index]);  
                    array_push($resultarray, $val->id);
                    break;
                }                            
            }
        }
        
        foreach($array as $val)
        { 
            $index = array_search($val->classGrammar, $classGrammar2);

            if($index!=null)
            {        
                if($val->newQuestion==1) continue;
                else
                {
                    array_push($resultarray, $val->id);
                    unset($classGrammar2[$index]);
                    $lengthArr = count($classGrammar2);
                    if($lengthArr==1){break;}
                }                         
            }
        }
        
        return $resultarray;
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        // error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q2S1Q8_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);

    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question8Choice = $currentId.".Q2S1Q8_".$questionNumber;

        $sess = Session::get($section1Question8Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
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

    /*
    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section2Question2Id = $currentId.".Q3S2Q2";
        if($request->ajax())
        {
            $questionDataLoad = Session::get($section2Question2Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q3\Reading\paginationDataQ3S2Q2', compact('data'))->render();
        }
    }   

    function hasDupe($array, $kanjiArray)
    {
        if (count($array) !== count(array_unique($array))) {
            return true;
        }
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

    public function paginate($items, $perPage = 3, $page = null, $options = [])
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