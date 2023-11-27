<?php

namespace App\Http\Controllers\Q5\Reading;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question1;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question2;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question3;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question4;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question5;
use App\QuestionDatabase\Q5\Reading\Q5Section2Question6;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use App\Grades;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Session;
use Illuminate\Support\Facades\Config;

class Q5S2Controller extends Controller
{

    public function timeOutCalculation (){

        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');

        if (!(Session::has($userID.'.Q5S2Q1Score')))
            static::Q5S1getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q5S2Q2Score')))
            static::Q5S2getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q5S2Q3Score')))
            static::Q5S3getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q5S2Q4Score')))
            static::Q5S4getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q5S2Q5Score')))
            static::Q5S5getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q5S2Q6Score')))
            static::Q5S6getResultToCalculate($userID);
        error_log("timeOutCalculation");
        return response(200);
    }

    public function Q5S1getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorPassFlag = 0;
        

        $userID = Session::get('idTester');
        $section2Question1Id = $userID.".Q5S2Q1";

        $questionDataLoad = Session::get($section2Question1Id);
        Session::put($userID.".Q5S2Q1Score_anchor", 0);

        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q5S2Q1_' . $questionId;
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
        }

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
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q5S2Q1'))
            {
                if ($key !== 'Q5S2Q1Score' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ5S2Q1 = $scoring;
        Session::put($userID.'.Q5S2Q1Score', $scoreQ5S2Q1);

        error_log($scoreQ5S2Q1);
    }

    public function Q5S2getResultToCalculate ($userID){

        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;

        $section2Question2Id = $userID.".Q5S2Q2";
        $questionDataLoad = Session::get($section2Question2Id);
        if ($questionDataLoad != null)
        {
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S2Q2_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-2')->first()->code;
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
            
            //TESTING CODE ONLY. DELETE LATER.
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',2)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',2)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_02',
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
                     'question' => 2,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_02',
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
        
        Q5Section2Question2::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section2Question2::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section2Question2::raw("correct_testee_number + 1")
        ]);
        Q5Section2Question2::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section2Question2::raw("past_testee_number + 1")
        ]);
        }

        $s2Q2Rate = round($scoring * 100 / 4);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q2_correct' => $scoring,
            's2_q2_question' => 4,
            's2_q2_perfect_score' => 11,
            's2_q2_anchor_pass' => $anchorFlag,
            's2_q2_rate' => $s2Q2Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q5S2Q2'))
                {
                    if ($key !== 'Q5S2Q2Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ5S2Q2 = $scoring;
        Session::put($userID.'.Q5S2Q2Score', $scoreQ5S2Q2);

        error_log($scoreQ5S2Q2);
        
    }

    public function Q5S3getResultToCalculate ($userID){
        
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;

        $section2Question3Id = $userID.".Q5S2Q3";
        $questionDataLoad = Session::get($section2Question3Id);

        if ($questionDataLoad != null)
        {
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
        }

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

        error_log($scoreQ5S2Q3);

    }

    public function Q5S4getResultToCalculate ($userID){

        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        
        $section2Question4Id = $userID.".Q5S2Q4";
        $questionDataLoad = Session::get($section2Question4Id);

        if ($questionDataLoad != null)
        {

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S2Q4_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-4')->first()->code;
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
            
            //TESTING CODE ONLY. DELETE LATER.
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',4)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',4)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_04',
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
                     'question' => 4,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_04',
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
        
        Q5Section2Question4::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section2Question4::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section2Question4::raw("correct_testee_number + 1")
        ]);
        Q5Section2Question4::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section2Question4::raw("past_testee_number + 1")
        ]);
        }

        $s2Q4Rate = round($scoring * 100 / 2);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q4_correct' => $scoring,
            's2_q4_question' => 2,
            's2_q4_perfect_score' => 12,
            's2_q4_anchor_pass' => $anchorFlag,
            's2_q4_rate' => $s2Q4Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q5S2Q4'))
                {
                    if ($key !== 'Q5S2Q4Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ5S2Q4 = $scoring;
        Session::put($userID.'.Q5S2Q4Score', $scoreQ5S2Q4);

        error_log($scoreQ5S2Q4);
        
    }

    public function Q5S5getResultToCalculate ($userID){

        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;

        $section2Question5Id = $userID.".Q5S2Q5";
        $questionDataLoad = Session::get($section2Question5Id);

        if ($questionDataLoad != null)
        {
        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S2Q5_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-5')->first()->code;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',5)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',5)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_05',
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
                     'question' => 5,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_05',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }
        }

        Q5Section2Question5::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section2Question5::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section2Question5::raw("correct_testee_number + 1")
        ]);
        Q5Section2Question5::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section2Question5::raw("past_testee_number + 1")
        ]);
        }

        $s2Q5Rate = round($scoring * 100 / 2);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q5_correct' => $scoring,
            's2_q5_question' => 2,
            's2_q5_perfect_score' => 16,
            's2_q5_anchor_pass' => $anchorFlag,
            's2_q5_rate' => $s2Q5Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q5S2Q5'))
                {
                    if ($key !== 'Q5S2Q5Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ5S2Q5 = $scoring;
        Session::put($userID.'.Q5S2Q5Score', $scoreQ5S2Q5);

        error_log($scoreQ5S2Q5);

    }

    public function Q5S6getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;

        $section2Question6Id = $userID.".Q5S2Q6";
        $question = Session::get($section2Question6Id);
        // dd($questionDataLoad);
        if ($question != null)
        {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q5S2Q6_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment','5-2-6')->first()->code;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',6)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',5)->where('section',2)->where('question',6)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_06',
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
                     'question' => 6,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_52_06',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }

        Q5Section2Question6::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section2Question6::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section2Question6::raw("correct_testee_number + 1")
        ]);
        Q5Section2Question6::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section2Question6::raw("past_testee_number + 1")
        ]);
        }

        $s2Q6Rate = round($scoring * 100 / 1);
        $s2Q1Correct = Session::get($userID.".Q5S2Q1Score");
        $s2Q2Correct = Session::get($userID.".Q5S2Q2Score");
        $s2Q3Correct = Session::get($userID.".Q5S2Q3Score");
        $s2Q4Correct = Session::get($userID.".Q5S2Q4Score");
        $s2Q5Correct = Session::get($userID.".Q5S2Q5Score");
        $s2Q6Correct = $scoring;
        $section2Total = $s2Q1Correct  /9*16 + $s2Q2Correct  /4*11 + $s2Q3Correct /4*14 + $s2Q4Correct /2*12 + $s2Q5Correct /2*16+ $s2Q6Correct /1*11;
        $s2Rate = ($s2Q1Correct+ $s2Q2Correct+ $s2Q3Correct+ $s2Q4Correct+ $s2Q5Correct + $s2Q6Correct)/(9+4+4+2+2+1);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q6_correct' => $scoring,
            's2_q6_question' => 1,
            's2_q6_perfect_score' => 11,
            's2_q6_anchor_pass' => $anchorFlag,
            's2_q6_rate' => $s2Q6Rate,
            's2_end_flag' => 1,
            's2_rate'=>$s2Rate,
            's2_score' => $section2Total
        ]);

        $anchorScoreQ5S1Q1 =  Session::get( $userID.'.Q5S1Q1Score_anchor');
        $anchorScoreQ5S1Q3 =  Session::get( $userID.'.Q5S1Q3Score_anchor');
        $anchorScoreQ5S2Q1 =  Session::get( $userID.'.Q5S2Q1Score_anchor');
        $currentAnchorScore = $anchorScoreQ5S1Q1+$anchorScoreQ5S1Q3+$anchorScoreQ5S2Q1;
        $currentAnchorPassRate = round($currentAnchorScore/ 14.365079365*100);
        Grades::where('examinee_number', substr($userID, 1))->where('level', 5)->update([
            'anchor_soten' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec2_soten' => $section2Total
            ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q5S2Q6'))
                {
                    if ($key !== 'Q5S2Q6Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ5S2Q6 = $scoring;
        // Session::put('Q5S1Q1Score',$scoreQ5S1Q1);    
        Session::put($userID.'.Q5S2Q6Score', $scoreQ5S2Q6);
        error_log($scoreQ5S2Q6);
        
        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 3]);

    }
}