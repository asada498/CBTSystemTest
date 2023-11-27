<?php

namespace App\Http\Controllers\Q4\Vocabulary;

use App\Http\Controllers\Controller;
use  App\QuestionClass\Q4\Vocabulary\Q4S1Q5;
use App\QuestionDatabase\Q4\Vocabulary\Q4Section1Question5;
use App\AnswerRecord;

use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;
use App\ExamineeLogin;
use App\Grades;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

use Illuminate\support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class Q4S1Q5Controller extends Controller
{


    public function showQuestion(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question5Id = $currentId.".Q4S1Q5";
        // if (!(Session::has($section1Question5Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question5Id, $questionData);
        // }
        $questionDataLoad = Session::get($section1Question5Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q4\Vocabulary\pageQ4S1Q5', compact('data')); //

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
        $section1Question4Id = $userID.".Q4S1Q5";

        $questionDataLoad = Session::get($section1Question4Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q4S1Q5_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = 'F';
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
            AnswerRecord::insert(
                [
                    'examinee_number' => substr($userID, 1),
                    'level' => 4,
                    'section' => 1,
                    'question' => 5,
                    'number' => $questionId,
                    'question_type' => $codeQuestion,
                    'question_table_name' => 'q_41_05',
                    'question_id' => $question->getDatabaseQuestionId(),
                    'anchor' => 0,
                    'choice' => $userAnswer,
                    'correct_answer' => $question->getCorrectChoice(),
                    'pass_fail' => $correctFlag,
                ]
            );
        }
        //update record on database
        Q4Section1Question5::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q4Section1Question5::raw("past_testee_number + 1"),
            "correct_testee_number" => Q4Section1Question5::raw("correct_testee_number + 1")
        ]);
        Q4Section1Question5::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q4Section1Question5::raw("past_testee_number + 1")
        ]);
        $s1Q5Rate = round($scoring * 100 / 4);

        $s1Q1Correct = Session::get($userID.".Q4S1Q1Score");
        $s1Q2Correct = Session::get($userID.".Q4S1Q2Score");
        $s1Q3Correct = Session::get($userID.".Q4S1Q3Score");
        $s1Q4Correct = Session::get($userID.".Q4S1Q4Score");
        $s1Q5Correct = $scoring;
        $section1Total = $s1Q1Correct / 7 * 5.25 / 80 * 120 +
                         $s1Q2Correct / 5 * 3.75 / 80 * 120 +
                         $s1Q3Correct / 8 * 6 / 80 * 120 +
                         $s1Q4Correct / 4 * 5 / 80 * 120 +
                         $s1Q5Correct / 4 * 5 / 80 * 120;
        $s1Rate = ($s1Q1Correct+$s1Q2Correct+$s1Q3Correct+$s1Q4Correct+$s1Q5Correct)/(7+5+8+4+4);
        // dd($s1Q1Correct,$s1Q2Correct,$s1Q3Correct,$s1Q4Correct,$s1Q5Correct,$section1Total);
        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 4)->update([
            's1_q5_correct' => $scoring,
            's1_q5_question' => 4,
            's1_q5_perfect_score' => 5 / 80 * 120,
            's1_end_flag' => 1,
            's1_q5_rate' => $s1Q5Rate,
            's1_rate'=>$s1Rate,
            's1_score' => $section1Total
        ]);

        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q4S1Q5'))
            {
                if ($key !== 'Q4S1Q5Score' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }

        $anchorScoreQ4S1Q1 =  Session::get( $userID.'.Q4S1Q1Score_anchor');
        $anchorScoreQ4S1Q3 =  Session::get( $userID.'.Q4S1Q3Score_anchor');
        $currentAnchorScore = $anchorScoreQ4S1Q1+$anchorScoreQ4S1Q3;
        $currentAnchorPassRate = round($currentAnchorScore /
                                       (5.25 / 80 * 120 / 7 +
                                        6    / 80 * 120 / 8 +
                                        13.5 / 80 * 120 / 13 * 2 +
                                        10.5 / 35 * 60 / 8 +
                                        10.5 / 35 * 60 / 7 +
                                        7.5  / 35 * 60 / 8)
                                        * 100);
        Grades::where('examinee_number', substr($userID, 1))->where('level', 4)->update([
            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec1_score' => $section1Total
        ]);
        
        $scoreQ4S1Q5 = $scoring;

        Session::put($userID.'.Q4S1Q5Score', $scoreQ4S1Q5);
        // error_log($scoreQ4S1Q4);
        // Session::put('idTester',$userID);    
        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 2]);


        return Redirect::to(url('/Q4ReadingWelcome'));
        // return Redirect::to(url('/Q4VocabularyQ5'));

    }

    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q4S1Q5_".$questionNumber;

        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section1Question4Choice = $currentId.".Q4S1Q5_".$questionNumber;

        $sess = Session::get($section1Question4Choice);

        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    public function endVocabularyQ4 (){

        return view('endVocabularyQ4');
    }

    function showDataBase()
    {
        $valueArray = [];

        $nounId = [];
        $iAdjectiveId = [];
        $verbId = [];
        $naOrAdverbId = [];
        $newQuestionId = [];

        $results = Q4Section1Question5::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->get();
        foreach ($results as $user) {
            $value = new Q4S1Q5( //q4s4
                $user->id,
                $user->question_id,
                $user->vocabulary,
                $user->class_vocabulary,
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
            $newQuestionFlag = $user->new_question;
            $partOfSpeechType = $user->class_vocabulary;
            $idQuestion = $user->id;


            if ($newQuestionFlag == 1)
            {
                array_push($newQuestionId,$idQuestion);
            }
            else 
            switch ($partOfSpeechType) {
                case "010":
                    array_push($nounId, $idQuestion); // 010 noun 2 question
                    break;
                case "040":
                    array_push($verbId, $idQuestion); // 040verb 1 questio
                    break;
                case "050":
                case "030":
                case "090":
                case "100":
                case "990":
                    array_push($iAdjectiveId, $idQuestion); // 050 い形容詞  or  060 な形容詞/ i-modify、na-modify： 1　　
                    break;
                case "060":
                case "080":
                    array_push($naOrAdverbId, $idQuestion); // 050 い形容詞  or  060 な形容詞/ i-modify、na-modify： 1　　
                    break;
                default:
                    break;
            }

        }

        $counter = 0;
        $questionIdArray = [];
        while ($counter == 0) {
            $questionIdArray = static::getRandomQuestionId(
                $nounId,
                $verbId,
                $iAdjectiveId,
                $naOrAdverbId,
                $newQuestionId,
                $valueArray
            );
            shuffle($questionIdArray);

            $answerArray = [];

            foreach ($questionIdArray as $idValueInArray) {
               
                $idValue = static::searchForId($idValueInArray, $valueArray);

                array_push($answerArray, $valueArray[$idValue]->getvocabularyAnswer());
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
            $elements->setQuestionId($key + 25);
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


    function removeElementFromArray($del_val,$array){
        // dd($del_val,$array);
        if (($key = array_search($del_val, $array)) !== false) {
            unset($array[$key]);
            return $array;
        }
    }

    function  getRandomQuestionId($nounId,$verbId,$iAdjectiveId,$naOrAdverbId,$newQuestionId,$valueArray)
    {
        $counterLoop = 0;
        $result = [];
        while ($counterLoop == 0) {
            $valueNewQuestionPartOfSpeech = "0";
            $newQuestionId1 = 0;
            if (!empty($newQuestionId)) {
                $arrayNewQuestionId = array_rand($newQuestionId, 1);
                $newQuestionId1 = $newQuestionId[$arrayNewQuestionId];
                foreach ($valueArray as $val) {
                    if ($val->getId() === $newQuestionId1) {
                        $valueNewQuestionPartOfSpeech = $val->getPartOfSpeech();
                    }

                    if ($valueNewQuestionPartOfSpeech == "050" || $valueNewQuestionPartOfSpeech == "030" ||$valueNewQuestionPartOfSpeech == "090" || $valueNewQuestionPartOfSpeech == "100"
                    || $valueNewQuestionPartOfSpeech == "990" ){
                        $valueNewQuestionPartOfSpeech = "050030090100990";
                    }
                    else if ($valueNewQuestionPartOfSpeech == "060" || $valueNewQuestionPartOfSpeech == "080" ){
                        $valueNewQuestionPartOfSpeech = "060080";
                    }
                }
            }

            $partOfSpeechArray = ["010", "040", "050030090100990", "060080"];
            $partOfSpeechArray = static::removeElementFromArray($valueNewQuestionPartOfSpeech, $partOfSpeechArray);
            if (!empty($partOfSpeechArray))
            {
                $result1 = [];
                shuffle($partOfSpeechArray);
                for ($x = 0; $x <= 2; $x++) {
                    $element1 = $partOfSpeechArray[$x];
                    switch ($element1) {
                        case "010":
                            $val = $nounId[array_rand($nounId)];
                            array_push($result1, $val);
                            break;
                        case "040":
                            $val = $verbId[array_rand($verbId)];
                            array_push($result1, $val);
                            break;
                        case "050030090100990":
                            $val = $iAdjectiveId[array_rand($iAdjectiveId)];
                            array_push($result1, $val);
                            break;
                        case "060080":
                            $val = $naOrAdverbId[array_rand($naOrAdverbId)];
                            array_push($result1, $val);
                            break;
                        default:
                            break;
                    }
                }
                array_push($result1, $newQuestionId1);
                $result = $result1;
                $counterLoop = 1;
            }
        }
        return $result;
    }
}
