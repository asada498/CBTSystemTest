<?php

namespace App\Http\Controllers\Q5\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question2;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q5\Reading\Q5S2Q2;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use DateTime;

use Session;

class Q5S2Q2Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question2Id = $currentId.".Q5S2Q2";

        $dt = new DateTime();
        $result = $dt->format('Y-m-d');
        $userIDNum = substr($currentId, 1);
        $folderPath = base_path().'/testerLog'.'/'.$result.'/'.$userIDNum.'.txt';
        file_put_contents($folderPath,"Q5S2Q2 question search \n",FILE_APPEND);

        // if (!(Session::has( $section2Question2Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put( $section2Question2Id,$questionData);        
        // }

        file_put_contents($folderPath,"User ID no ".$userIDNum." start the 5QS2Q2. \n",FILE_APPEND);

        $questionDataLoad = Session::get($section2Question2Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q5\Reading\pageQ5S2Q2', compact('data')); //

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
        $section2Question2Id = $userID.".Q5S2Q2";
        $questionDataLoad = Session::get($section2Question2Id);
        // dd(Session::all());        
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S2Q2_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-2')->first()->code;
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
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',2)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',2)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_02',
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
                     'question' => 2,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_02',
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
        
        Q5Section2Question2::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section2Question2::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section2Question2::raw("correct_testee_number + 1")
        ]);
        Q5Section2Question2::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section2Question2::raw("past_testee_number + 1")
        ]);

        $s2Q2Rate = round($scoring * 100 / 4);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q2_correct' => $scoring,
            's2_q2_question' => 4,
            's2_q2_perfect_score' => 11,
            's2_q2_anchor_pass' => $anchorFlag,
            's2_q2_rate' => $s2Q2Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q5S2Q2'))
                {
                    if ($key !== 'Q5S2Q2Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ5S2Q2 = $scoring;
        Session::put($userID.'.Q5S2Q2Score', $scoreQ5S2Q2);

        return Redirect::to(url('/Q5ReadingQ3'));
        
    }
   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S2Q2_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question2Choice = $currentId.".Q5S2Q2_".$questionNumber;

        $sess = Session::get($section2Question2Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 
        $particalId = [];
        $conjugationConnectionId = [];
        $sentencePatternId = [];
        $newQuestionId = [];
        $results = Q5Section2Question2::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->orWhere('new_question', 1)->get();
        foreach ($results as $record) {
            $value = new Q5S2Q2($record->id,$record->question_id,$record->sentence_pattern,$record->sentence_pattern_classification,$record->past_testee_number,$record->correct_testee_number,
            $record->question,$record->choice_a,$record->choice_b,$record->choice_c,$record->choice_d,$record->correct_answer,$record->new_question);
            array_push($valueArray,$value);

            $sentencePatternClassificationType = $record->sentence_pattern_classification;
            $idQuestion = $record->id;
            $newQuestionFlag = $record->new_question;
            if ($newQuestionFlag == 1) {
                array_push($newQuestionId, $idQuestion);
            }
            switch ($sentencePatternClassificationType){
                case "010":
                    array_push($particalId,$idQuestion);
                    break;
                case "030":
                    array_push($sentencePatternId,$idQuestion);
                    break;
                default:
                    break;
            }
        }
        $counter = 0;
        $questionIdArray = [];
        while($counter == 0)
        {
        $questionIdArray = static::getRandomQuestionId($particalId,$sentencePatternId);
        shuffle($questionIdArray);
        $newQuestionCounter = 0;

        $answerArray = [];
        foreach($questionIdArray as $idValueInArray)
        {
            $idValue = static::searchForId($idValueInArray, $valueArray);
            array_push($answerArray,$valueArray[$idValue]->getSentencePattern());
            if (in_array($idValueInArray, $newQuestionId))
                $newQuestionCounter++;
        }
        if ($newQuestionCounter == 1 && !($this->hasDupe($answerArray)) )
            $counter = 1;
        }

        $questionList = [];
        foreach($questionIdArray as $id)
        {
            $idValue = static::searchForId($id, $valueArray);
            array_push($questionList,$valueArray[$idValue]);
        }
        foreach($questionList as $key=>$elements)
        {
            $valueKey = $key;
            $elements->setQuestionId($key+10);
        }
        return $questionList;
    }

    function hasDupe($array) {
        return count($array) !== count(array_unique($array));
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
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

    function getRandomQuestionId ($particalId,$sentencePatternId)
    {
        $arrayPartialId = array_rand($particalId, 2);
        $partialId1 = $particalId[$arrayPartialId[0]];
        $partialId2 = $particalId[$arrayPartialId[1]];

        $arraySentencePatternId = array_rand($sentencePatternId, 2);
        $sentencePatternId1 = $sentencePatternId[$arraySentencePatternId[0]];
        $sentencePatternId2 = $sentencePatternId[$arraySentencePatternId[1]];
        return [$partialId1,$partialId2,$sentencePatternId1,$sentencePatternId2];
    }
}