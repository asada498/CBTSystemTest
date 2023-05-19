<?php
namespace App\Http\Controllers\Q4\Vocabulary;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Vocabulary\Q4Section1Question2;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q4\Vocabulary\Q4S1Q2;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;

use Session;

class Q4S1Q2Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section1Question2Id = $currentId.".Q4S1Q2";
        // if (!(Session::has( $section1Question2Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put( $section1Question2Id,$questionData);        
        // }
        $questionDataLoad = Session::get( $section1Question2Id);
        // dd($questionDataLoad);
        $data = $this->paginate($questionDataLoad);
        return view('Q4\Vocabulary\pageQ4S1Q2', compact('data'));
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
        $section1Question2Id = $userID.".Q4S1Q2";
        $questionDataLoad = Session::get($section1Question2Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q4S1Q2_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-1-2')->first()->code;
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
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',1)->where('question',2)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',1)->where('question',2)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_41_02',
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
                     'section'=> 1,
                     'question' => 2,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_41_02',
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
        
        Q4Section1Question2::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q4Section1Question2::raw("past_testee_number + 1"),
            "correct_testee_number" => Q4Section1Question2::raw("correct_testee_number + 1")
        ]);
        Q4Section1Question2::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q4Section1Question2::raw("past_testee_number + 1")
        ]);

        $s1Q2Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q2_correct' => $scoring,
            's1_q2_question' => 5,
            's1_q2_perfect_score' => 3.75 / 80 * 120,
            's1_q2_anchor_pass' => $anchorFlag,
            's1_q2_rate' => $s1Q2Rate]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q4S1Q2'))
                {
                    if ($key !== 'Q4S1Q2Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
            
        $scoreQ4S1Q2 = $scoring;
        Session::put($userID.'.Q4S1Q2Score', $scoreQ4S1Q2);
        return Redirect::to(url('/Q4VocabularyQ3'));
        
    }

   function fetchData(Request $request)
   {
    $currentId = Session::get('idTester');
    $section1Question2Id = $currentId.".Q4S1Q2";
    if($request->ajax())
    {
    $questionDataLoad = Session::get($section1Question2Id);
    $data = $this->paginate($questionDataLoad);

     return view('Q4\Vocabulary\paginationDataQ4S1Q2', compact('data'))->render();
    }
   }
   public function saveChoiceRequestPost(Request $request)
   {       
    $currentId = Session::get('idTester');
    $questionNumber = $request->get('name');
    error_log($questionNumber);

    $answer = $request->get('answer');
    $valueSession = $currentId.".Q4S1Q2_".$questionNumber;
    Session::put($valueSession, $answer);
    return response()->json(['success' => $valueSession]);

   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question2Choice = $currentId.".Q4S1Q2_".$questionNumber;

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
        $valueArray = []; 
        $nounId = [];
        $verbId = [];
        $iAdjectiveId = [];
        $naAdjectiveId = [];

        $type201Id = [];
        $type301Id = []; 
        $type302Id = [];
        $type104203Id = [];
        $newQuestionId = [];
        $results = Q4Section1Question2::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->get();
        foreach ($results as $user) {
            $value = new Q4S1Q2(
                $user->id,
                $user->question_id,
                $user->vocabulary,
                $user->class_vocabulary,
                $user->class_kanji_writing,
                $user->kanji,
                $user->correct_answer_rate,
                $user->past_testee_number,
                $user->correct_testee_number,
                $user->question,
                $user->choice_a,
                $user->choice_b,
                $user->choice_c,
                $user->choice_d,
                $user->correct_answer,
                $user->new_question
            );
            array_push($valueArray,$value);

            $group1 = $user ->class_vocabulary;
            $group2 = $user ->class_kanji_writing;
            $idQuestion = $user->id;
            $newQuestionFlag = $user ->new_question;
            if ($newQuestionFlag == 1)
            {
                array_push($newQuestionId,$idQuestion);
            }
            else 
            {
            switch ($group1){
                case "010":
                    array_push($nounId,$idQuestion);
                    break;
                case "040":
                    array_push($verbId,$idQuestion);
                    break;
                case "050":
                    array_push($iAdjectiveId,$idQuestion);
                    break;
                case "060":
                case "030":
                case "100":
                case "080":
                    array_push($naAdjectiveId,$idQuestion);
                    break;
                default:
                    break;
            }

            switch ($group2){
                case "201":
                    array_push($type201Id,$idQuestion);
                    break;
                case "301":
                    array_push($type301Id,$idQuestion);
                    break;
                case "302":
                    array_push($type302Id,$idQuestion);
                    break;
                case "104":
                case "203":
                    array_push($type104203Id,$idQuestion);
                    break;
                default:
                    break;
            }
        }
        }

        $counter = 0;
        $questionIdArray = [];
        while($counter == 0)
        {
        $questionIdArray = static::getRandomQuestionId($nounId,$verbId,$iAdjectiveId,$naAdjectiveId,$type201Id,$type301Id,$type302Id,$type104203Id,$newQuestionId,$valueArray);
        $answerArray = [];
        $kanjiArray = [];
            foreach ($questionIdArray as $idValueInArray) {

                // if (in_array($idValueInArray, $type101Id))
                //     $counter101++;

                // if (in_array($idValueInArray, $type102Id))
                //     $counter102++;
                // if (in_array($idValueInArray, $type104Id))
                //     $counter104++;

                // if (in_array($idValueInArray, $type105Id))
                //     $counter105++;
                // if (in_array($idValueInArray, $type301Id))
                //     $counter301++;
                // if (in_array($idValueInArray, $type302Id))
                //     $counter302++;

                // if (in_array($idValueInArray, $type303Id))
                //     $counter303++;
                // if (in_array($idValueInArray, $anchorId))
                //     $counterAnchor++;

                $idValue = static::searchForId($idValueInArray, $valueArray);

                array_push($answerArray, $valueArray[$idValue]->getvocabularyAnswer());
                array_push($kanjiArray, $valueArray[$idValue]->getKanji());
            }
            if (!($this->hasDupe($answerArray, $kanjiArray))
            ) 
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
            $elements->setQuestionId($key+8);
        }
        return $questionList;
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

    function removeElementFromArray($del_val,$array){
        // dd($del_val,$array);
        if (($key = array_search($del_val, $array)) !== false) {
            unset($array[$key]);
            return $array;
        }
    }

    function getRandomQuestionId($nounId,$verbId,$iAdjectiveId,$naAdjectiveId,$type201Id,$type301Id,$type302Id,$type104203Id,$newQuestionId,$valueArray)
    {
        $counterLoop = 0;
        $result = [];
        while ($counterLoop == 0) {
            $valueNewQuestionPartOfSpeech = "0";
            $valueNewQuestionGroup = "0";
            $newQuestionId1 = 0;

            if (!empty($newQuestionId)) {
                $arrayNewQuestionId = array_rand($newQuestionId, 1);
                $newQuestionId1 = $newQuestionId[$arrayNewQuestionId];
                foreach ($valueArray as $val) {
                    if ($val->getId() === $newQuestionId1) {

                        $valueNewQuestionPartOfSpeech = $val->getGroup1();
                        $valueNewQuestionGroup = $val->getGroup2();
                        if ($valueNewQuestionPartOfSpeech == "060" ||$valueNewQuestionPartOfSpeech == "030" ||$valueNewQuestionPartOfSpeech == "100" ||$valueNewQuestionPartOfSpeech == "080" ){
                            $valueNewQuestionPartOfSpeech = "060030100080";
                        }
                        if ($valueNewQuestionGroup == "104" ||$valueNewQuestionGroup == "203" ){
                            $valueNewQuestionGroup = "104203";
                        }
                    }
                }
            }
            $partOfSpeechArray = ["010", "010", "040", "050", "060030100080"];
            $groupArray = ["201", "201", "301", "302", "104203"];
            if ($valueNewQuestionPartOfSpeech != 0)
                $partOfSpeechArray = static::removeElementFromArray($valueNewQuestionPartOfSpeech, $partOfSpeechArray);
            if ($valueNewQuestionGroup != 0)
                $groupArray = static::removeElementFromArray($valueNewQuestionGroup, $groupArray);

            $result1 = [];
            shuffle($groupArray);
            shuffle($partOfSpeechArray);
            $pairArray = [];
            $lengthSearch = count($partOfSpeechArray);
            for ($x = 0; $x < $lengthSearch; $x++) {
                $element1 = array_pop($partOfSpeechArray);
                $element2 = array_pop($groupArray);
                array_push($pairArray, [$element1, $element2]);
            }
            $lengthPairArray = count($pairArray);
            for ($x = 0; $x < $lengthPairArray; $x++) {
                $groupId = $pairArray[$x][1];
                $partOfSpeechId = $pairArray[$x][0];
                $groupArrayChoice = [];
                $partOfSpeechChoice = [];
                switch ($groupId) {
                    case "201":
                        $groupArrayChoice = $type201Id;
                        break;

                    case "301":
                        $groupArrayChoice = $type301Id;
                        break;

                    case "302":
                        $groupArrayChoice = $type302Id;
                        break;

                    case "104203":
                        $groupArrayChoice = $type104203Id;
                        break;

                }

                switch ($partOfSpeechId) {
                    case "010":
                        $partOfSpeechChoice = $nounId;
                        break;

                    case "040":
                        $partOfSpeechChoice = $verbId;
                        break;

                    case "050":
                        $partOfSpeechChoice = $iAdjectiveId;
                        break;

                    case "060030100080":
                        $partOfSpeechChoice = $naAdjectiveId;
                        break;
                }
                $arrayVal = array_intersect($groupArrayChoice, $partOfSpeechChoice);
                if (!empty($arrayVal)) {
                    $val = $arrayVal[array_rand($arrayVal)];
                    array_push($result1, $val);
                } else {
                    // dd($groupArrayChoice,$partOfSpeechChoice);
                }
            }
            if ($newQuestionId1 != 0)
                array_push($result1, $newQuestionId1);
            if (count($result1) == 5) {
                $result = $result1;
                $counterLoop = 1;
            }
        }
        return $result;
    }
}