<?php

namespace App\Http\Controllers\Q4\Vocabulary;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Vocabulary\Q4Section1Question3;
use App\TestInformation;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q4\Vocabulary\Q4S1Q3;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Session;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class Q4S1Q3Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section1Question3Id = $currentId.".Q4S1Q3";
        // if (!(Session::has($section1Question3Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question3Id, $questionData);
        // }
        // $questionData = $this->showDataBase();
        // Session::put('Q4S1Q3',$questionData);   
        $questionDataLoad = Session::get($section1Question3Id);
        // dd($questionDataLoad);
        // dd ($questionDataLoad);
        $data = $this->paginate($questionDataLoad);

        return view('Q4\Vocabulary\paginationQ4S1Q3', compact('data'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        $s1Q3Rate = 0;
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "130test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        $section1Question3Id = $userID.".Q4S1Q3";
        
        $questionDataLoad = Session::get($section1Question3Id);
        Session::put($userID.".Q4S1Q3Score_anchor", 0);

        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q4S1Q3_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-3')->first()->code;
                $correctFlag;
                $passFail;
                // dd($question->getId());
                if($question->getAnchor() == "R")
                        $anchorFlag = 1;
                    else $anchorFlag = 0;
                if ($question->getCorrectChoice() == $userAnswer)
                {
                    $correctFlag = 1;
                    if ($anchorFlag == 1)
                    {
                        $anchorFlagResult = 1;
                        Session::put($userID.".Q4S1Q3Score_anchor", 6 / 80 * 120 / 8);
                    }
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
                if (AnswerRecord::where('examinee_number',$userID)->where('level',4)->where('section',1)->where('question',3)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',$userID)->where('level',4)->where('section',1)->where('question',3)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_41_03',
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
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_41_03',
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
            
            Q4Section1Question3::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q4Section1Question3::raw("past_testee_number + 1"),
                "correct_testee_number" => Q4Section1Question3::raw("correct_testee_number + 1")
            ]);
            Q4Section1Question3::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q4Section1Question3::raw("past_testee_number + 1")
            ]);
        }

        $s1Q3Rate = round($scoring * 100 / 8);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q3_correct' => $scoring,
            's1_q3_question' => 8,
            's1_q3_perfect_score' => 6 / 80 * 120,
            's1_q3_anchor_pass' => $anchorFlagResult,
            's1_q3_rate' => $s1Q3Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q4S1Q3'))
                {
                    if ($key !== 'Q4S1Q3Score' && $key !== 'Q4S1Q3Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        
        $scoreQ4S1Q3 = $scoring;
        // Session::put('Q4S1Q1Score',$scoreQ4S1Q1);    
        // Session::put('Q4S1Q2Score',$scoreQ4S1Q2);    
        Session::put($userID.'.Q4S1Q3Score', $scoreQ4S1Q3);
        // Session::put('idTester',$userID);    

        error_log($scoreQ4S1Q3);
        return Redirect::to(url('/Q4VocabularyQ4'));

    }

   function fetchData(Request $request)
   {
    $currentId = Session::get('idTester');
    $section1Question3Id = $currentId.".Q4S1Q3";

    if ($request->ajax()) {
        $questionDataLoad = Session::get($section1Question3Id);
        $data = $this->paginate($questionDataLoad);

        return view('Q4\Vocabulary\paginationDataQ4S1Q3', compact('data'))->render(); //
    }
   }
   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q4S1Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);

   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question3Choice = $currentId.".Q4S1Q3_".$questionNumber;

        $sess = Session::get($section1Question3Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 
        $nounId = [];
        $katakanaId = [];
        $verbId = [];
        $iAdjectiveId = [];
        $naAdjectiveId = [];
        $adverbId = [];
        $anchorId = [];
        $suruVerbId = [];
        $newQuestionId = [];

        $results = Q4Section1Question3::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->get();
        foreach ($results as $user) {
            $value = new Q4S1Q3(
                $user->id,
                $user->question_id,
                $user->vocabulary,
                $user->class_vocabulary,
                $user->kanji,
                $user->correct_answer_rate,
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
            $partOfSpeechType = $user->class_vocabulary;

            $idQuestion = $user->id;
            $anchor = $user->anchor;
            $newQuestionFlag = $user ->new_question;

            if ($anchor == 1)
            {
                array_push($anchorId,$idQuestion);
            }
            else if ($newQuestionFlag == 1)
            {
                array_push($newQuestionId,$idQuestion);
            }
            else 
            switch ($partOfSpeechType){
                case "010":
                    array_push($nounId,$idQuestion);
                    break;
                case "020":
                    array_push($katakanaId,$idQuestion);
                    break;
                case "040":
                    array_push($verbId,$idQuestion);
                    break;
                case "030":
                    array_push($suruVerbId,$idQuestion);
                    break;
                case "050":
                    array_push($iAdjectiveId,$idQuestion);
                    break;
                case "060":
                case "090":
                case "100":
                    array_push($naAdjectiveId,$idQuestion);
                    break;
                case "080":
                    array_push($adverbId,$idQuestion);
                    break;
                default:
                    break;
            }

        }
        // dd($anchorId);
        $counter = 0;
        $counter1 = 0;
        $questionIdArray = [];
        while($counter == 0)
        {
        $questionIdArray = static::getRandomQuestionId($nounId,$katakanaId,$verbId,$suruVerbId,$iAdjectiveId,$naAdjectiveId,$adverbId,$anchorId,$newQuestionId,$valueArray);
        $counter1++;
        shuffle($questionIdArray);

            // $counter101 = 0;
            // $counter102 = 0;
            // $counter104 = 0;
            // $counter105 = 0;
            // $counter301 = 0;
            // $counter302 = 0;
            // $counter303 = 0;
            // $counterAnchor = 0;

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
        foreach ($questionIdArray as $id) {

            $idValue = static::searchForId($id, $valueArray);
            array_push($questionList, $valueArray[$idValue]);
        }
        foreach ($questionList as $key => $elements) {
            $valueKey = $key;
            $elements->setQuestionId($key + 13);
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
    
    public function paginate($items, $perPage = 4, $page = null, $options = [])
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

    function getRandomQuestionId ($nounId,$katakanaId,$verbId,$suruVerbId,$iAdjectiveId,$naAdjectiveId,$adverbId,$anchorId,$newQuestionId,$valueArray)
    {
        // dd($nounId,$katakanaId,$verbId,$suruVerbId,$iAdjectiveId,$naAdjectiveId,$adverbId,$anchorId,$newQuestionId,$valueArray);
        $counterLoop = 0;
        $result = [];
        while ($counterLoop == 0) {
            $valueAnchor = "0";
            $anchorId1 = 0;
            $valueNewQuestionPartOfSpeech = "0";
            if (!empty($anchorId)) {
                $arrayAnchorId = array_rand($anchorId, 1);
                $anchorId1 = $anchorId[$arrayAnchorId];
                foreach ($valueArray as $val) {
                    if ($val->getId() === $anchorId1) {
                        $valueAnchor = $val->getPartOfSpeech();
                        if ($valueAnchor == "060" || $valueAnchor == "090" || $valueAnchor == "100" ){
                            $valueAnchor = "060090100";
                        }
                    }
                }
            }
            $valueNewQuestionPartOfSpeech = "0";
            $newQuestionId1 = 0;
            if (!empty($newQuestionId)) {
                $arrayNewQuestionId = array_rand($newQuestionId, 1);
                $newQuestionId1 = $newQuestionId[$arrayNewQuestionId];
                foreach ($valueArray as $val) {
                    if ($val->getId() === $newQuestionId1) {
                        $valueNewQuestionPartOfSpeech = $val->getPartOfSpeech();
                        if ($valueNewQuestionPartOfSpeech == "060" || $valueNewQuestionPartOfSpeech == "090" || $valueNewQuestionPartOfSpeech == "100" ){
                            $valueNewQuestionPartOfSpeech = "060090100";
                        }
                    }
                }
            }
            $partOfSpeechArray = ["010", "020", "040","040","030", "050", "060090100","080"];
            $partOfSpeechArray = static::removeElementFromArray($valueNewQuestionPartOfSpeech, $partOfSpeechArray);
            $partOfSpeechArray = static::removeElementFromArray($valueAnchor, $partOfSpeechArray);
            
            if (!empty($partOfSpeechArray))
            {
                $result1 = [];
                shuffle($partOfSpeechArray);
                $lengthPairArray = count($partOfSpeechArray);

                for ($x = 0; $x < $lengthPairArray; $x++) {
                    $element1 = $partOfSpeechArray[$x];
                    switch ($element1) {
                        case "010":
                            $val = $nounId[array_rand($nounId)];
                            array_push($result1, $val);
                            break;
                        case "020":
                            $val = $katakanaId[array_rand($katakanaId)];
                            array_push($result1, $val);
                            break;
                        case "040":
                            $val = $verbId[array_rand($verbId)];
                            array_push($result1, $val);
                            break;
                        case "030":
                            $val = $suruVerbId[array_rand($suruVerbId)];
                            array_push($result1, $val);
                            break;
                        case "050":
                            $val = $iAdjectiveId[array_rand($iAdjectiveId)];
                            array_push($result1, $val);
                            break;
                        case "060090100":
                            $val = $naAdjectiveId[array_rand($naAdjectiveId)];
                            array_push($result1, $val);
                            break;
                        case "080":
                            $val = $adverbId[array_rand($adverbId)];
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