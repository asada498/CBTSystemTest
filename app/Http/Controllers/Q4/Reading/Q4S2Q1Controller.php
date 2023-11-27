<?php

namespace App\Http\Controllers\Q4\Reading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question1;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q4\Reading\Q4S2Q1;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Session;
use Illuminate\Support\Facades\Config;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;

class Q4S2Q1Controller extends Controller
{
    public function showQuestion (){
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $currentId = Session::get('idTester');
        $section2Question1Id = $currentId.".Q4S2Q1";
        // if (!(Session::has($section2Question1Id))) {
            $questionData = $this->showDataBase();
            Session::put($section2Question1Id, $questionData);
        // }
        $questionDataLoad = Session::get($section2Question1Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q4\Reading\paginationQ4S2Q1', compact('data'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorPassFlag = 0;

        if (!(Session::has('idTester'))) {
            $userIDTemp = "123test";
            Session::put('idTester', $userIDTemp);
        }
        $userID = Session::get('idTester');
        $section2Question1Id = $userID.".Q4S2Q1";

        $questionDataLoad = Session::get($section2Question1Id);
        Session::put($userID.".Q4S2Q1Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q4S2Q1_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment', '5-2-1')->first()->code;
            //    $correctFlag;
            //    $passFail;

            if ($question->getAnchor() == 'R') {
                $anchorFlag = 1;
            } else {
                $anchorFlag = 0;
            }

            if ($question->getCorrectChoice() == $userAnswer) {
                $correctFlag = 1;
                $scoring++;
                array_push($correctAnswer, $question->getId());

                if ($question->getAnchor() == 'R') {
                    $anchorPassFlag += 1;
                }
            } else {
                if ($question->getCorrectChoice() == null)
                    $correctFlag = null;
                else {
                    $correctFlag = 0;
                    array_push($incorrectAnswer, $question->getId());
                }
            }
            AnswerRecord::insert(
                [
                    'examinee_number' => substr($userID, 1),
                    'level' => 4,
                    'section' => 2,
                    'question' => 1,
                    'number' => $questionId,
                    'question_type' => $codeQuestion,
                    'question_table_name' => 'q_42_01',
                    'question_id' => $question->getDatabaseQuestionId(),
                    'anchor' => $anchorFlag,
                    'choice' => $userAnswer,
                    'correct_answer' => $question->getCorrectChoice(),
                    'pass_fail' => $correctFlag,
                ]
            );
        }
        //update record on database
        Q4Section2Question1::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q4Section2Question1::raw("past_testee_number + 1"),
            "correct_testee_number" => Q4Section2Question1::raw("correct_testee_number + 1")
        ]);
        Q4Section2Question1::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q4Section2Question1::raw("past_testee_number + 1")
        ]);

        
        $s2Q1Rate = round($scoring * 100 / 13);
        Session::put($userID.".Q4S2Q1Score_anchor", 13.5 / 80 * 120 / 13 * $anchorPassFlag);

        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 4)->update([
                's2_q1_correct' => $scoring,
                's2_q1_question' => 13,
                's2_q1_perfect_score' => 13.5 / 80 * 120,
                's2_q1_anchor_pass' => $anchorPassFlag,
                's2_q1_rate' => $s2Q1Rate
            ]);

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q4S2Q1'))
            {
                if ($key !== 'Q4S2Q1Score' && $key !== 'Q4S2Q1Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ4S2Q1 = $scoring;
        Session::put($userID.'.Q4S2Q1Score', $scoreQ4S2Q1);

        return Redirect::to(url('/Q4ReadingQ2'));
    }
   function fetchData(Request $request)
   {
    $currentId = Session::get('idTester');
    $section2Question1Id = $currentId.".Q4S2Q1";
    if($request->ajax())
    {
        $questionDataLoad = Session::get($section2Question1Id);
        $data = $this->paginate($questionDataLoad);

        return view('Q4\Reading\paginationDataQ4S2Q1', compact('data'))->render();
    }
   }
   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q4S2Q1_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);

   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question1Choice = $currentId.".Q4S2Q1_".$questionNumber;

        $sess = Session::get($section2Question1Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 
        $particalId = [];
        $conjugationConnectionId = [];
        $sentencePatternsId = [];
        $contextId = [];
        $extraId = [];
        $anchorId = [];
        $newQuestionId = [];
        
        $results = Q4Section2Question1::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->get();
        foreach ($results as $user) {
            $value = new Q4S2Q1( //q4s4
                $user->id,
                $user->question_id,
                $user->grammar,
                $user->class_grammar,
                $user->kanji,
                $user->past_testee_number,
                $user->correct_testee_number,
                $user->anchor,
                $user->question,
                $user->choice_a,
                $user->choice_b,
                $user->choice_c,
                $user->choice_d,
                $user->correct_answer,
                $user->new_question

            );
            array_push($valueArray,$value);
            $classGrammarType = $user->class_grammar;
            $idQuestion = $user->id;
            $anchor = $user->anchor;
            $newQuestionFlag = $user->new_question;
            if ($anchor == "1")
            {
                array_push($anchorId,$idQuestion);
            }
            else if ($newQuestionFlag == 1)
            {
                array_push($newQuestionId,$idQuestion);
            }
            else switch ($classGrammarType){
                case "010":
                    array_push($particalId,$idQuestion);
                    break;
                case "020":
                case "050":
                case "051":
                    array_push($conjugationConnectionId,$idQuestion);
                    break;
                case "030":
                    array_push($sentencePatternsId,$idQuestion);
                    break;
                case "040":
                    array_push($contextId,$idQuestion);
                    break;
                case "060":
                    array_push($extraId,$idQuestion);
                default:
                    break;
            }
        }
        $counter = 0;
        $questionIdArray = [];
        while($counter == 0)
        {
        $questionIdArray = static::getRandomQuestionId($particalId,$conjugationConnectionId,$sentencePatternsId,$contextId,$extraId,$anchorId,$newQuestionId,$valueArray);
        shuffle($questionIdArray);
        $grammarArray = [];
        $kanjiArray = [];
        $counterAnchor = 0;
        
        foreach($questionIdArray as $idValueInArray)
        {   
            $idValue = static::searchForId($idValueInArray, $valueArray);
            array_push($grammarArray, $valueArray[$idValue]->getGrammar());
            array_push($kanjiArray, $valueArray[$idValue]->getKanji());

            if (in_array($idValueInArray, $anchorId))
                $counterAnchor++;
        }
        

        if (!($this->hasDupe($grammarArray,$kanjiArray)) && $counterAnchor == 1)
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
            $elements->setQuestionId($key+1);
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
    
    function getRandomQuestionId ($particalId,$conjugationConnectionId,$sentencePatternsId,$contextId,$extraId,$anchorId,$newQuestionId,$valueArray)
    {
        // dd($nounId,$katakanaId,$verbId,$suruVerbId,$iAdjectiveId,$naAdjectiveId,$adverbId,$anchorId,$newQuestionId,$valueArray);
        $counterLoop = 0;
        $result = [];
        while ($counterLoop == 0) {
            $valueAnchor = "0";
            $anchorId1 = 0;
            $valueNewQuestion = "0";
            if (!empty($anchorId)) {
                $arrayAnchorId = array_rand($anchorId, 1);
                $anchorId1 = $anchorId[$arrayAnchorId];
                foreach ($valueArray as $val) {
                    if ($val->getId() === $anchorId1) {
                        $valueAnchor = $val->getGrammarClass();
                        if ($valueAnchor == "020" || $valueAnchor == "050" || $valueAnchor == "051" ){
                            $valueAnchor = "020050051";
                        }
                    }
                }
            }
            $valueNewQuestion = "0";
            $newQuestionId1 = 0;
            if (!empty($newQuestionId)) {
                $arrayNewQuestionId = array_rand($newQuestionId, 1);
                $newQuestionId1 = $newQuestionId[$arrayNewQuestionId];
                foreach ($valueArray as $val) {
                    if ($val->getId() === $newQuestionId1) {
                        $valueNewQuestion = $val->getGrammarClass();
                        if ($valueNewQuestion == "020" || $valueNewQuestion == "050" || $valueNewQuestion == "051" ){
                            $valueNewQuestion = "020050051";
                        }
                    }
                }
            }
            $partOfSpeechArray = ["010", "010", "020050051","020050051","020050051", "020050051", "020050051","030","030","030","030","040","060"];
            $partOfSpeechArray = static::removeElementFromArray($valueAnchor, $partOfSpeechArray);
            $partOfSpeechArray = static::removeElementFromArray($valueNewQuestion, $partOfSpeechArray);            
            if (!empty($partOfSpeechArray))
            {
                $result1 = [];
                shuffle($partOfSpeechArray);
                $lengthPairArray = count($partOfSpeechArray);

                for ($x = 0; $x < $lengthPairArray; $x++) {
                    $element1 = $partOfSpeechArray[$x];
                    switch ($element1) {
                        case "010":
                            $val = $particalId[array_rand($particalId)];
                            array_push($result1, $val);
                            break;
                        case "020050051":
                            $val = $conjugationConnectionId[array_rand($conjugationConnectionId)];
                            array_push($result1, $val);
                            break;
                        case "030":
                            $val = $sentencePatternsId[array_rand($sentencePatternsId)];
                            array_push($result1, $val);
                            break;
                        case "040":
                            $val = $contextId[array_rand($contextId)];
                            array_push($result1, $val);
                            break;
                        case "060":
                            $val = $extraId[array_rand($extraId)];
                            array_push($result1, $val);
                            break;
                        default:
                            break;
                    }
                }
                array_push($result1, $newQuestionId1);
                array_push($result1, $anchorId1);

                $result = $result1;
                $counterLoop = 1;
            }
        }
        return $result;
    }
}