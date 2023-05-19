<?php

namespace App\Http\Controllers\Q5\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question3;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q5\Reading\Q5S2Q3;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Session;
use Illuminate\Support\Facades\Config;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use DateTime;

class Q5S2Q3Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question3Id = $currentId.".Q5S2Q3";

        $dt = new DateTime();
        $result = $dt->format('Y-m-d');
        $userIDNum = substr($currentId, 1);
        $folderPath = base_path().'/testerLog'.'/'.$result.'/'.$userIDNum.'.txt';
        file_put_contents($folderPath,"Q5S2Q3 question search \n",FILE_APPEND);

        // if (!(Session::has($section2Question3Id))) {
            $questionData = $this->showDataBase();
            Session::put($section2Question3Id, $questionData);
        // }

        file_put_contents($folderPath,"User ID no ".$userIDNum." start the 5QS2Q3. \n",FILE_APPEND);

        $questionData = Session::get($section2Question3Id);
        $threeQuestionList = $questionData[0];
        $threeQuestionText = $threeQuestionList[0]->getText();
        $twoQuestionList = $questionData[1];
        $twoQuestionText = $twoQuestionList[0]->getText();

        return view('Q5\Reading\paginationQ5S2Q3', compact('threeQuestionList','threeQuestionText','twoQuestionList','twoQuestionText'));

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
        $section2Question3Id = $userID.".Q5S2Q3";
        $questionDataLoad = Session::get($section2Question3Id);
        foreach ($questionDataLoad as $questionPack) {
            foreach($questionPack as $question)
            {
            $questionId = $question->getQuestion();
            $currentQuestion = $userID.'.Q5S2Q3_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-3')->first()->code;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',3)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',3)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_03',
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
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_03',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
            }
        }

        Q5Section2Question3::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section2Question3::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section2Question3::raw("correct_testee_number + 1")
        ]);
        Q5Section2Question3::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section2Question3::raw("past_testee_number + 1")
        ]);

        $s2Q3Rate = round($scoring * 100 / 4);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q3_correct' => $scoring,
            's2_q3_question' => 4,
            's2_q3_perfect_score' => 14,
            's2_q3_anchor_pass' => $anchorFlag,
            's2_q3_rate' => $s2Q3Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q5S2Q3'))
                {
                    if ($key !== 'Q5S2Q3Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ5S2Q3 = $scoring;
        Session::put($userID.'.Q5S2Q3Score', $scoreQ5S2Q3);

        return Redirect::to(url('/Q5ReadingQ4'));

    }

   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S2Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question3Choice = $currentId.".Q5S2Q3_".$questionNumber;

        $sess = Session::get($section2Question3Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 
        $particalId = [];
        $conjugationConnectionId = [];
        $sentencePatternsId = [];
        $contextId = [];
        // $anchorId = [];

        //this is special case because this is in set of questions. do the correct answer rate below.
        $results = Q5Section2Question3::where('usable', 1)->where('not_in_use', 0)->get();
        $groupByTextNumberArray = [];
        $code010question = [];
        $code020question = [];
        $code030question = [];
        $code040050question = [];

        foreach ($results as $question) {
            $value = new Q5S2Q3($question->id,$question->question_id,$question->grammar,$question->sentence_patterns,$question->sentence_patterns_classification,$question->correct_answer_rate,$question->past_testee_number,$question->correct_testee_number,$question->text_number,
            $question->text,$question->question,$question->choice_a,$question->choice_b,$question->choice_c,$question->choice_d,$question->correct_answer,$question->new_question);
            array_push($valueArray,$value);

            $idQuestion = $question->id;
            $sentencePatternsClassificationType = $question->sentence_patterns_classification;
            $sentencePatternsType = $question->sentence_patterns;
            $textNumberGroup = $question->text_number;
            if(array_key_exists($textNumberGroup,$groupByTextNumberArray))
                array_push($groupByTextNumberArray[$textNumberGroup],$value);
            else 
                $groupByTextNumberArray[$textNumberGroup] = [$value];   
            switch ($sentencePatternsType){
                    case "010":
                        array_push($particalId,$idQuestion);
                        break;
                    case "020":
                        array_push($conjugationConnectionId,$idQuestion);
                        break;
                    case "030":
                        array_push($sentencePatternsId,$idQuestion);
                        break;
                    case "040":
                    case "050":
                        array_push($contextId,$idQuestion);
                        break;
                    default:
                        break;
            }
        }
        
        // dd($particalId,$conjugationConnectionId,$sentencePatternsId,$contextId);
        // $countGroupTwo = 0;
        // $countGroupThree = 0;
        // foreach ($groupByTextNumberArray as $groupQuestion)
        // {
        //     if (count($groupQuestion)==2)
        //     {
        //         $countGroupTwo++;
        //         $temporarySentencePatternClassificationArray = [$groupQuestion[0]->getSentencePatternClassification(),$groupQuestion[1]->getSentencePatternClassification()];

        //         if (count(array_keys($temporarySentencePatternClassificationArray,"A")) ==1 && count(array_keys($temporarySentencePatternClassificationArray,"B")) == 1)
        //             array_push($abQuestion,$groupQuestion);

        //         else if (count(array_keys($temporarySentencePatternClassificationArray,"A")) ==2)
        //             array_push($aaQuestion,$groupQuestion);

        //         else if (count(array_keys($temporarySentencePatternClassificationArray,"A")) ==1 && count(array_keys($temporarySentencePatternClassificationArray,"C")) == 1)
        //             array_push($acQuestion,$groupQuestion);

        //         else if (count(array_keys($temporarySentencePatternClassificationArray,"B")) ==1 && count(array_keys($temporarySentencePatternClassificationArray,"C")) == 1)
        //             array_push($bcQuestion,$groupQuestion);

        //         else if (count(array_keys($temporarySentencePatternClassificationArray,"C")) ==2)
        //             array_push($ccQuestion,$groupQuestion);
        //     }

        //     else if (count($groupQuestion)==3)
        //     {
        //         $countGroupThree++;
        //         $temporarySentencePatternClassificationArray = [$groupQuestion[0]->getSentencePatternClassification(),$groupQuestion[1]->getSentencePatternClassification(),
        //         $groupQuestion[2]->getSentencePatternClassification()];

        //         if (count(array_keys($temporarySentencePatternClassificationArray,"A")) ==2 && count(array_keys($temporarySentencePatternClassificationArray,"B")) == 1)
        //             array_push($aabQuestion,$groupQuestion);

        //         else if (count(array_keys($temporarySentencePatternClassificationArray,"A")) ==2 && count(array_keys($temporarySentencePatternClassificationArray,"C")) == 1)
        //             array_push($aacQuestion,$groupQuestion);

        //         else if (count(array_keys($temporarySentencePatternClassificationArray,"A")) ==1 && count(array_keys($temporarySentencePatternClassificationArray,"B")) == 1 && count(array_keys($temporarySentencePatternClassificationArray,"C")) == 1)
        //             array_push($abcQuestion,$groupQuestion);

        //         else if (count(array_keys($temporarySentencePatternClassificationArray,"B")) ==1 && count(array_keys($temporarySentencePatternClassificationArray,"C")) == 2)
        //             array_push($bccQuestion,$groupQuestion);

        //         else if (count(array_keys($temporarySentencePatternClassificationArray,"C")) ==2 && count(array_keys($temporarySentencePatternClassificationArray,"A")) == 1)
        //             array_push($accQuestion,$groupQuestion);
        //     }
        // }
        // // dd($groupByTextNumberArray);
        // // dd($aabQuestion,$aacQuestion,$abcQuestion,$bccQuestion,$accQuestion);
        // $counter = 0;
        // while ($counter == 0)
        // {
        // $choice = rand(0,4);
        // $threeQuestionGroup;
        // $twoQuestionGroup;
        // switch ($choice){
        //     case 1:
        //         if (count($aabQuestion) == 0)
        //             {
        //             $threeQuestionGroup = $aacQuestion[array_rand($aacQuestion)];
        //             $twoQuestionGroup = $bcQuestion[array_rand($bcQuestion)];
        //             }
        //         else 
        //             {
        //             $threeQuestionGroup = $aabQuestion[array_rand($aabQuestion)];
        //             $twoQuestionGroup = $ccQuestion[array_rand($ccQuestion)];
        //             }
        //     break;
        //     case 2:
        //         $threeQuestionGroup = $aacQuestion[array_rand($aacQuestion)];
        //         $twoQuestionGroup = $bcQuestion[array_rand($bcQuestion)];
        //     break;
        //     case 3:
        //         $threeQuestionGroup = $bccQuestion[array_rand($bccQuestion)];
        //         $twoQuestionGroup = $aaQuestion[array_rand($aaQuestion)];
        //     break;
        //     case 4:
        //         $threeQuestionGroup = $accQuestion[array_rand($accQuestion)];
        //         $twoQuestionGroup = $abQuestion[array_rand($abQuestion)];
        //     break;
        //     default:
        //         $threeQuestionGroup = $abcQuestion[array_rand($abcQuestion)];
        //         $twoQuestionGroup = $bcQuestion[array_rand($bcQuestion)];
            
        // }
        // $questionNumberArray = [];
        // $correctAnswerRateArray = [];
        // foreach($threeQuestionGroup as $question)
        // {
        //     array_push($questionNumberArray,$question->getQuestion());
        //     array_push($correctAnswerRateArray,$question->getCorrectAnswerRate());
        // }
        // foreach($twoQuestionGroup as $question)
        // {
        //     array_push($questionNumberArray,$question->getQuestion());
        //     array_push($correctAnswerRateArray,$question->getCorrectAnswerRate());
        // }
        // if (!$this->hasDupe($questionNumberArray)&& $this->correctAnswerRateInRage($correctAnswerRateArray))
        //     $counter = 1;
        // }
        // $threeQuestionGroupFirstId = $threeQuestionGroup[0]->getQuestion();
        // $twoQuestionGroupFirstId = $twoQuestionGroup[0]->getQuestion();
        // if ($twoQuestionGroupFirstId > $threeQuestionGroupFirstId)
        //     return [$threeQuestionGroup,$twoQuestionGroup];
        // else return [$twoQuestionGroup,$threeQuestionGroup];
        $counter = 0;
        $result = [];
        while ($counter == 0)
        {   
            $firstGroupQuestionID = array_rand($groupByTextNumberArray, 2);
            $firstGroupQuestion1 = $groupByTextNumberArray[$firstGroupQuestionID[0]];
            $firstGroupQuestion2 = $groupByTextNumberArray[$firstGroupQuestionID[1]];
            $idFirstGroup = [];
            $idSecondGroup = [];
            // dd($firstGroupQuestion1,$firstGroupQuestion2);
            $groupQuestion = [$firstGroupQuestion1,$firstGroupQuestion2];

            $abCounter = 0;
            $cCounter = 0;
            $dCounter = 0;
            $eCounter = 0;

            // dd($firstGroupQuestion2);
            $correctAnswerRateArray = [];
            foreach($firstGroupQuestion1 as $question)
                {
                    $val = $question->getSentencePatternClassification();
                    array_push($idFirstGroup,$val);
                }

            foreach($firstGroupQuestion2 as $question)
                {
                    $val = $question->getSentencePatternClassification();
                    array_push($idSecondGroup,$val);
                }
            $dupValue = array_intersect($idFirstGroup, $idSecondGroup);

            foreach ($groupQuestion as $questionGroup)
            {
                foreach($questionGroup as $question)
                    {
                        $val = $question->getSentencePattern();
                        array_push($correctAnswerRateArray,$question->getCorrectAnswerRate());

                        if ($val == "040" || $val == "050")
                        $abCounter++;
                        else if ($val == "010")
                        $cCounter++;
                        else if ($val == "020")
                        $dCounter++;
                        else if ($val == "030")
                        $eCounter++;
                    }
            }
            $groupFirstId = $groupQuestion[0][0]->getQuestion();
            $groupSecondId = $groupQuestion[1][0]->getQuestion();
            if (count($dupValue) == 0 && $abCounter == 1 && $cCounter == 1 && $dCounter == 1 && $eCounter == 1 && $groupFirstId !== $groupSecondId && $this->correctAnswerRateInRage($correctAnswerRateArray))
                $counter = 1;
        }
        $groupFirstId = $groupQuestion[0][0]->getQuestion();
        $groupSecondId = $groupQuestion[1][0]->getQuestion();
        if ($groupFirstId < $groupSecondId)
            return [$groupQuestion[0],$groupQuestion[1]];
        else return [$groupQuestion[1],$groupQuestion[0]];
    }

    function hasDupe($array) {
        return count($array) !== count(array_unique($array));
    }

    function correctAnswerRateInRage($array) {
        foreach ($array as $value) {
            if ( (0.2 > $value) || ($value > 0.8))
                return false;
        }    
        return true;
    }

    public function paginate($items, $perPage = 2, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    function getRandomQuestionId ($particalId,$conjugationConnectionId,$sentencePatternsId,$anchorId,$valueArray)
    {
        $arrayAnchorId = array_rand($anchorId, 1);
        $anchorId1 = $anchorId[$arrayAnchorId];
        $valueAnchor = $valueArray[$anchorId1-1]->getPartOfSpeech();

        $result = [];

        $particalCounter = 7;
        $conjugationConnectionCounter = 4;
        $sentencePatternsCounter = 5;
        if ($valueAnchor == "020")
            $conjugationConnectionCounter = 3;
        else if ($valueAnchor == "030")
            $sentencePatternsCounter = 4;
        else 
            $particalCounter  = 6;

        $particalArray = [];
        $conjugationConnectionArray = [];
        $sentencePatternsArray = [];

        $arraypParticalId = array_rand($particalId, $particalCounter);
        $arrayConjugationConnectionId = array_rand($conjugationConnectionId, $conjugationConnectionCounter);
        $arraySentencePatternsIdId = array_rand($sentencePatternsId, $sentencePatternsCounter);

        for ($counter = 0; $counter < $particalCounter; $counter++)
            {
                $particalIdValue = $particalId[$arraypParticalId[$counter]];
                array_push($particalArray,$particalIdValue);
            }
        for ($counter = 0; $counter < $conjugationConnectionCounter; $counter++)
            {
                $conjugationConnectionIdValue = $conjugationConnectionId[$arrayConjugationConnectionId[$counter]];
                array_push($conjugationConnectionArray,$conjugationConnectionIdValue);
            }
        for ($counter = 0; $counter < $sentencePatternsCounter; $counter++)
            {
                $sentencePatternsIdValue = $sentencePatternsId[$arraySentencePatternsIdId[$counter]];
                array_push($sentencePatternsArray,$sentencePatternsIdValue);
            }
        
            $result = array_merge($particalArray,$conjugationConnectionArray,$sentencePatternsArray);
            array_push($result,$anchorId1);
        return $result;
    
    }
}