<?php

namespace App\Http\Controllers\Q5\Reading;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question1;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q5\Reading\Q5S2Q1;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Session;
use Illuminate\Support\Facades\Config;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;
use Illuminate\Support\Facades\File;
use DateTime;

class Q5S2Q1Controller extends Controller
{
    public function showQuestion()
    {
        if (!(Session::has('idTester'))) {
            $userIDTemp = "123test";
            Session::put('idTester', $userIDTemp);
        }
        $currentId = Session::get('idTester');
        $section2Question1Id = $currentId.".Q5S2Q1";

        $dt = new DateTime();
        $result = $dt->format('Y-m-d');
        $userIDNum = substr($currentId, 1);
        $folderPath = base_path().'/testerLog'.'/'.$result.'/'.$userIDNum.'.txt';
        file_put_contents($folderPath, "Q5S2Q1 question search \n", FILE_APPEND);

        // if (!(Session::has($section2Question1Id))) {
            $questionData = $this->showDataBase();
            Session::put($section2Question1Id, $questionData);
        // }

        file_put_contents($folderPath, "User ID no ".$userIDNum." start the 5QS2Q1. \n", FILE_APPEND);

        $questionDataLoad = Session::get($section2Question1Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q5\Reading\paginationQ5S2Q1', compact('data'));
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
        $section2Question1Id = $userID . ".Q5S2Q1";

        $questionDataLoad = Session::get($section2Question1Id);
        Session::put($userID.".Q5S2Q1Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID . '.Q5S2Q1_' . $questionId;
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
                    Session::put($userID.".Q5S2Q1Score_anchor", 8 / 60 * 120 / 9);
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
                    'section' => 2,
                    'question' => 1,
                    'number' => $questionId,
                    'question_type' => $codeQuestion,
                    'question_table_name' => 'q_52_01',
                    'question_id' => $question->getDatabaseQuestionId(),
                    'anchor' => $anchorFlag,
                    'choice' => $userAnswer,
                    'correct_answer' => $question->getCorrectChoice(),
                    'pass_fail' => $correctFlag,
                ]
            );
        }
        //update record on database
        Q5Section2Question1::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section2Question1::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section2Question1::raw("correct_testee_number + 1")
        ]);
        Q5Section2Question1::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section2Question1::raw("past_testee_number + 1")
        ]);

        $s2Q1Rate = round($scoring * 100 / 9);
        Session::put($userID.".Q5S2Q1Score_anchor", 8 / 60 * 120 / 9 * $anchorPassFlag);

        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 5)->update([
            's2_q1_correct' => $scoring,
            's2_q1_question' => 9,
            's2_q1_perfect_score' => 16,
            's2_q1_anchor_pass' => $anchorPassFlag,
            's2_q1_rate' => $s2Q1Rate
        ]);

        // $request->session()->flush();
        foreach (Session::get($userID) as $key => $obj) {
            if (str_starts_with($key, 'Q5S2Q1')) {
                if ($key !== 'Q5S2Q1Score' && $key !== 'Q5S2Q1Score_anchor') {
                    $afterSubmitSession = $userID . '.' . $key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ5S2Q1 = $scoring;
        Session::put($userID . '.Q5S2Q1Score', $scoreQ5S2Q1);

        return Redirect::to(url('/Q5ReadingQ2'));
    }
    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section2Question1Id = $currentId . ".Q5S2Q1";
        if ($request->ajax()) {
            $questionDataLoad = Session::get($section2Question1Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q5\Reading\paginationDataQ5S2Q1', compact('data'))->render();
        }
    }
    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId . ".Q5S2Q1_" . $questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section2Question1Choice = $currentId . ".Q5S2Q1_" . $questionNumber;

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
        $extraId = [];
        $anchorId = [];
        $newQuestionId = [];

        $results = Q5Section2Question1::where('usable', 1)->whereBetween('correct_answer_rate', [0.2, 0.8])->orWhere('new_question', 1)->get();
        foreach ($results as $user) {
            $value = new Q5S2Q1(
                $user->id,
                $user->question_id,
                $user->sentence_pattern,
                $user->sentence_pattern_classification,
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
            array_push($valueArray, $value);
            $partOfSpeechType = $user->sentence_pattern_classification;
            $idQuestion = $user->id;
            $anchor = $user->anchor;
            $newQuestionFlag = $user->new_question;
            if ($newQuestionFlag == 1) {
                array_push($newQuestionId, $idQuestion);
            }
            if ($anchor == "R") {
                array_push($anchorId, $idQuestion);
            } else switch ($partOfSpeechType) {
                case "010":
                    array_push($particalId, $idQuestion);
                    break;
                case "020":
                    array_push($conjugationConnectionId, $idQuestion);
                    break;
                case "030":
                    array_push($sentencePatternsId, $idQuestion);
                    break;
                case "040":
                case "050":
                case "051":
                    array_push($extraId, $idQuestion);
                default:
                    break;
            }
        }
        $counter = 0;
        $questionIdArray = [];
        while ($counter == 0) {
            $questionIdArray = static::getRandomQuestionId($particalId, $conjugationConnectionId, $sentencePatternsId, $extraId, $anchorId, $newQuestionId, $valueArray);
            shuffle($questionIdArray);

            $answerArray = [];
            $kanjiArray = [];
            $allTextArray = [];
            $newQuestionCounter = 0;
            foreach ($questionIdArray as $idValueInArray) {
                $idValue = static::searchForId($idValueInArray, $valueArray);
                array_push($answerArray, $valueArray[$idValue]->getSentencePattern());
                array_push($kanjiArray, $valueArray[$idValue]->getKanji());
                array_push($allTextArray,[$valueArray[$idValue]->getQuestion(),$valueArray[$idValue]->getChoiceA(),$valueArray[$idValue]->getChoiceB(),
                $valueArray[$idValue]->getChoiceC(),$valueArray[$idValue]->getChoiceD()]);
                
                if (in_array($idValueInArray, $newQuestionId))
                    $newQuestionCounter++;
            }
            // dd($allTextArray,$kanjiArray);
            if ($newQuestionCounter == 1 && !($this->hasDupe($answerArray,array_filter($kanjiArray))) )
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

    function getRandomQuestionId($particalId, $conjugationConnectionId, $sentencePatternsId, $extraId, $anchorId, $newQuestionId, $valueArray)
    {
            $valueAnchor = "0";
            $anchorId1 = 0;
            if (!empty($anchorId)) {
                $arrayAnchorId = array_rand($anchorId, 1);
                $anchorId1 = $anchorId[$arrayAnchorId];
                foreach ($valueArray as $val) {
                    if ($val->getId() === $anchorId1) {
                        $valueAnchor = $val->getPartOfSpeech();
                    }
                }
            }

            $result = [];

            $particalCounter = 4;
            $conjugationConnectionCounter = 2;
            $sentencePatternsCounter = 2;
            $extraCounter = 1;

            if ($valueAnchor == "010")
                $particalCounter  = 3;
            else if ($valueAnchor == "020")
                $conjugationConnectionCounter = 1;
            else if ($valueAnchor == "030")
                $sentencePatternsCounter = 1;
            else
                $extraCounter  = 0;

            $particalArray = [];
            $conjugationConnectionArray = [];
            $sentencePatternsArray = [];
            $extraArray = [];

            $arraypParticalId = array_rand($particalId, $particalCounter);
            for ($counter = 0; $counter < $particalCounter; $counter++) {
                $particalIdValue = $particalId[$arraypParticalId[$counter]];
                array_push($particalArray, $particalIdValue);
            }
            if ($conjugationConnectionCounter == 2) {
                $arrayConjugationConnectionId = array_rand($conjugationConnectionId, $conjugationConnectionCounter);
                for ($counter = 0; $counter < $conjugationConnectionCounter; $counter++) {
                    $conjugationConnectionIdValue = $conjugationConnectionId[$arrayConjugationConnectionId[$counter]];
                    array_push($conjugationConnectionArray, $conjugationConnectionIdValue);
                }
            } else {
                $arrayConjugationConnectionId = array_rand($conjugationConnectionId, 1);
                $conjugationConnectionValue = $conjugationConnectionId[$arrayConjugationConnectionId];
                array_push($conjugationConnectionArray, $conjugationConnectionValue);
            }

            if ($sentencePatternsCounter == 2) {
                $arraySentencePatternsIdId = array_rand($sentencePatternsId, $sentencePatternsCounter);
                for ($counter = 0; $counter < $sentencePatternsCounter; $counter++) {
                    $sentencePatternsIdValue = $sentencePatternsId[$arraySentencePatternsIdId[$counter]];
                    array_push($sentencePatternsArray, $sentencePatternsIdValue);
                }
            } else {
                $arraySentencePatternsId = array_rand($sentencePatternsId, 1);
                $sentencePatternsValue = $sentencePatternsId[$arraySentencePatternsId];
                array_push($sentencePatternsArray, $sentencePatternsValue);
            }

            if ($extraCounter != 0) {
                $arrayExtraId = array_rand($extraId, 1);
                $extraValue = $extraId[$arrayExtraId];
                array_push($extraArray, $extraValue);
            }
            $result = array_merge($particalArray, $conjugationConnectionArray, $sentencePatternsArray, $extraArray);
            if ($anchorId1 != 0)
                array_push($result, $anchorId1);

        return $result;
    }
}
