<?php

namespace App\Http\Controllers\Q5\Vocabulary;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Vocabulary\Q5Section1Question3;
use App\TestInformation;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q5\Vocabulary\Q5S1Q3;
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
use Illuminate\Support\Facades\File;
use DateTime;

class Q5S1Q3Controller extends Controller
{
    public function showQuestion()
    {
        $currentId = Session::get('idTester');
        $section1Question3Id = $currentId . ".Q5S1Q3";

        $dt = new DateTime();
        $result = $dt->format('Y-m-d');
        $userIDNum = substr($currentId, 1);
        $folderPath = base_path() . '/testerLog' . '/' . $result . '/' . $userIDNum . '.txt';
        file_put_contents($folderPath, "Q5S1Q3 question search \n", FILE_APPEND);

        // if (!(Session::has($section1Question3Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question3Id, $questionData);
        // }
        // $questionData = $this->showDataBase();
        // Session::put('Q5S1Q3',$questionData);   
        file_put_contents($folderPath, "User ID no " . $userIDNum . " start the 5QS1Q3. \n", FILE_APPEND);
        $questionDataLoad = Session::get($section1Question3Id);
        $data = $this->paginate($questionDataLoad);
        // $tempUrl =  Storage::disk('s3')->url('Q5/Vocabulary/N11251030500.jpg');
        // $expiryDate = now()->addHours(4);
        // $temporaryUrl = Storage::disk('s3')->temporaryUrl("Q5/Vocabulary/N11251030500.jpg", $expiryDate);
        return view('Q5\Vocabulary\paginationQ5S1Q3', compact('data'));
    }

    public function getResultToCalculate(Request $request)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        if (!(Session::has('idTester'))) {
            $userIDTemp = "130test";
            Session::put('idTester', $userIDTemp);
        }
        $userID = Session::get('idTester');
        $section1Question3Id = $userID . ".Q5S1Q3";

        $questionDataLoad = Session::get($section1Question3Id);
        Session::put($userID.".Q5S1Q3Score_anchor", 0);

        if ($questionDataLoad != null) {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID . '.Q5S1Q3_' . $questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment', '5-1-3')->first()->code;
                $correctFlag;
                $passFail;
                if ($question->getAnchorStatus() == "R")
                    $anchorFlag = 1;
                else
                    $anchorFlag = 0;
                if ($question->getCorrectChoice() == $userAnswer)
                {
                    $correctFlag = 1;
                    if ($anchorFlag == 1)
                    {
                        $anchorFlagResult = 1;
                        Session::put($userID.".Q5S1Q3Score_anchor", 1.6666667);
                    }
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
                if (AnswerRecord::where('examinee_number', $userID)->where('level', 5)->where('section', 1)->where('question', 3)->where('number', $questionId)->exists()) {
                    AnswerRecord::where('examinee_number', $userID)->where('level', 5)->where('section', 1)->where('question', 3)->where('number', $questionId)->update(
                        [
                            'question_type' => $codeQuestion,
                            'question_table_name' => 'q_51_03',
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
                            'question' => 3,
                            'number' => $questionId,
                            'question_type' => $codeQuestion,
                            'question_table_name' => 'q_51_03',
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

            Q5Section1Question3::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q5Section1Question3::raw("past_testee_number + 1"),
                "correct_testee_number" => Q5Section1Question3::raw("correct_testee_number + 1")
            ]);
            Q5Section1Question3::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q5Section1Question3::raw("past_testee_number + 1")
            ]);
        }

        $s1Q3Rate = round($scoring * 100 / 6);

        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's1_q3_correct' => $scoring,
            's1_q3_question' => 6,
            's1_q3_perfect_score' => 10,
            's1_q3_anchor_pass' => $anchorFlagResult,
            's1_q3_rate' => $s1Q3Rate
        ]);

        foreach (Session::get($userID) as $key => $obj) {
            if (str_starts_with($key, 'Q5S1Q3')) {
                if ($key !== 'Q5S1Q3Score'&& $key !== 'Q5S1Q3Score_anchor' ) {
                    $afterSubmitSession = $userID . '.' . $key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ5S1Q3 = $scoring;
        Session::put($userID . '.Q5S1Q3Score', $scoreQ5S1Q3);
        error_log($scoreQ5S1Q3);
        return Redirect::to(url('/Q5VocabularyQ4'));
    }

    function fetchData(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question3Id = $currentId . ".Q5S1Q3";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section1Question3Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q5\Vocabulary\paginationDataQ5S1Q3', compact('data'))->render(); //
        }
    }
    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId . ".Q5S1Q3_" . $questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section1Question3Choice = $currentId . ".Q5S1Q3_" . $questionNumber;

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
        $newQuestionId = [];

        $imageQuestionId = [];
        //Save index of picture question
        $indexImageValue = -1;

        $results = Q5Section1Question3::where('usable', 1)->whereBetween('correct_answer_rate', [0.2, 0.8])->orWhere('new_question', 1)->get();;
        foreach ($results as $user) {
            $value = new Q5S1Q3(
                $user->id,
                $user->question_id,
                $user->vocabulary,
                $user->part_of_speech,
                $user->past_testee_number,
                $user->correct_testee_number,
                $user->illustration,
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
            $group = $user->group1;

            $idQuestion = $user->id;
            $image = $user->illustration;
            $anchor = $user->anchor;
            $newQuestionFlag = $user->new_question;

            if ($image != "")
                array_push($imageQuestionId, $idQuestion);
            // switch ($group){
            //     case "D":
            //         array_push($dailyLifeId,$idQuestion);
            //         break;
            //     case "S":
            //         array_push($schoolId,$idQuestion);
            //         break;
            //     case "F":
            //         array_push($friendId,$idQuestion);
            //         break;
            //     default:
            //         break;
            //     }

            if ($anchor == "R") {
                array_push($anchorId, $idQuestion);
            } else if ($newQuestionFlag == 1) {
                array_push($newQuestionId, $idQuestion);
            } else {
                switch ($partOfSpeechType) {
                    case "010":
                    case "100":
                        array_push($nounId, $idQuestion);
                        break;
                    case "020":
                        array_push($katakanaId, $idQuestion);
                        break;
                    case "030":
                    case "040":
                        array_push($verbId, $idQuestion);
                        break;
                    case "050":
                        array_push($iAdjectiveId, $idQuestion);
                        break;
                    case "060":
                        array_push($naAdjectiveId, $idQuestion);
                        break;
                    case "080":
                        array_push($adverbId, $idQuestion);
                        break;
                    default:
                        break;
                }
            }
        }
        // dd($anchorId);
        $counter = 0;
        $counter1 = 0;
        $questionIdArray = [];
        while ($counter == 0) {
            $questionIdArray = static::getRandomQuestionId($nounId, $katakanaId, $verbId, $iAdjectiveId, $naAdjectiveId, $adverbId, $anchorId, $newQuestionId, $valueArray);
            $counter1++;
            shuffle($questionIdArray);
            $counterPicture = 0;
            $counterAnchor = 0;
            $answerArray = [];
            $counterIndex = -1;
            foreach ($questionIdArray as $idValueInArray) {
                $counterIndex++;
                if (in_array($idValueInArray, $imageQuestionId)) {
                    $indexImageValue = $counterIndex;
                    $counterPicture++;
                }
                if (in_array($idValueInArray, $anchorId))
                    $counterAnchor++;
                $idValue = static::searchForId($idValueInArray, $valueArray);
                array_push($answerArray, $valueArray[$idValue]->getVocabularyAnswer());
            }
            // dd([$counterD,$counterF,$counterS,$counterPicture]);
            if ($counterPicture == 1 && $counterAnchor == 1 && !($this->hasDupe($answerArray)))
                $counter = 1;
        }
        $questionList = [];
        $questionImageId = $questionIdArray[$indexImageValue];

        array_splice($questionIdArray, $indexImageValue, 1);
        array_push($questionIdArray, $questionImageId);

        foreach ($questionIdArray as $id) {
            $idValue = static::searchForId($id, $valueArray);
            array_push($questionList, $valueArray[$idValue]);
        }

        // $tempUrl =  Storage::disk('s3')->temporaryUrl('N11251030500.jpg', now()->addHours(5));

        foreach ($questionList as $key => $elements) {
            $valueKey = $key;
            $elements->setQuestionId($key + 13);
        }
        return $questionList;
    }

    function hasDupe($array)
    {
        if (count($array) !== count(array_unique($array)))
            error_log("DUPLICATE");
        return         count($array) !== count(array_unique($array));
    }

    public function paginate($items, $perPage = 6, $page = null, $options = [])
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
        if ($del_val == "010" || $del_val == "100") {
            $key = array_search("010100", $array);
            if ($key == false )
                return [];
            else {
                unset($array[$key]);
                return $array;
            }
        } else if ($del_val == "030" || $del_val == "040") {
            $key = array_search("030040", $array);
            if ($key == false )
                return [];
            else {
                unset($array[$key]);
                return $array;
            }
        } else if (($key = array_search($del_val, $array)) !== false) {
            unset($array[$key]);
            return $array;
        }
        else return [];
    }

    function getRandomQuestionId($nounId, $katakanaId, $verbId, $iAdjectiveId, $naAdjectiveId, $adverbId, $anchorId, $newQuestionId, $valueArray)
    {
        $counterLoop = 0;
        $result = [];
        while ($counterLoop == 0) {
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
            $valueNewQuestionPartOfSpeech = "0";
            $newQuestionId1 = 0;
            if (!empty($newQuestionId)) {
                $arrayNewQuestionId = array_rand($newQuestionId, 1);
                $newQuestionId1 = $newQuestionId[$arrayNewQuestionId];
                foreach ($valueArray as $val) {
                    if ($val->getId() === $newQuestionId1) {
                        $valueNewQuestionPartOfSpeech = $val->getPartOfSpeech();
                    }
                }
            }

            $partOfSpeechArray = ["010100", "020", "030040", "050", "060","080"];
            $partOfSpeechArray = static::removeElementFromArray($valueNewQuestionPartOfSpeech, $partOfSpeechArray);
            $partOfSpeechArray = static::removeElementFromArray($valueAnchor, $partOfSpeechArray);
            
            if (!empty($partOfSpeechArray))
            {
                $result1 = [];
                shuffle($partOfSpeechArray);
                for ($x = 0; $x <= 3; $x++) {
                    $element1 = $partOfSpeechArray[$x];
                    switch ($element1) {
                        case "010100":
                            $val = $nounId[array_rand($nounId)];
                            array_push($result1, $val);
                            break;
                        case "020":
                            $val = $katakanaId[array_rand($katakanaId)];
                            array_push($result1, $val);
                            break;
                        case "030040":
                            $val = $verbId[array_rand($verbId)];
                            array_push($result1, $val);
                            break;
                        case "050":
                            $val = $iAdjectiveId[array_rand($iAdjectiveId)];
                            array_push($result1, $val);
                            break;
                        case "060":
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
