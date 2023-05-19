<?php

namespace App\Http\Controllers\Q4\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question4;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q4\Reading\Q4S2Q4;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;

use Session;

class Q4S2Q4Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question4Id = $currentId.".Q4S2Q4";
        // if (!(Session::has( $section2Question4Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put( $section2Question4Id,$questionData);        
        // }
        $questionDataLoad = Session::get( $section2Question4Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q4\Reading\pageQ4S2Q4', compact('data')); //

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
        $section2Question4Id = $userID.".Q4S2Q4";
        $questionDataLoad = Session::get($section2Question4Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q4S2Q4_'.$questionId;
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
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',4)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',4)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_42_04',
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
                     'level' => 4,
                     'section'=> 2,
                     'question' => 4,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_42_04',
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
        
        Q4Section2Question4::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q4Section2Question4::raw("past_testee_number + 1"),
            "correct_testee_number" => Q4Section2Question4::raw("correct_testee_number + 1")
        ]);
        Q4Section2Question4::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q4Section2Question4::raw("past_testee_number + 1")
        ]);

        $s2Q4Rate = round($scoring * 100 / 3);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q4_correct' => $scoring,
            's2_q4_question' => 3,
            's2_q4_perfect_score' => 10.5 / 80 * 120,
            's2_q4_anchor_pass' => $anchorFlag,
            's2_q4_rate' => $s2Q4Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q4S2Q4'))
                {
                    if ($key !== 'Q4S2Q4Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ4S2Q4 = $scoring;
        Session::put($userID.'.Q4S2Q4Score', $scoreQ4S2Q4);

        return Redirect::to(url('/Q4ReadingQ5'));
        
    }

   public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q4S2Q4_".$questionNumber;
        Session::put($valueSession,$answer);  
        return response()->json(['success'=>$valueSession]);

   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question4Choice = $currentId.".Q4S2Q4_".$questionNumber;

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

        $results = Q4Section2Question4::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->get();
        foreach ($results as $record) {
            $value = new Q4S2Q4(
                $record->id,
                $record->question_id,
                $record->class_reading_theme,
                $record->class_reading,
                $record->past_testee_number,
                $record->correct_testee_number,
                $record->text,
                $record->question,
                $record->choice_a,
                $record->choice_b,
                $record->choice_c,
                $record->choice_d,
                $record->correct_answer,
                $record->letter,
                $record->new_question
            );
            
            array_push($valueArray,$value);

            $idQuestion = $record->id;
            $classReadingType = $record->class_reading;
            $newQuestionFlag = $record->new_question;
            if ($newQuestionFlag == 1)
            {
                array_push($newQuestionId,$idQuestion);
            }
            else switch ($classReadingType){
                    case "060":
                    case "070":
                    case "080":
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
            $questionIdArray = static::getRandomQuestionId($group1Id,$group2Id);
            if(!empty($newQuestionId))
            {
            $arrayNewQuestionId = array_rand($newQuestionId, 1);
            $newQuestionId1 = $newQuestionId[$arrayNewQuestionId];
            foreach ($valueArray as $val) {
            if ($val->getId() === $newQuestionId1) {
                $valueGrammarClass = $val->getReadingClass();
                switch ($valueGrammarClass){
                    case "060":
                    case "070":
                    case "080":
                        array_splice($questionIdArray, 0,1);
                        array_push($questionIdArray,$newQuestionId1);
                        break;
                    case "010":
                    case "020":
                    case "030":
                    case "040":
                    case "050":
                    case "090":
                    case "100":       
                        array_splice($questionIdArray, 1,1);
                        array_push($questionIdArray,$newQuestionId1);
                        break;
                    default:
                        break;
                    }
                }
            }
            }
            shuffle($questionIdArray);
            $counter = 1;
        }
        
        foreach ($questionIdArray as $id) {

            $idValue = static::searchForId($id, $valueArray);
            array_push($questionList, $valueArray[$idValue]);
        }      

        foreach($questionList as $key=>$elements)
        {
            $valueKey = $key;
            $elements->setQuestionId($key+22);
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

     function getRandomQuestionId($group1Id,$group2Id)
     {
 
        $arrayGroup1Id = array_rand($group1Id, 1); 
        $group1Id1 = $group1Id[$arrayGroup1Id];

        $arrayGroup2Id = array_rand($group2Id, 2); 
        $group2Id1 = $group2Id[$arrayGroup2Id[0]];
        $group2Id2 = $group2Id[$arrayGroup2Id[1]];

        return [$group1Id1, $group2Id1,$group2Id2];
     }
}