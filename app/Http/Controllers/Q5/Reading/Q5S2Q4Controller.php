<?php

namespace App\Http\Controllers\Q5\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question4;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q5\Reading\Q5S2Q4;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use DateTime;

use Session;

class Q5S2Q4Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question4Id = $currentId.".Q5S2Q4";

        $dt = new DateTime();
        $result = $dt->format('Y-m-d');
        $userIDNum = substr($currentId, 1);
        $folderPath = base_path().'/testerLog'.'/'.$result.'/'.$userIDNum.'.txt';

        file_put_contents($folderPath,"Q5S2Q4 question search \n",FILE_APPEND);

        // if (!(Session::has( $section2Question4Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put( $section2Question4Id,$questionData);        
        // }

        file_put_contents($folderPath,"User ID no ".$userIDNum." start the 5QS2Q4. \n",FILE_APPEND);

        $questionDataLoad = Session::get( $section2Question4Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q5\Reading\pageQ5S2Q4', compact('data')); //

    }

    public function getResultToCalculate (Request $request){

        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        $section2Question4Id = $userID.".Q5S2Q4";
        $questionDataLoad = Session::get($section2Question4Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S2Q4_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-4')->first()->code;
            $correctFlag;
            $passFail;

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
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',4)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',4)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_04',
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
                     'level' => 5,
                     'section'=> 2,
                     'question' => 4,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_04',
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
        
        Q5Section2Question4::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section2Question4::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section2Question4::raw("correct_testee_number + 1")
        ]);
        Q5Section2Question4::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section2Question4::raw("past_testee_number + 1")
        ]);

        $s2Q4Rate = round($scoring * 100 / 2);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q4_correct' => $scoring,
            's2_q4_question' => 2,
            's2_q4_perfect_score' => 12,
            's2_q4_anchor_pass' => $anchorFlag,
            's2_q4_rate' => $s2Q4Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q5S2Q4'))
                {
                    if ($key !== 'Q5S2Q4Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ5S2Q4 = $scoring;
        Session::put($userID.'.Q5S2Q4Score', $scoreQ5S2Q4);

        return Redirect::to(url('/Q5ReadingQ5'));
        
    }

   public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S2Q4_".$questionNumber;
        Session::put($valueSession,$answer);  
        return response()->json(['success'=>$valueSession]);

   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question4Choice = $currentId.".Q5S2Q4_".$questionNumber;

        $sess = Session::get($section2Question4Choice);        
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 
        $allQuestionIDArray = [];
        $group1Id = [];
        $group2Id = [];
        $newQuestionId = [];
        $results = Q5Section2Question4::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->orWhere('new_question', 1)->get();
        foreach ($results as $record) {
            $value = new Q5S2Q4($record->id,$record->question_id,$record->category_of_question,$record->theme,$record->past_testee_number,$record->correct_testee_number,
            $record->text,$record->question,$record->choice_a,$record->choice_b,$record->choice_c,$record->choice_d,$record->correct_answer,$record->new_question);
            array_push($valueArray,$value);

            $idQuestion = $record->id;
            $categoryOfQuestion = $record->category_of_question;
            $newQuestionFlag = $record->new_question;

            if ($newQuestionFlag == 1) {
                array_push($newQuestionId, $idQuestion);
            }
            switch ($categoryOfQuestion){
                case "070":
                case "080":
                case "060":
                    array_push($group1Id,$idQuestion);
                    break;
                case "010":
                case "020":
                case "030":
                case "040":
                case "050":
                case "090":
                case "100":
                    array_push($group2Id,$idQuestion);
                    break;
                default:
                    break;
            }
        }
        $counter = 0;
        $questionArray = [];

        $questionList = [];
        
        while($counter == 0)
        {
            $questionIdArray = static::getRandomQuestionId($group1Id,$group2Id,$newQuestionId,$valueArray);
            shuffle($questionIdArray);

            $textArray = [];
        foreach($questionIdArray as $idValueInArray)
        {
            $idValue = static::searchForId($idValueInArray, $valueArray);
            array_push($textArray,$valueArray[$idValue]->getText());
        }
        if (!($this->hasDupe($textArray)))
            $counter = 1;
        }
        
        foreach ($questionIdArray as $id) {

            $idValue = static::searchForId($id, $valueArray);
            array_push($questionList, $valueArray[$idValue]);
        }      

        foreach($questionList as $key=>$elements)
        {
            $valueKey = $key;
            $elements->setQuestionId($key+18);
        }
        return $questionList;
    }

    function hasDupe($array) {
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

     function getRandomQuestionId($group1IdArray,$group2IdArray,$newQuestionId,$valueArray)
     {
 
        // $arrayGroup1Id = array_rand($group1Id, 1); 
        // $group1Id1 = $group2Id[$arrayGroup1Id];

        // $arrayGroup2Id = array_rand($group2Id, 1); 
        // $group2Id1 = $group2Id[$arrayGroup2Id];
 
        // return [$group1Id1, $group2Id1];

        $valueNewQuestion = "0";
        $newQuestionId1 = 0;
        if (!empty($newQuestionId)) {
            $arrayNewQuestionId = array_rand($newQuestionId, 1);
            $newQuestionId1 = $newQuestionId[$arrayNewQuestionId];
            foreach ($valueArray as $val) {
                if ($val->getId() === $newQuestionId1) {
                    $valueNewQuestion = $val->getCategoryOfQuestion();
                }
            }
        }

            $result = [];

            $group1Counter = 1;
            $group2Counter = 2;

            switch ($valueNewQuestion){
                case "070":
                case "080":
                case "060":
                    $group1Counter = 0;
                    break;
                case "010":
                case "020":
                case "030":
                case "040":
                case "050":
                case "090":
                case "100":
                    $group2Counter = 0;
                    break;
                default:
                    break;
            }

            $group1Array = [];
            $group2Array = [];
            
            if ($group1Counter != 0) {
                $group1Id = array_rand($group1IdArray, 1);
                $extraValue1 = $group1IdArray[$group1Id];
                array_push($group1Array, $extraValue1);
            }

            if ($group2Counter != 0) {
                $group2Id = array_rand($group2IdArray, 1);
                $extraValue2 = $group2IdArray[$group2Id];
                array_push($group2Array, $extraValue2);
            }
            $result = array_merge($group1Array, $group2Array);
            if ($newQuestionId1 != 0)
                array_push($result, $newQuestionId1);
        return $result;
     }
}