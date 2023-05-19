<?php

namespace App\Http\Controllers\Q1\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q1\Vocabulary\Q1Section1Question6;
use App\QuestionClass\Q1\Vocabulary\Q1S1Q6;
use Illuminate\Support\Facades\Session;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;

class Q1S1Q6Controller extends Controller
{
    public function showQuestion (){
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }
        $currentId = Session::get('idTester');
        $section1Question6Id = $currentId.".Q1S1Q6";
        // if (!(Session::has($section2Question1Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question6Id, $questionData);
        // }
        $data = Session::get($section1Question6Id);
        //$data = $this->paginate($questionDataLoad);
        //dd($data);
        return view('Q1\Vocabulary\paginationQ1S1Q6', compact('data'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        
        $userID = Session::get('idTester');
        $section1Question6Id = $userID.".Q1S1Q6";

        $questionDataLoad = Session::get($section1Question6Id);
        //Session::put($userID.".Q1S1Q6Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q1S1Q6_' . $questionId;
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
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',6)->where('number',$questionId)->exists())
                {
                    //dd("ha ifga kirdi");
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',1)->where('section',1)->where('question',6)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_11_06',
                        'question_id'=>$question->getDatabaseQuestionId(),
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
                            'question' => 6,
                            'number' => $questionId,
                            'question_type' => $codeQuestion,
                            'question_table_name' => 'q_11_06',
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
        Q1Section1Question6::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q1Section1Question6::raw("past_testee_number + 1"),
            "correct_testee_number" => Q1Section1Question6::raw("correct_testee_number + 1")
        ]);
        Q1Section1Question6::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q1Section1Question6::raw("past_testee_number + 1")
        ]);

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q1Section1Question6::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q1Section1Question6::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q6Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 1)->update([
            's1_q6_correct' => $scoring,
            's1_q6_question' => 5,
            's1_q6_perfect_score' => 6/40*60,
            's1_q6_rate' => $s1Q6Rate
            ]);
        

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q1S1Q6'))
            {
                if ($key !== 'Q1S1Q6Score' && $key !== 'Q1S1Q6Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ1S1Q6 = $scoring;
        Session::put($userID.'.Q1S1Q6Score', $scoreQ1S1Q6);

        return Redirect::to(url('/Q1VocabularyQ7'));
    }

    function showDataBase()
    {
        $classGrammar =['030','030','030','030','060'];
        $classGrammar2 = $classGrammar;

       

        $results = Q1Section1Question6::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->whereIn('class_grammar', $classGrammar2)
        ->get();  
        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];

        foreach($array as $val)
        { 
            $value = new Q1S1Q6(
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
            array_push($resultarray,$value);
        }    
        
        

        $count = 0;
        while($count==0)
        {
            $questionIdArray = static::getRandomQuestionId($resultarray, $classGrammar2);
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
            $elements->setQuestionId($key + 36);
        } 
        
        //dd($questionIdArray);
        return $questionList;
              
       
    }

    function getRandomQuestionId($array, $classGrammar2)
    {
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
        
        //dd($resultarray);
        return $resultarray;
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q1S1Q6_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);

    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question6Choice = $currentId.".Q1S1Q6_".$questionNumber;

        $sess = Session::get($section1Question6Choice);
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