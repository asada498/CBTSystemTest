<?php

namespace App\Http\Controllers\Q5\Vocabulary;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Vocabulary\Q5Section1Question2;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q5\Vocabulary\Q5S1Q2;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use DateTime;

use Session;

class Q5S1Q2Controller extends Controller
{
    public function showQuestion()
    {
        $currentId = Session::get('idTester');
        $section1Question2Id = $currentId . ".Q5S1Q2";

        $dt = new DateTime();
        $result = $dt->format('Y-m-d');
        $userIDNum = substr($currentId, 1);
        $folderPath = base_path() . '/testerLog' . '/' . $result . '/' . $userIDNum . '.txt';
        file_put_contents($folderPath, "Q5S1Q2 question search \n", FILE_APPEND);

        // if (!(Session::has($section1Question2Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question2Id, $questionData);
        // }

        file_put_contents($folderPath, "User ID no " . $userIDNum . " start the 5QS1Q2. \n", FILE_APPEND);

        $questionDataLoad = Session::get($section1Question2Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q5\Vocabulary\paginationQ5S1Q2', compact('data'));
    }

    public function getResultToCalculate(Request $request)
    {

        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;

        if (!(Session::has('idTester'))) {
            $userIDTemp = "123test";
            Session::put('idTester', $userIDTemp);
        }
        $userID = Session::get('idTester');
        $section1Question2Id = $userID . ".Q5S1Q2";
        $questionDataLoad = Session::get($section1Question2Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID . '.Q5S1Q2_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment', '5-1-2')->first()->code;
            $correctFlag;
            $passFail;

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

            //TESTING CODE ONLY. DELETE LATER.
            if (AnswerRecord::where('examinee_number', substr($userID, 1))->where('level', 5)->where('section', 1)->where('question', 2)->where('number', $questionId)->exists()) {
                AnswerRecord::where('examinee_number', substr($userID, 1))->where('level', 5)->where('section', 1)->where('question', 2)->where('number', $questionId)->update(
                    [
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_51_02',
                        'question_id' => $question->getDatabaseQuestionId(),
                        'anchor' => $anchorFlag,
                        'choice' => $userAnswer,
                        'correct_answer' => $question->getCorrectChoice(),
                        'pass_fail' => $correctFlag,
                    ]
                );
            } else {
                AnswerRecord::insert(
                    [
                        'examinee_number' => substr($userID, 1),
                        'level' => 5,
                        'section' => 1,
                        'question' => 2,
                        'number' => $questionId,
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_51_02',
                        'question_id' => $question->getDatabaseQuestionId(),
                        'anchor' => $anchorFlag,
                        'choice' => $userAnswer,
                        'correct_answer' => $question->getCorrectChoice(),
                        'pass_fail' => $correctFlag,
                    ]
                );
            }
        }
        //update record on database

        Q5Section1Question2::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section1Question2::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section1Question2::raw("correct_testee_number + 1")
        ]);
        Q5Section1Question2::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section1Question2::raw("past_testee_number + 1")
        ]);

        $s1Q2Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's1_q2_correct' => $scoring,
            's1_q2_question' => 5,
            's1_q2_perfect_score' => 9,
            's1_q2_anchor_pass' => $anchorFlag,
            's1_q2_rate' => $s1Q2Rate
        ]);
        foreach (Session::get($userID) as $key => $obj) {
            if (str_starts_with($key, 'Q5S1Q2')) {
                if ($key !== 'Q5S1Q2Score') {
                    $afterSubmitSession = $userID . '.' . $key;

                    Session::forget($afterSubmitSession);
                }
            }
        }

        $scoreQ5S1Q2 = $scoring;
        Session::put($userID . '.Q5S1Q2Score', $scoreQ5S1Q2);
        return Redirect::to(url('/Q5VocabularyQ3'));
    }

    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question2Id = $currentId . ".Q5S1Q2";
        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question2Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q5\Vocabulary\paginationDataQ5S1Q2', compact('data'))->render();
        }
    }
    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId . ".Q5S1Q2_" . $questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section1Question2Choice = $currentId . ".Q5S1Q2_" . $questionNumber;

        $sess = Session::get($section1Question2Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    public function example()
    {

        return view('example');
    }

    function showDataBase()
    {
        $valueArray = [];
        $nounId = [];
        $katakanaId = [];
        $verbId = [];
        $iAdjectiveId = [];
        $naOrAffixOrAdverbId = [];
        $newQuestionId = [];

        $type104 = [];
        $type201 = [];
        $type203 = [];
        $type302 = [];
        $type301 = [];

        $results = Q5Section1Question2::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->get();
        foreach ($results as $user) {
            $value = new Q5S1Q2(
                $user->id,
                $user->question_id,
                $user->vocabulary,
                $user->kanji,
                $user->part_of_speech,
                $user->group1,
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
            array_push($valueArray, $value);

            $partOfSpeechType = $user->part_of_speech;
            $group1 = $user->group1;
            $idQuestion = $user->id;
            $newQuestionFlag = $user->new_question;
            if ($newQuestionFlag == 1) {
                array_push($newQuestionId, $idQuestion);
            } else {
                switch ($partOfSpeechType) {
                    case "010":
                        array_push($nounId, $idQuestion);
                        break;
                    case "020":
                        array_push($katakanaId, $idQuestion);
                        break;
                    case "040":
                        array_push($verbId, $idQuestion);
                        break;
                    case "050":
                        array_push($iAdjectiveId, $idQuestion);
                        break;
                    case "060":
                    case "080":
                    case "100":
                        array_push($naOrAffixOrAdverbId, $idQuestion);
                        break;
                    default:
                        break;
                }

                switch ($group1) {
                    case "104":
                        array_push($type104, $idQuestion);
                        break;
                    case "201":
                        array_push($type201, $idQuestion);
                        break;
                    case "203":
                        array_push($type203, $idQuestion);
                        break;
                    case "302":
                        array_push($type302, $idQuestion);
                        break;
                    case "301":
                        array_push($type301, $idQuestion);
                        break;
                    default:
                        break;
                }
            }
        }

        $counter = 0;
        $questionIdArray = [];
        while ($counter == 0) {
            $questionIdArray = static::getRandomQuestionId($nounId, $katakanaId, $verbId, $iAdjectiveId, $naOrAffixOrAdverbId, $type104, $type201, $type203, $type302, $type301, $newQuestionId, $valueArray);
            shuffle($questionIdArray);
            $answerArray = [];
            foreach ($questionIdArray as $idValueInArray) {
                // if (in_array($idValueInArray, $type104))
                //     $counterJ++;
                // if (in_array($idValueInArray, $type301))
                //     $counterE++;
                // if (in_array($idValueInArray, $type302))
                //     $counterY++;
                // if (in_array($idValueInArray, $type203))
                //     $counterC++;
                // if (in_array($idValueInArray, $type201))
                //     $counterM++;
                $idValue = static::searchForId($idValueInArray, $valueArray);
                array_push($answerArray, $valueArray[$idValue]->getKanjiAnswer());
            }
            if (!($this->hasDupe($answerArray)))
                $counter = 1;
        }
        $questionList = [];
        foreach ($questionIdArray as $id) {
            $idValue = static::searchForId($id, $valueArray);
            array_push($questionList, $valueArray[$idValue]);
        }
        foreach ($questionList as $key => $elements) {
            $valueKey = $key;
            $elements->setQuestionId($key + 8);
        }
        return $questionList;
    }

    function hasDupe($array)
    {
        return count($array) !== count(array_unique($array));
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
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

    function removeElementFromArray($del_val, $array)
    {
       if (($key = array_search($del_val, $array)) !== false) {
            unset($array[$key]);
            return $array;
        }
    }

    function getRandomQuestionId($nounId, $katakanaId, $verbId, $iAdjectiveId, $naOrAffixOrAdverbId, $type104, $type201, $type203, $type302, $type301, $newQuestionId, $valueArray)
    {
        // dd($nounId, $katakanaId, $verbId, $iAdjectiveId, $naOrAffixOrAdverbId, $type104, $type201, $type203, $type302, $type301, $newQuestionId, $valueArray);

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

                        $valueNewQuestionPartOfSpeech = $val->getPartOfSpeech();
                        $valueNewQuestionGroup = $val->getGroup1();
                        if ($valueNewQuestionPartOfSpeech == "060" ||$valueNewQuestionPartOfSpeech == "100" ||$valueNewQuestionPartOfSpeech == "080" ){
                            $valueNewQuestionPartOfSpeech = "060100080";
                        }
                        // $valueNewQuestionPartOfSpeech = "040";
                        // $valueNewQuestionGroup = "105";
                    }
                }
            }
            $partOfSpeechArray = ["010", "020", "040", "050", "060100080"];
            $groupArray = ["104", "201", "203", "302", "301"];
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
                    case "104":
                        $groupArrayChoice = $type104;
                        break;

                    case "203":
                        $groupArrayChoice = $type203;
                        break;

                    case "201":
                        $groupArrayChoice = $type201;
                        break;

                    case "302":
                        $groupArrayChoice = $type302;
                        break;

                    case "301":
                        $groupArrayChoice = $type301;
                        break;
                }

                switch ($partOfSpeechId) {
                    case "010":
                        $partOfSpeechChoice = $nounId;
                        break;

                    case "020":
                        $partOfSpeechChoice = $katakanaId;
                        break;

                    case "040":
                        $partOfSpeechChoice = $verbId;
                        break;

                    case "050":
                        $partOfSpeechChoice = $iAdjectiveId;
                        break;

                    case "060100080":
                        $partOfSpeechChoice = $naOrAffixOrAdverbId;
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
