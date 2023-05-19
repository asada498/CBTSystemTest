<?php
namespace App\Http\Controllers\Q5\Vocabulary;

use App\Http\Controllers\Controller;
use App\QuestionClass\Q5\Vocabulary\Q5S1Q1;
use App\QuestionDatabase\Q5\Vocabulary\Q5Section1Question1;
use App\AnswerRecord;
use App\ExamineeLogin;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use DateTime;

use function GuzzleHttp\Psr7\parse_request;

class Q5S1Q1Controller extends Controller
{
    public function showQuestion(Request $request)
    {
        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q123123123131";
            Session::put('idTester',$userIDTemp);        
        }
        $currentId = Session::get('idTester');
        $section1Question1Id = $currentId.".Q5S1Q1";
        
        $dt = new DateTime();
        $result = $dt->format('Y-m-d');
        $userIDNum = substr($currentId, 1);
        $path = base_path().'/testerLog'.'/'.$result.'/'.$userIDNum.'.txt';
        file_put_contents($path,"Q5S1Q1 question search \n",FILE_APPEND);
        // if (!(Session::has($section1Question1Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question1Id, $questionData);
        // }

        file_put_contents($path,"User ID no ".$userIDNum." start the 5QS1Q1. \n",FILE_APPEND);

        $questionDataLoad = Session::get($section1Question1Id);
        $data = $this->paginate($questionDataLoad);
        $data->withPath('Q5VocabularyQ1');
        return view('Q5\Vocabulary\paginationQ5S1Q1',compact('data'));
    }


    public function getResultToCalculate(Request $request)
    {

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
        $section1Question1Id = $userID.".Q5S1Q1";

        $questionDataLoad = Session::get($section1Question1Id);
        Session::put($userID.".Q5S1Q1Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S1Q1_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment', '5-1-1')->first()->code;

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
                    $anchorPassFlag = 1;
                    Session::put($userID.".Q5S1Q1Score_anchor", 1.5714286);
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
                    'level' => 5,
                    'section' => 1,
                    'question' => 1,
                    'number' => $questionId,
                    'question_type' => $codeQuestion,
                    'question_table_name' => 'q_51_01',
                    'question_id' => $question->getDatabaseQuestionId(),
                    'anchor' => $anchorFlag,
                    'choice' => $userAnswer,
                    'correct_answer' => $question->getCorrectChoice(),
                    'pass_fail' => $correctFlag,
                ]
            );
        }
        //update record on database
        Q5Section1Question1::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section1Question1::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section1Question1::raw("correct_testee_number + 1")
        ]);
        Q5Section1Question1::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section1Question1::raw("past_testee_number + 1")
        ]);


        $s1Q1Rate = round($scoring * 100 / 7);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q1_correct' => $scoring,
            's1_q1_question' => 7,
            's1_q1_perfect_score' => 11,
            's1_q1_rate' => $s1Q1Rate,
            's1_q1_anchor_pass' => $anchorPassFlag]);

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q5S1Q1'))
            {
                if ($key !== 'Q5S1Q1Score' && $key !== 'Q5S1Q1Score_anchor')
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ5S1Q1 = $scoring;
        Session::put($userID.'.Q5S1Q1Score', $scoreQ5S1Q1);
        
        return Redirect::to(url('/Q5VocabularyQ2'));
    }


    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question1Id = $currentId.".Q5S1Q1";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question1Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q5\Vocabulary\paginationDataQ5S1Q1', compact('data'))->render(); //
        }
    }

    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S1Q1_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section1Question1Choice = $currentId.".Q5S1Q1_".$questionNumber;

        $sess = Session::get($section1Question1Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }


    function showDataBase()
    {
        $valueArray = [];

        $nounId = []; //1
        $verbId = [];
        $iAdjectiveId = [];
        $naAdjectiveId = [];
        $affixId = [];
        $anchorId = [];

        $type101Id = [];
        $type102Id = [];
        $type104Id = [];
        $type105Id = [];
        $type301Id = [];
        $type302Id = [];
        $type303Id = [];

        $newQuestionId = [];


        $results = Q5Section1Question1::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->get();
        foreach ($results as $user) {
            $value = new Q5S1Q1(
                $user->id,
                $user->question_id,
                $user->vocabulary,
                $user->kanji,
                $user->part_of_speech,
                $user->group1,
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
            array_push($valueArray, $value);

            $partOfSpeechType = $user->part_of_speech;
            $group1 = $user->group1;
            $anchor = $user->anchor;
            $kanji = $user->kanji;
            $idQuestion = $user->id;
            $newQuestionFlag = $user ->new_question;
            $anchor = $user->anchor;
            if ($anchor == "R")
            {
                array_push($anchorId,$idQuestion);
            }
            else if ($newQuestionFlag == 1)
            {
                array_push($newQuestionId,$idQuestion);
            }
            else 
            {
                switch ($partOfSpeechType) {
                    case "010":
                        array_push($nounId, $idQuestion); // 1 noun 3 question
                        break;
                    case "040":
                        array_push($verbId, $idQuestion); // 4 adjective 1 question
                        break;
                    case "050":
                        array_push($iAdjectiveId, $idQuestion); // 5 I verb 1 question
                        break;
                    case "060":
                        array_push($naAdjectiveId, $idQuestion); // 6 Na verb 1 question
                        break;
                    case "100":
                        array_push($affixId, $idQuestion); //9 Affix 1 question
                        break;
                    // case "10":
                    //     array_push($numberId, $idQuestion); //10 Number 1 question
                    //     break;
    
                    default:
                        break;
                }
                switch ($group1) {
    
    
                    case "101":
                        array_push($type101Id, $idQuestion);
                        break;
    
                    case "104":
                        array_push($type104Id, $idQuestion);
                        break;
    
                    case "102":    
                    case "105":
                        array_push($type105Id, $idQuestion);
                        break;
    
                    case "301":
                        array_push($type301Id, $idQuestion);
                        break;
    
                    case "302":
                        array_push($type302Id, $idQuestion);
                        break;
    
                    case "303":
                        array_push($type303Id, $idQuestion);
                        break;
    
                    default:
                        break;
                }
            }

        }
        $counter = 0;
        $questionIdArray = [];
        while ($counter == 0) {
            $questionIdArray = static::getRandomQuestionId(
                $nounId,
                $verbId,
                $iAdjectiveId,
                $naAdjectiveId,
                $affixId,
                $anchorId,
                $type101Id,
                // $type102Id,
                $type104Id,
                $type105Id,
                $type301Id,
                $type302Id,
                $type303Id,
                $newQuestionId,
                $valueArray
            );

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
            $elements->setQuestionId($key + 1);
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

    public function paginate($items, $perPage = 7, $page = null, $options = [])
    {
        // $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        // // $items = $items instanceof Collection ? $items : Collection::make($items);
        // if ($page == 2)
        // {
        //     $secondPage = array_slice($items,5,7);
        //     $items = $secondPage instanceof Collection ? $secondPage : Collection::make($secondPage);
        //     return new LengthAwarePaginator($items, 12, 7, $page, $options);
        // }
        // $items = $items instanceof Collection ? $items : Collection::make($items);

        // return new LengthAwarePaginator($items->forPage($page, 5),10, 5, $page, $options);
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

    function getRandomQuestionId($nounId, $verbId, $iAdjectiveId, $naAdjectiveId, $affixId, $anchorId,  $type101Id,    $type104Id,   $type105Id,    $type301Id,    $type302Id,    $type303Id,$newQuestionId,$valueArray)
    {
        // dd($newQuestionId);
        // dd($nounId, $verbId, $iAdjectiveId, $naAdjectiveId, $affixId, $anchorId,  $type101Id,    $type104Id,     $type105Id,    $type301Id,    $type302Id,    $type303Id,$newQuestionId);
        $counterLoop = 0;
        $result = [];
        while ($counterLoop == 0)
        {
        $valueAnchorPartOfSpeech = "0";
        $valueAnchorGroup = "0";
        $valueNewQuestionPartOfSpeech = "0";
        $valueNewQuestionGroup = "0";
        $anchorId1 = 0;
        $newQuestionId1 = 0;
        
        if (!empty($anchorId))
        {
            $arrayAnchorId = array_rand($anchorId, 1);
            $anchorId1 = $anchorId[$arrayAnchorId];
            // dd($anchorId1);

            foreach ($valueArray as $val) {
                if ($val->getId() === $anchorId1) {
                    $valueAnchorPartOfSpeech = $val->getPartOfSpeech();           
                    $valueAnchorGroup = $val->getGroup();
                    if ($valueAnchorGroup == "102" || $valueAnchorGroup == "105" ){
                        $valueAnchorGroup = "102105";
                    }
                }
            }
        }

        if (!empty($newQuestionId))
        {
            $arrayNewQuestionId = array_rand($newQuestionId, 1);
            $newQuestionId1 = $newQuestionId[$arrayNewQuestionId];
            foreach ($valueArray as $val) {
                if ($val->getId() === $newQuestionId1) {
                    $valueNewQuestionPartOfSpeech = $val->getPartOfSpeech();           
                    $valueNewQuestionGroup = $val->getGroup();
                    if ($valueNewQuestionGroup == "102" || $valueNewQuestionGroup == "105" ){
                        $valueNewQuestionGroup = "102105";
                    }

                }
            }
        }
        // $tempArr = ["101","102","104","105"];
        // $temVal = $tempArr[array_rand($tempArr)];
        $partOfSpeechArray = ["010","010","010","040","060","100","050"];
        $groupArray = ["101","102105","104","301","301","302","303"];
        $partOfSpeechArray = static::removeElementFromArray($valueAnchorPartOfSpeech,$partOfSpeechArray);
        if (array_search($valueNewQuestionPartOfSpeech, $partOfSpeechArray) == true || $valueNewQuestionPartOfSpeech == 0 )
        {   
            if ($valueNewQuestionPartOfSpeech != 0)
                $partOfSpeechArray = static::removeElementFromArray($valueNewQuestionPartOfSpeech,$partOfSpeechArray);

            $groupArray = static::removeElementFromArray($valueAnchorGroup,$groupArray);

            if (array_search($valueNewQuestionGroup, $groupArray) == true)
            {
                $groupArray = static::removeElementFromArray($valueNewQuestionGroup,$groupArray);
                $result1 = [];
                shuffle($groupArray);
                shuffle($partOfSpeechArray);
                $pairArray = [];
                $lengthSearch = count($partOfSpeechArray);
                for ($x = 0; $x < $lengthSearch; $x++) {
                    $element1 = array_pop($partOfSpeechArray)   ;
                    $element2 = array_pop($groupArray)   ;
                    array_push($pairArray,[$element1,$element2]);
                }
                $lengthPairArray = count($pairArray);
                for ($x = 0; $x < $lengthPairArray; $x++) {
                    $groupId = $pairArray[$x][1];
                    $partOfSpeechId = $pairArray[$x][0];
                    $groupArrayChoice = [];
                    $partOfSpeechChoice = [];
                    switch ($groupId) {
                        case "101":
                            $groupArrayChoice = $type101Id;
                            break;
        
                        case "104":
                            $groupArrayChoice = $type104Id;
                            break;
        
                        case "102105":
                            $groupArrayChoice = $type105Id;
                            break;
        
                        case "301":
                            $groupArrayChoice = $type301Id;
                            break;
        
                        case "302":
                            $groupArrayChoice = $type302Id;
                            break;
        
                        case "303":
                            $groupArrayChoice = $type303Id;
                            break;
                    }

                    switch ($partOfSpeechId){
                        case "010":
                            $partOfSpeechChoice = $nounId;
                            break;
        
                        case "040":
                            $partOfSpeechChoice = $verbId;
                            break;
        
                        case "050":
                            $partOfSpeechChoice = $iAdjectiveId;
                            break;
        
                        case "060":
                            $partOfSpeechChoice = $naAdjectiveId;
                            break;
        
                        case "100":
                            $partOfSpeechChoice = $affixId;
                            break;
                    }
                    $arrayVal = array_intersect($groupArrayChoice, $partOfSpeechChoice);
                    if (!empty($arrayVal))
                    {
                        $val = $arrayVal[array_rand($arrayVal)];
                        array_push($result1,$val);
                    }
                    else {
                        // dd($groupArrayChoice,$partOfSpeechChoice);
                    }

                } 
                array_push($result1,$anchorId1);
                if ($newQuestionId1 != 0)
                    array_push($result1,$newQuestionId1);
                if (count($result1) == 7)
                {
                    $result = $result1;
                    $counterLoop = 1;
                }

            }

        }
        }
        return $result;
    }
}