<?php

namespace App\Http\Controllers\Q3\Vocabulary;

use App\Http\Controllers\Controller;
use App\QuestionClass\Q3\Vocabulary\Q3S1Q5;
use App\QuestionDatabase\Q3\Vocabulary\Q3Section1Question5;
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

class Q3S1Q5Controller extends Controller
{


    public function showQuestion(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question5Id = $currentId.".Q3S1Q5";
        // if (!(Session::has($section1Question5Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question5Id, $questionData);
        // }
        $questionDataLoad = Session::get($section1Question5Id);
        $data = $this->paginate($questionDataLoad);
        //dd($questionDataLoad);
        return view('Q3\Vocabulary\paginationQ3S1Q5', compact('data')); //

    }

    public function getResultToCalculate(Request $request)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;

        $userID = Session::get('idTester');
        $section1Question5Id = $userID.".Q3S1Q5";

        $questionDataLoad = Session::get($section1Question5Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q3S1Q5_' . $questionId;
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
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',5)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',5)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_31_05',
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
                        'level' => 3,
                        'section' => 1,
                        'question' => 5,
                        'number' => $questionId,
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_31_05',
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
        Q3Section1Question5::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q3Section1Question5::raw("past_testee_number + 1"),
            "correct_testee_number" => Q3Section1Question5::raw("correct_testee_number + 1")
        ]);
        Q3Section1Question5::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q3Section1Question5::raw("past_testee_number + 1")
        ]);

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q3Section1Question5::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q3Section1Question5::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q5Rate = round($scoring * 100 / 5);

        $s1Q1Correct = Session::get($userID.".Q3S1Q1Score");
        $s1Q2Correct = Session::get($userID.".Q3S1Q2Score");
        $s1Q3Correct = Session::get($userID.".Q3S1Q3Score");
        $s1Q4Correct = Session::get($userID.".Q3S1Q4Score");
        $s1Q5Correct = $scoring;
        $section1Total = $s1Q1Correct /8*5.5/55*60 + $s1Q2Correct /6*4/55*60 + $s1Q3Correct /11*7.5/55*60 + $s1Q4Correct /5*7.5/55*60 + $s1Q5Correct /5*7.5/55*60;
        $s1Rate = ($s1Q1Correct+$s1Q2Correct+$s1Q3Correct+$s1Q4Correct+$s1Q5Correct)/(8+6+11+5+5);
        //dd($s1Q1Correct,$s1Q2Correct,$s1Q3Correct,$s1Q4Correct,$s1Q5Correct,$section1Total);
        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 3)->update([
            's1_q5_correct' => $scoring,
            's1_q5_question' => 5,
            's1_q5_perfect_score' => 7.5/55*60,
            's1_end_flag' => 1,
            's1_q5_rate' => $s1Q5Rate,
            's1_rate'=>$s1Rate,
            's1_score' => $section1Total
        ]);

        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q3S1Q5'))
            {
                if ($key !== 'Q3S1Q5Score' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }

        $anchorScoreQ3S1Q1 =  Session::get( $userID.'.Q3S1Q1Score_anchor');
        $anchorScoreQ3S1Q3 =  Session::get( $userID.'.Q3S1Q3Score_anchor');
        $currentAnchorScore = $anchorScoreQ3S1Q1+$anchorScoreQ3S1Q3;
        $currentAnchorPassRate = round($currentAnchorScore/ 9.53704174*100);
        Grades::where('examinee_number', substr($userID, 1))->where('level', 3)->update([

            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec1_score' => $section1Total
        ]);
        $scoreQ3S1Q5 = $scoring;
        Session::put($userID.'.Q3S1Q5Score', $scoreQ3S1Q5);
        // error_log($scoreQ4S1Q4);
        // Session::put('idTester',$userID);    
        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 2]);

        return Redirect::to(url('/Q3ReadingWelcome'));

    }

    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q3S1Q5_".$questionNumber;

        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section1Question4Choice = $currentId.".Q3S1Q5_".$questionNumber;

        $sess = Session::get($section1Question4Choice);

        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    public function endVocabularyQ4 (){

        return view('endVocabularyQ3');
    }

    function showDataBase()
    {
        $vocabulary_class =['010','030','040','050060','080090100'];

        $results = Q3Section1Question5::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get();

        $array = $results->toArray();
        $resultarray = [];
        foreach($array as $val)
        { 
            $value = new Q3S1Q5(
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
        $i=0;
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
            $elements->setQuestionId($key+31);
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
            }else  $resultclass = $class;  
            array_push($vocabulary_class2,$resultclass);
        }

        
        shuffle($array);
        $newQuestion = null;
        $resultarray = [];       
        array_unshift($vocabulary_class2,'0');
        
        foreach($array as $val)
        { 
            $index = array_search($val->partOfSpeech, $vocabulary_class2);
            
            if($index!=null)
            {                
                if($val->newQuestion==1 and $newQuestion==null)
                {  
                    $newQuestion = array_search($val->partOfSpeech, $vocabulary_class2);                  
                    unset($vocabulary_class2[$index]);  
                    array_push($resultarray, $val->id);

                    if($newQuestion!=null) break;
                    else continue;
                }                             
            }
        }
        
        foreach($array as $val)
        { 
            $index = array_search($val->partOfSpeech, $vocabulary_class2);
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

    function hasDupe($array)
    {
        return count($array) !== count(array_unique($array));
    }

    public function paginate($items, $perPage = 2, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question5Id = $currentId.".Q3S1Q5";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question5Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q3\Vocabulary\paginationDataQ3S1Q5', compact('data'))->render(); //
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


    function removeElementFromArray($del_val,$array){
        // dd($del_val,$array);
        if (($key = array_search($del_val, $array)) !== false) {
            unset($array[$key]);
            return $array;
        }
    }

    
}
