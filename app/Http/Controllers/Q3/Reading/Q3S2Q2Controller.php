<?php

namespace App\Http\Controllers\Q3\Reading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question2;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q3\Reading\Q3S2Q2;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;

class Q3S2Q2Controller extends Controller
{
    public function showQuestion (){
        
        $currentId = Session::get('idTester');
        $section2Question2Id = $currentId.".Q3S2Q2";
        // if (!(Session::has($section2Question1Id))) {
            $questionData = $this->showDataBase();
            Session::put($section2Question2Id, $questionData);
        // }
        $questionDataLoad = Session::get($section2Question2Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q3\Reading\pageQ3S2Q2', compact('data'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorPassFlag = 0;

        $userID = Session::get('idTester');
        $section2Question2Id = $userID.".Q3S2Q2";

        $questionDataLoad = Session::get($section2Question2Id);

        if($questionDataLoad != null){
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S2Q2_' . $questionId;
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',2)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',2)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_32_02',
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
                            'question' => 2,
                            'number' => $questionId,
                            'question_type' => $codeQuestion,
                            'question_table_name' => 'q_32_02',
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
            Q3Section2Question2::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section2Question2::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section2Question2::raw("correct_testee_number + 1")
            ]);
            Q3Section2Question2::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section2Question2::raw("past_testee_number + 1")
            ]);
    
            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q3Section2Question2::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
    
            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q3Section2Question2::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }

        $s2Q1Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 3)->update([
            's2_q2_correct' => $scoring,
            's2_q2_question' => 5,
            's2_q2_perfect_score' => 6/55*60,
            's2_q2_rate' => $s2Q1Rate
            ]);
        

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q3S2Q2'))
            {
                if ($key !== 'Q3S2Q2Score' && $key !== 'Q3S2Q2Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ3S2Q2 = $scoring;
        Session::put($userID.'.Q3S2Q2Score', $scoreQ3S2Q2);

        return Redirect::to(url('/Q3ReadingQ3'));
    }
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
    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q3S2Q2_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);

    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question2Choice = $currentId.".Q3S2Q2_".$questionNumber;

        $sess = Session::get($section2Question2Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $classGrammar =['030','030','030','030','030'];
        
        $classGrammar2 = $classGrammar;

       

        $results = Q3Section2Question2::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->whereIn('class_grammar', $classGrammar2)
        ->get();      

        $count = 0;
        while($count==0)
        {
            $questionIdArray = static::getRandomQuestionId($results, $classGrammar2);
            
            $lengClass = count($classGrammar);
            $lengArr = count($questionIdArray);

            $kanji = [];
            foreach($questionIdArray as $question)
            {
                array_push($kanji, $question->kanji);
            }
            
            if($lengArr==$lengClass and $this->hasDupe($kanji)==false)
            $count=1;
            
        }

        
        shuffle($questionIdArray);
        foreach ($questionIdArray as $key => $elements) {
            $elements->setQuestionId($key + 14);
        }   
        return $questionIdArray;
              
       
    }

    function hasDupe($kanjiArray)  // hasDupe($array, $kanjiArray)
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

    public function paginate($items, $perPage = 3, $page = null, $options = [])
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
    
    function getRandomQuestionId($results, $classGrammar2)
    {
        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];
        $newQuestion = null;
        
        array_unshift($classGrammar2,'0');
        
        foreach($array as $val)
        { 
            $value = new Q3S2Q2(
                $val['id'],
                $val['question_id'],
                $val['grammar'],
                $val['class_grammar'],
                $val['kanji'],
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
            
            $index = array_search($val['class_grammar'], $classGrammar2);

            if($index!=null)
            {
                if($val['new_question']==1 and $newQuestion==null)
                {  
                    $newQuestion = array_search($val['class_grammar'], $classGrammar2);                  
                    unset($classGrammar2[$index]);  
                    array_push($resultarray, $value);
                    break;
                }                            
            }
        }
               
        foreach($array as $val)
        { 
            $value = new Q3S2Q2(
                $val['id'],
                $val['question_id'],
                $val['grammar'],
                $val['class_grammar'],
                $val['kanji'],
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
            
            $index = array_search($val['class_grammar'], $classGrammar2);

            if($index!=null)
            { 
                $i=0;       
                if($val['new_question']==1) continue;
                else
                {
                    array_push($resultarray, $value);
                    unset($classGrammar2[$index]);
                    $lengthArr = count($classGrammar2);
                    if($lengthArr==1){break;}
                }                         
            }
        }
        return $resultarray;
    }
}