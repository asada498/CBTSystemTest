<?php

namespace App\Http\Controllers\Q5\Vocabulary;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Vocabulary\Q5Section1Question1;
use App\QuestionDatabase\Q5\Vocabulary\Q5Section1Question2;
use App\QuestionDatabase\Q5\Vocabulary\Q5Section1Question3;
use App\QuestionDatabase\Q5\Vocabulary\Q5Section1Question4;
use App\Grades;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use App\ExamineeLogin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Session;
use Illuminate\Support\Facades\Config;

class Q5S1Controller extends Controller
{

    public function timeOutCalculation (){

        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');

        if (!(Session::has($userID.'.Q5S1Q1Score')))
            static::Q5S1getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q5S1Q2Score')))
            static::Q5S2getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q5S1Q3Score')))
            static::Q5S3getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q5S1Q4Score')))
            static::Q5S4getResultToCalculate($userID);
        error_log("timeOutCalculation");
        return response(200);
    }

    public function Q5S1getResultToCalculate ($userID){
        $section1Question1Id = $userID.".Q5S1Q1";
        $questionDataLoad = Session::get($section1Question1Id);
        Session::put($userID.".Q5S1Q1Score_anchor", 0);

        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorPassFlag = 0;

        if ($questionDataLoad != null)
        {
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S1Q1_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-1-1')->first()->code;

            if($question->getAnchor() == "R"){
                $anchorFlag = 1;
            } else {
                $anchorFlag = 0;
            }

            if ($question->getCorrectChoice() == $userAnswer) {
                $correctFlag = 1;
                $scoring++;
                array_push($correctAnswer,$question->getId());

                if ($question->getAnchor() == 'R') {
                    $anchorPassFlag = 1;
                    Session::put($userID.".Q5S1Q1Score_anchor", 1.5714286);
                }
            } else {
                if ($question->getCorrectChoice() == null)
                    $correctFlag = null;
                else 
                    {   $correctFlag = 0;
                        array_push($incorrectAnswer,$question->getId());
                    }
            }
            AnswerRecord::insert(
                ['examinee_number' => substr($userID, 1), 
                 'level' => 5,
                 'section'=> 1,
                 'question' => 1,
                 'number'=> $questionId,
                 'question_type'=>$codeQuestion,
                 'question_table_name'=>'q_51_01',
                 'question_id'=>$question->getDatabaseQuestionId(),
                 'anchor'=>$anchorFlag,
                 'choice'=>$userAnswer,
                 'correct_answer'=>$question->getCorrectChoice(),
                 'pass_fail'=>$correctFlag,
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
        }

        $s1Q1Rate = round($scoring * 100 / 7);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q1_correct' => $scoring,
            's1_q1_question' => 7,
            's1_q1_perfect_score' => 11,
            's1_q1_rate' => $s1Q1Rate,
            's1_q1_anchor_pass' => $anchorPassFlag]);

        $scoreQ5S1Q1 = $scoring;
        Session::put($userID.'.Q5S1Q1Score', $scoreQ5S1Q1);
        error_log($scoreQ5S1Q1);
        
    }

    public function Q5S2getResultToCalculate ($userID){
        $section1Question2Id = $userID.".Q5S1Q2";
        $questionDataLoad = Session::get($section1Question2Id);
        
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $perfectScore = Config::get('constants.Q5S1Q2.perfectScore');
        $testeeScore = 0;
        $percentageScore = 0;
        if ($questionDataLoad != null)
        {
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S1Q2_'.$questionId;
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
            AnswerRecord::insert(
                ['examinee_number' => substr($userID, 1), 
                 'level' => 5,
                 'section'=> 1,
                 'question' => 2,
                 'number'=> $questionId,
                 'question_type'=>$codeQuestion,
                 'question_table_name'=>'q_51_02',
                 'question_id'=>$question->getDatabaseQuestionId(),
                 'anchor'=>$anchorFlag,
                 'choice'=>$userAnswer,
                 'correct_answer'=>$question->getCorrectChoice(),
                 'pass_fail'=>$correctFlag,
                 ]
            );
        }
        //update record on database
        
        Q5Section1Question2::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section1Question2::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section1Question2::raw("correct_testee_number + 1")
        ]);
        Q5Section1Question2::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section1Question2::raw("past_testee_number + 1")
        ]);

        
    }
        $s1Q2Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q2_correct' => $scoring,
            's1_q2_question' => 5,
            's1_q2_perfect_score' => $percentageScore,
            's1_q2_anchor_pass' => $anchorFlag,
            's1_q2_rate' => $s1Q2Rate
        ]);
        $scoreQ5S1Q2 = $scoring;
        Session::put($userID.'.Q5S1Q2Score', $scoreQ5S1Q2);
        error_log($scoreQ5S1Q2);
        
    }

    public function Q5S3getResultToCalculate ($userID){
        $section1Question3Id = $userID.".Q5S1Q3";
        $questionDataLoad = Session::get($section1Question3Id);
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = 'Q5S1Q3_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-3')->first()->code;
                $correctFlag;
                $passFail;
                if($question->getAnchorStatus() == "R")
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
                    array_push($correctAnswer,$question->getId());
                } else  {
                    if ($question->getCorrectChoice() == null)
                        $correctFlag = null;
                    else 
                        {   $correctFlag = 0;
                            array_push($incorrectAnswer,$question->getId());
                        }
                }
                AnswerRecord::insert(
                    ['examinee_number' => substr($userID, 1), 
                     'level' => 5,
                     'section'=> 1,
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_51_03',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
            //update record on database
            
            Q5Section1Question3::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q5Section1Question2::raw("past_testee_number + 1"),
                "correct_testee_number" => Q5Section1Question2::raw("correct_testee_number + 1")
            ]);
            Q5Section1Question3::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q5Section1Question2::raw("past_testee_number + 1")
            ]);
        }

        $s1Q3Rate = round($scoring * 100 / 6);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q3_correct' => $scoring,
            's1_q3_question' => 6,
            's1_q3_perfect_score' => 10,
            's1_q3_anchor_pass' => $anchorFlagResult,
            's1_q3_rate' => $s1Q3Rate
        ]);
        $scoreQ5S1Q3 = $scoring;
        Session::put($userID.'.Q5S1Q3Score', $scoreQ5S1Q3);
        error_log($scoreQ5S1Q3);
        
    }

    public function Q5S4getResultToCalculate ($userID){

        $section1Question4Id = $userID.".Q5S1Q4";
        $questionDataLoad = Session::get($section1Question4Id);
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;

        if ($questionDataLoad != null)
        {
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S1Q4_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-1-4')->first()->code;

            if ($question->getCorrectChoice() == $userAnswer)
            {
                $correctFlag = 1;
                $scoring++;
                array_push($correctAnswer,$question->getId());
            } else {
                if ($question->getCorrectChoice() == null)
                    $correctFlag = null;
                else {
                    $correctFlag = 0;
                    array_push($incorrectAnswer,$question->getId());
                }
            }
            AnswerRecord::insert(
                ['examinee_number' => substr($userID, 1), 
                 'level' => 5,
                 'section'=> 1,
                 'question' => 4,
                 'number'=> $questionId,
                 'question_type'=>$codeQuestion,
                 'question_table_name'=>'q_51_04',
                 'question_id'=>$question->getDatabaseQuestionId(),
                 'anchor'=> 0,
                 'choice'=>$userAnswer,
                 'correct_answer'=>$question->getCorrectChoice(),
                 'pass_fail'=>$correctFlag,
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
        }

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
        $currentAnchorPassRate = round($currentAnchorScore/ 14.365079365*100);
        Grades::where('examinee_number', substr($userID, 1))->where('level', 5)->update([
            'anchor_soten' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec1_soten' => $section1Total
        ]);
        
        $scoreQ5S1Q4 = $scoring;
        Session::put($userID.'.Q5S1Q4Score', $scoreQ5S1Q4);
        error_log($scoreQ5S1Q4);

        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 2]);
    }
}