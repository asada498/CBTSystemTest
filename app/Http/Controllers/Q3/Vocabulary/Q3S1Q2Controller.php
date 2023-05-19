<?php
namespace App\Http\Controllers\Q3\Vocabulary;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q3\Vocabulary\Q3Section1Question2;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use App\QuestionClass\Q3\Vocabulary\Q3S1Q2;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class Q3S1Q2Controller extends Controller
{
    public function showQuestion (){
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }
        $currentId = Session::get('idTester');
        $section1Question2Id = $currentId.".Q3S1Q2";
        // if (!(Session::has( $section1Question2Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put( $section1Question2Id,$questionData);        
        // }
        $questionDataLoad = Session::get( $section1Question2Id);
        // dd($questionDataLoad);
        $data = $this->paginate($questionDataLoad);
        return view('Q3\Vocabulary\paginationQ3S1Q2', compact('data'));
    }

    public function getResultToCalculate (Request $request){

        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
 
        $userID = Session::get('idTester');
        $section1Question2Id = $userID.".Q3S1Q2";
        $questionDataLoad = Session::get($section1Question2Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q3S1Q2_'.$questionId;
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
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',2)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',2)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_31_02',
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
                     'question' => 2,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_31_02',
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
        
        Q3Section1Question2::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q3Section1Question2::raw("past_testee_number + 1"),
            "correct_testee_number" => Q3Section1Question2::raw("correct_testee_number + 1")
        ]);
        Q3Section1Question2::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q3Section1Question2::raw("past_testee_number + 1")
        ]);

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q3Section1Question2::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q3Section1Question2::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q2Rate = round($scoring * 100 / 6);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q2_correct' => $scoring,
            's1_q2_question' => 6,
            's1_q2_perfect_score' => 4 / 55 * 60,
            's1_q2_anchor_pass' => $anchorFlag,
            's1_q2_rate' => $s1Q2Rate]);
            
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S1Q2'))
                {
                    if ($key !== 'Q3S1Q2Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
            
        $scoreQ3S1Q2 = $scoring;
        Session::put($userID.'.Q3S1Q2Score', $scoreQ3S1Q2);
        return Redirect::to(url('/Q3VocabularyQ3'));
        
    }

   function fetchData(Request $request)
   {
        $currentId = Session::get('idTester');
        $section1Question2Id = $currentId.".Q3S1Q2";
        if($request->ajax())
        {
            $questionDataLoad = Session::get($section1Question2Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q3\Vocabulary\paginationDataQ3S1Q2', compact('data'))->render();
        }
   }
   public function saveChoiceRequestPost(Request $request)
   {       
    $currentId = Session::get('idTester');
    $questionNumber = $request->get('name');
    error_log($questionNumber);

    $answer = $request->get('answer');
    $valueSession = $currentId.".Q3S1Q2_".$questionNumber;
    Session::put($valueSession, $answer);
    return response()->json(['success' => $valueSession]);

   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question2Choice = $currentId.".Q3S1Q2_".$questionNumber;

        $sess = Session::get($section1Question2Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    public function example (){

        return view('example');
    }

    function showDataBase()
    {
        $results = Q3Section1Question2::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->get();  
        $array = $results->toArray();
        $resultarray = [];
        foreach($array as $val)
        { 
            $value = new Q3S1Q2(
                $val['id'],
                $val['question_id'],
                $val['vocabulary'],
                $val['class_vocabulary'],
                $val['kanji'],
                $val['class_kanji_writing'],                
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

        $vocabulary_class =['010','030','040','050','060','080100'];
        $kanjiWriting_class =['104','201','203','301','301','302'];

        $questionIdArray = [];

        $count = 0; $i=0;
        while($count==0)
        {
            $questionIdArray = static::getRandomQuestionId($vocabulary_class, $kanjiWriting_class, $resultarray);
            
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
            array_push($questionList, $resultarray[$idValue]);
        }

        foreach ($questionList as $key => $elements) {
            $elements->setQuestionId($key+9);
        }
        //if($lengthArr>1)dd('database information is not enough for requarement'); 
        return $questionList;    
    }

    function getRandomQuestionId($vocabulary_class, $kanjiWriting_class, $array){
                
        //listening classdagi ikkita nuqilay ma'lunotni to'g'irlash
        $vocabulary_class2 =[];

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

        shuffle($array);
        $resultarray = [];
        $newQuestion = null;
       
        array_unshift($vocabulary_class2,'0');
        array_unshift($kanjiWriting_class,'0');
        
        foreach($array as $val)
        {
            $index = array_search($val->classVocabulary, $vocabulary_class2);
            $index2 = array_search($val->classKanjiWriting, $kanjiWriting_class);
            
            if($index!=null and $index2!=null)
            {                
                if($val->newQuestion==1 and $newQuestion==null)
                {  
                    $newQuestion = array_search($val->classVocabulary, $vocabulary_class2);                  
                    unset($vocabulary_class2[$index]);
                    unset($kanjiWriting_class[$index2]);   
                    array_push($resultarray, $val->id);

                    if($newQuestion!=null) break;
                    else continue;
                }                             
            }
        }
        foreach($array as $val)
        { 
            
            $index = array_search($val->classVocabulary, $vocabulary_class2);
            $index2 = array_search($val->classKanjiWriting, $kanjiWriting_class);

            
            if($index!=null and $index2!=null)
            {               
                if($val->newQuestion==1) continue;                              
                else
                {
                    //array_push($resultarray,$val);
                    array_push($resultarray, $val->id);
                    unset($vocabulary_class2[$index]);
                    unset($kanjiWriting_class[$index2]);
                    $lengthArr = count($vocabulary_class2);
                    if($lengthArr==1){break;}
                }           
                                               
            }
        }
    
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
    
}