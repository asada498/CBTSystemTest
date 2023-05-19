<?php

namespace App\Http\Controllers\Q3\Vocabulary;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q3\Vocabulary\Q3Section1Question4;
use App\QuestionClass\Q3\Vocabulary\Q3S1Q4;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class Q3S1Q4Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section1Question4Id = $currentId.".Q3S1Q4";        
        //if (!(Session::has($section1Question4Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question4Id, $questionData);
        //}        
        $questionDataLoad = Session::get($section1Question4Id);
        $data = $this->paginate($questionDataLoad);
        //dd($questionData);

        return view('Q3\Vocabulary\paginationQ3S1Q4', compact('data'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        
        $userID = Session::get('idTester');
        $section1Question4Id = $userID.".Q3S1Q4";
        
        $questionDataLoad = Session::get($section1Question4Id);
        Session::put($userID.".Q3S1Q4Score_anchor", 0);

        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S1Q4_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-3')->first()->code;
                // $correctFlag;
                // $passFail;
                // dd($question->getId());

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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',4)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',4)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_31_04',
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
                     'level' => 3,
                     'section'=> 1,
                     'question' => 4,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_31_04',
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
            
            Q3Section1Question4::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section1Question4::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section1Question4::raw("correct_testee_number + 1")
            ]);
            Q3Section1Question4::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section1Question4::raw("past_testee_number + 1")
            ]);
        }

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q3Section1Question4::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q3Section1Question4::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q3Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q4_correct' => $scoring,
            's1_q4_question' => 5,
            's1_q4_perfect_score' => 7.5 / 55 * 60,
            's1_q4_anchor_pass' => $anchorFlagResult,
            's1_q4_rate' => $s1Q3Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S1Q4'))
                {
                    if ($key !== 'Q3S1Q4Score' && $key !== 'Q3S1Q4Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        
        $scoreQ3S1Q4 = $scoring;
        // Session::put('Q4S1Q1Score',$scoreQ4S1Q1);    
        // Session::put('Q4S1Q2Score',$scoreQ4S1Q2);    
        Session::put($userID.'.Q3S1Q4Score', $scoreQ3S1Q4);
        // Session::put('idTester',$userID);    

        error_log($scoreQ3S1Q4);
        return Redirect::to(url('/Q3VocabularyQ5'));

    }

    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question4Id = $currentId.".Q3S1Q4";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question4Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q3\Vocabulary\paginationDataQ3S1Q4', compact('data'))->render(); //
        }
    }
    public function saveChoiceRequestPost(Request $request)
    {       
            $currentId = Session::get('idTester');
            $questionNumber = $request->get('name');
            error_log($questionNumber);

            $answer = $request->get('answer');
            $valueSession = $currentId.".Q3S1Q4_".$questionNumber;
            Session::put($valueSession, $answer);
            return response()->json(['success' => $valueSession]);

    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question4Choice = $currentId.".Q3S1Q4_".$questionNumber;

        $sess = Session::get($section1Question4Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $vocabulary_class =['010','030','040','050080','060090'];

        $results = Q3Section1Question4::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get();  
        
        $array = $results->toArray();
        $resultarray = [];

        foreach($array as $val)
        { 
            $value = new Q3S1Q4(
                $val['id'],                
                $val['question_id'],
                $val['vocabulary'],
                $val['class_vocabulary'],                
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
            
            if($lengArr==$lengClass)
            $count=1;
        }
        
        $questionList = [];
        foreach($questionIdArray as $questionId)
        {
            $idValue = static::searchForId($questionId, $resultarray);
            array_push($questionList, $resultarray[$idValue]);
        }

        shuffle($questionList);
        foreach ($questionList as $key => $elements) {
            $elements->setQuestionId($key + 26);
        }
               
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
        
        
        $newQuestion = null;
        $resultarray = [];
        
        shuffle($array);
        array_unshift($vocabulary_class2,'0');
        foreach($array as $val)
        { 
            $index = array_search($val->partOfSpeech, $vocabulary_class2);
            
            if($index!=null)
            {                
                if($val->newQuestion==1)
                {
                    $newQuestion = array_search($val->partOfSpeech, $vocabulary_class2);
                    unset($vocabulary_class2[$newQuestion]);   
                    array_push($resultarray, $val->id);
                    break;
                }                             
            }
        }

        foreach($array as $val)
        {
            $index = array_search($val->partOfSpeech, $vocabulary_class2);

            
            if($index!=null )
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

    function hasDupe($array)
    {
        return count($array) !== count(array_unique($array));
    }
    
    public function paginate($items, $perPage = 3, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
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