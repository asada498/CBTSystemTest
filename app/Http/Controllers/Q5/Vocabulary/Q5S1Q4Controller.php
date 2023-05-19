<?php

namespace App\Http\Controllers\Q5\Vocabulary;

use App\Http\Controllers\Controller;
use  App\QuestionClass\Q5\Vocabulary\Q5S1Q4;
use App\QuestionDatabase\Q5\Vocabulary\Q5Section1Question4;
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
use Illuminate\Support\Facades\File;
use DateTime;

class Q5S1Q4Controller extends Controller
{


    public function showQuestion(Request $request)
    {
        $currentId = Session::get('idTester');
        $section1Question4Id = $currentId.".Q5S1Q4";

        $dt = new DateTime();
        $result = $dt->format('Y-m-d');
        $userIDNum = substr($currentId, 1);
        $folderPath = base_path().'/testerLog'.'/'.$result.'/'.$userIDNum.'.txt';
        file_put_contents($folderPath,"Q5S1Q4 question search \n",FILE_APPEND);

        // if (!(Session::has($section1Question4Id))) {
            $questionData = $this->showDataBase();
            Session::put($section1Question4Id, $questionData);
        // }

        file_put_contents($folderPath,"User ID no ".$userIDNum." start the 5QS1Q4. \n",FILE_APPEND);

        $questionDataLoad = Session::get($section1Question4Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q5\Vocabulary\pageQ5S1Q4', compact('data')); //

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
        $section1Question4Id = $userID.".Q5S1Q4";

        $questionDataLoad = Session::get($section1Question4Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S1Q4_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment', '5-1-4')->first()->code;

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
                    'level' => 5,
                    'section' => 1,
                    'question' => 4,
                    'number' => $questionId,
                    'question_type' => $codeQuestion,
                    'question_table_name' => 'q_51_04',
                    'question_id' => $question->getDatabaseQuestionId(),
                    'anchor' => 0,
                    'choice' => $userAnswer,
                    'correct_answer' => $question->getCorrectChoice(),
                    'pass_fail' => $correctFlag,
                ]
            );
        }
        //update record on database
        Q5Section1Question4::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section1Question4::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section1Question4::raw("correct_testee_number + 1")
        ]);
        Q5Section1Question4::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section1Question4::raw("past_testee_number + 1")
        ]);

        $s1Q4Rate = round($scoring * 100 / 3);
        $s1Q1Correct = Session::get($userID.".Q5S1Q1Score");
        $s1Q2Correct = Session::get($userID.".Q5S1Q2Score");
        $s1Q3Correct = Session::get($userID.".Q5S1Q3Score");
        $s1Q4Correct = $scoring;

        $section1Total = $s1Q1Correct /7*11 + $s1Q2Correct/5*9 + $s1Q3Correct /6*10 + $s1Q4Correct /3*10;
        $s1Rate = ($s1Q1Correct+$s1Q2Correct+$s1Q3Correct+$s1Q4Correct)/(7+5+6+3);

        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 5)->update([
            's1_q4_correct' => $scoring,
            's1_q4_question' => 3,
            's1_q4_perfect_score' => 10,
            's1_end_flag' => 1,
            's1_q4_rate' => $s1Q4Rate,
            's1_rate'=>$s1Rate,
            's1_score' => $section1Total
        ]);

        $anchorScoreQ5S1Q1 =  Session::get( $userID.'.Q5S1Q1Score_anchor');
        $anchorScoreQ5S1Q3 =  Session::get( $userID.'.Q5S1Q3Score_anchor');
        $currentAnchorScore = $anchorScoreQ5S1Q1+$anchorScoreQ5S1Q3;
        $currentAnchorPassRate = round($currentAnchorScore/ 12.587302*100);
        Grades::where('examinee_number', substr($userID, 1))->where('level', 5)->update([

            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec1_score' => $section1Total
        ]);
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q5S1Q4'))
            {
                if ($key !== 'Q5S1Q4Score' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ5S1Q4 = $scoring;
        Session::put($userID.'.Q5S1Q4Score', $scoreQ5S1Q4);
        // error_log($scoreQ5S1Q4);
        // Session::put('idTester',$userID);    

        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 2]);

        return Redirect::to(url('/Q5ReadingWelcome'));
    }

    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S1Q4_".$questionNumber;

        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section1Question4Choice = $currentId.".Q5S1Q4_".$questionNumber;

        $sess = Session::get($section1Question4Choice);

        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    public function endVocabularyQ5 (){

        return view('endVocabularyQ5');
    }

    function showDataBase()
    {
        $valueArray = [];

        $nounId = [];
        $iOrNaAdjectiveId = [];
        $verbId = [];
        $newQuestionId = [];

        $results = Q5Section1Question4::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->orWhere('new_question', 1)->get();
        foreach ($results as $user) {
            $value = new Q5S1Q4( //q5s4
                $user->id,
                $user->question_id, 
                $user->vocabulary,
                $user->part_of_speech,
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
            array_push($valueArray, $value);

            $partOfSpeechType = $user->part_of_speech;
            // $group = $user->group1;
            $idQuestion = $user->id;
            $newQuestionFlag = $user->new_question;


            if ($newQuestionFlag == 1) {
                array_push($newQuestionId, $idQuestion);
            } else switch ($partOfSpeechType) {
                case "010":
                case "020":
                    array_push($nounId, $idQuestion); // 010 noun 2 question
                    break;
                case "030":
                case "040":
                    array_push($verbId, $idQuestion); // 040verb 1 questio
                    break;
                case "050":
                case "060":
                    array_push($iOrNaAdjectiveId, $idQuestion); // 050 い形容詞  or  060 な形容詞/ i-modify、na-modify： 1　　
                    break;
                default:
                    break;
            }

            // switch ($group) {
            //     case "D":
            //         array_push($dTypeId, $idQuestion);
            //         break;
            //     case "S":
            //     case "F":
            //     case "K":
            //         array_push($sfkTypeId, $idQuestion);
            //         break;
            //     default:
            //         break;
            // }
        }
        $counter = 0;
        $counterNoun = 0;
        $counterVerb = 0;
        $counterAdj = 0;
        $questionIdArray = [];
        while ($counter == 0) {
            $questionIdArray = static::getRandomQuestionId(
                $nounId,
                $verbId,
                $iOrNaAdjectiveId,
                $newQuestionId,
                $valueArray
            );
            shuffle($questionIdArray);

            $answerArray = [];

            foreach ($questionIdArray as $idValueInArray) {

                $idValue = static::searchForId($idValueInArray, $valueArray);
                $partOfSpeechVal = $valueArray[$idValue]->getpartofspeech();
                switch ($partOfSpeechVal) {
                    case "010":
                    case "020":
                        $counterNoun++;
                        break;
                    case "030":
                    case "040":
                        $counterVerb++;
                        break;
                    case "050":
                    case "060":
                        $counterAdj++;
                        break;
                    default:
                        break;
                }
                array_push($answerArray, $valueArray[$idValue]->getvocabularyAnswer());
            }
            if ((!($this->hasDupe($answerArray))) && $counterNoun == 1 && $counterVerb == 1 && $counterAdj == 1)
                $counter = 1;
        }

        $questionList = [];
        foreach ($questionIdArray as $id) {

            $idValue = static::searchForId($id, $valueArray);
            array_push($questionList, $valueArray[$idValue]);
        }
        foreach ($questionList as $key => $elements) {
            $valueKey = $key;
            $elements->setQuestionId($key + 19);
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



    function getRandomQuestionId($nounId, $verbId,   $iOrNaAdjectiveId,$newQuestionId,$valueArray)
    {
        //noun 1 ,verb ,naadj/iadj,  katakana/suru/adverb/other
        $valueNewQuestion = "0";
        $newQuestionId1 = 0;
        if (!empty($newQuestionId))
        {
            $arrayAnchorId = array_rand($newQuestionId, 1);
            $newQuestionId1 = $newQuestionId[$arrayAnchorId];
            foreach ($valueArray as $val) {
                if ($val->getId() === $newQuestionId1) {
                    $valueNewQuestion = $val->getPartOfSpeech();
                }
            }
        }

        $result = [];
        $nounCounter = 1;
        $iOrNaCounter = 1;
        $verbCounter = 1;
        if ($valueNewQuestion == "010" || $valueNewQuestion == "020")
            $nounCounter = 0;
        else if ($valueNewQuestion == "030" || $valueNewQuestion == "040")
            $verbCounter = 0;
        else 
            $iOrNaCounter  = 0;


        $nounArray = [];
        $iOrNaArray = [];
        $verbArray = [];
        // $arrayNounId = array_rand($nounId, $nounCounter);
        // for ($counter = 0; $counter < $nounCounter; $counter++)
        //     {
        //         $value = $nounId[$arrayNounId[$counter]];
        //         array_push($nounArray,$value);
        //     }

        // if ($katakanaCounter == 2)
        // {
        //     $arrayKatakanaId = array_rand($katakanaId, $katakanaCounter);
        //     for ($counter = 0; $counter < $katakanaCounter; $counter++)
        //     {
        //         $value = $katakanaId[$arrayKatakanaId[$counter]];
        //         array_push($katakanaArray,$value);
        //     }
        // }
        // else {
        //     $arrayKatakanaId = array_rand($katakanaId, 1);
        //     $katakanaValue = $katakanaId[$arrayKatakanaId];
        //     array_push($katakanaArray,$katakanaValue);
        // }

        // if ($verbCounter == 2)
        // {
        //     $arrayVerbId = array_rand($verbId, $verbCounter);
        //     for ($counter = 0; $counter < $verbCounter; $counter++)
        //     {
        //         $value = $verbId[$arrayVerbId[$counter]];
        //         array_push($verbArray,$value);  
        //     }
        // }
        // else {
        //     $arrayVerbId = array_rand($verbId, 1);
        //     $verbValue = $verbId[$arrayVerbId];
        //     array_push($verbArray,$verbValue);
        // }

        if($nounCounter != 0)
        {
            $arrayNounId = array_rand($nounId, 1);
            $nounValue = $nounId[$arrayNounId];
            array_push($nounArray,$nounValue);
        }

        if($iOrNaCounter != 0)
        {
            $arrayIOrNaAdjectiveId = array_rand($iOrNaAdjectiveId, 1);
            $iOrNaAdjectiveValue = $iOrNaAdjectiveId[$arrayIOrNaAdjectiveId];
            array_push($iOrNaArray,$iOrNaAdjectiveValue);
        }

        if($verbCounter != 0)
        {
            $arrayVerbId = array_rand($verbId, 1);
            $verbValue = $verbId[$arrayVerbId];
            array_push($verbArray,$verbValue);
        }

        $result = array_merge($nounArray,$iOrNaArray,$verbArray);
        array_push($result,$newQuestionId1);
        // dd($newQuestionId1,$valueNewQuestion,$nounCounter,$iOrNaCounter,$verbCounter,$result);

        return $result;
        // $arrayNounId = array_rand($nounId, 1);
        // $nounId1 = $nounId[$arrayNounId];

        // $arrayverbId = array_rand($verbId, 1);
        // $verbId1 = $verbId[$arrayverbId];

        // $arrayiOrNaAdjectiveId = array_rand($iOrNaAdjectiveId, 1);
        // $iOrNaAdjectiveId1 = $iOrNaAdjectiveId[$arrayiOrNaAdjectiveId];

        // return [$nounId1, $verbId1, $iOrNaAdjectiveId1];
    }
}
