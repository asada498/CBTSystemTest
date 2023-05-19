<?php

namespace App\Http\Controllers\Q4\Reading;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question1;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question2;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question3;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question4;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question5;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question6;
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
use App\ExamineeLogin;

class Q4S2Controller extends Controller
{

    public function timeOutCalculation (){

        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');

        if (!(Session::has($userID.'.Q4S2Q1Score')))
            static::Q4S1getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q4S2Q2Score')))
            static::Q4S2getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q4S2Q3Score')))
            static::Q4S3getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q4S2Q4Score')))
            static::Q4S4getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q4S2Q5Score')))
            static::Q4S5getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q4S2Q6Score')))
            static::Q4S6getResultToCalculate($userID);
        error_log("timeOutCalculation");
        return response(200);
    }

    public function Q4S1getResultToCalculate ($userID){
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
        $section2Question1Id = $userID.".Q4S2Q1";

        $questionDataLoad = Session::get($section2Question1Id);
        Session::put($userID.".Q4S2Q1Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q4S2Q1_' . $questionId;
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
                    $anchorPassFlag = 1;
                    Session::put($userID.".Q4S2Q1Score_anchor", 13.5 / 80 * 120 / 13);
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
                    'level' => 4,
                    'section' => 2,
                    'question' => 1,
                    'number' => $questionId,
                    'question_type' => $codeQuestion,
                    'question_table_name' => 'q_42_01',
                    'question_id' => $question->getDatabaseQuestionId(),
                    'anchor' => $anchorFlag,
                    'choice' => $userAnswer,
                    'correct_answer' => $question->getCorrectChoice(),
                    'pass_fail' => $correctFlag,
                ]
            );
        }
        //update record on database
        Q4Section2Question1::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q4Section2Question1::raw("past_testee_number + 1"),
            "correct_testee_number" => Q4Section2Question1::raw("correct_testee_number + 1")
        ]);
        Q4Section2Question1::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q4Section2Question1::raw("past_testee_number + 1")
        ]);


        $s2Q1Rate = round($scoring * 100 / 13);


        if ($anchorPassFlag == 1) {

            ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 4)->update([
                    's2_q1_correct' => $scoring,
                    's2_q1_question' => 13,
                    's2_q1_perfect_score' => 13.5 / 80 * 120,
                    's2_q1_anchor_pass' => 1,
                    's2_q1_rate' => $s2Q1Rate
                ]);
        } else {

            ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 4)->update([
                's2_q1_correct' => $scoring,
                's2_q1_question' => 13,
                's2_q1_perfect_score' => 13.5 / 80 * 120,
                's2_q1_rate' => $s2Q1Rate
                ]);
        }

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q4S2Q1'))
            {
                if ($key !== 'Q4S2Q1Score' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ4S2Q1 = $scoring;
        Session::put($userID.'.Q4S2Q1Score', $scoreQ4S2Q1);

        error_log($scoreQ4S2Q1);
    }

    public function Q4S2getResultToCalculate ($userID){

        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $s2Q2Rate = 0;
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        $section2Question2Id = $userID.".Q4S2Q2";
        $questionDataLoad = Session::get($section2Question2Id);
        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q4S2Q2_'.$questionId;
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',2)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',2)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_42_02',
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
                         'section'=> 2,
                         'question' => 2,
                         'number'=> $questionId,
                         'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_42_02',
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
            
            Q4Section2Question2::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q4Section2Question2::raw("past_testee_number + 1"),
                "correct_testee_number" => Q4Section2Question2::raw("correct_testee_number + 1")
            ]);
            Q4Section2Question2::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q4Section2Question2::raw("past_testee_number + 1")
            ]);
    
            $s2Q2Rate = round($scoring * 100 / 4);
        }
    

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q2_correct' => $scoring,
            's2_q2_question' => 4,
            's2_q2_perfect_score' => 5.5 / 80 * 120,
            's2_q2_anchor_pass' => $anchorFlag,
            's2_q2_rate' => $s2Q2Rate]
        );


        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q4S2Q2'))
                {
                    if ($key !== 'Q4S2Q2Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ4S2Q2 = $scoring;
        Session::put($userID.'.Q4S2Q2Score', $scoreQ4S2Q2);
        error_log($scoreQ4S2Q2);
        
    }

    public function Q4S3getResultToCalculate ($userID){
        
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;
        $s2Q3Rate = 0;
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        $section2Question3Id = $userID.".Q4S2Q3";
        $questionDataLoad = Session::get($section2Question3Id);

        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                // dd($questionPack);
                // foreach($questionPack as $question)
                // {
                $questionId = $question->getQuestion();
                $currentQuestion = $userID.'.Q4S2Q3_'.$questionId;
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
    
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',3)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',3)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_42_03',
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
                         'section'=> 2,
                         'question' => 3,
                         'number'=> $questionId,
                         'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_42_03',
                         'question_id'=>$question->getDatabaseQuestionId(),
                         'anchor'=>$anchorFlag,
                         'choice'=>$userAnswer,
                         'correct_answer'=>$question->getCorrectChoice(),
                         'pass_fail'=>$correctFlag,
                         ]
                    );
                }
                // }
            }
    
            Q4Section2Question3::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q4Section2Question3::raw("past_testee_number + 1"),
                "correct_testee_number" => Q4Section2Question3::raw("correct_testee_number + 1")
            ]);
            Q4Section2Question3::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q4Section2Question3::raw("past_testee_number + 1")
            ]);
    
            $s2Q3Rate = round($scoring * 100 / 4);
        }

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q3_correct' => $scoring,
            's2_q3_question' => 4,
            's2_q3_perfect_score' => 8 / 80 * 120,
            's2_q3_anchor_pass' => $anchorFlag,
            's2_q3_rate' => $s2Q3Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q4S2Q3'))
                {
                    if ($key !== 'Q4S2Q3Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ4S2Q3 = $scoring;
        Session::put($userID.'.Q4S2Q3Score', $scoreQ4S2Q3);

        error_log($scoreQ4S2Q3);

    }

    public function Q4S4getResultToCalculate ($userID){

        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $s2Q4Rate = 0;
        
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        $section2Question4Id = $userID.".Q4S2Q4";
        $questionDataLoad = Session::get($section2Question4Id);

        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q4S2Q4_'.$questionId;
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',4)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',4)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_42_04',
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
                         'section'=> 2,
                         'question' => 4,
                         'number'=> $questionId,
                         'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_42_04',
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
            
            Q4Section2Question4::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q4Section2Question4::raw("past_testee_number + 1"),
                "correct_testee_number" => Q4Section2Question4::raw("correct_testee_number + 1")
            ]);
            Q4Section2Question4::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q4Section2Question4::raw("past_testee_number + 1")
            ]);
    
            $s2Q4Rate = round($scoring * 100 / 3);
        }
    
        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q4_correct' => $scoring,
            's2_q4_question' => 3,
            's2_q4_perfect_score' => 10.5 / 80 * 120,
            's2_q4_anchor_pass' => $anchorFlag,
            's2_q4_rate' => $s2Q4Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q4S2Q4'))
                {
                    if ($key !== 'Q4S2Q4Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ4S2Q4 = $scoring;
        Session::put($userID.'.Q4S2Q4Score', $scoreQ4S2Q4);

        error_log($scoreQ4S2Q4);
        
    }

    public function Q4S5getResultToCalculate ($userID){

        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;
        $s2Q5Rate = 0;

        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        $section2Question5Id = $userID.".Q4S2Q5";
        $questionDataLoad = Session::get($section2Question5Id);

        if($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q4S2Q5_'.$questionId;
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
    
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',5)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',5)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_42_05',
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
                         'section'=> 2,
                         'question' => 5,
                         'number'=> $questionId,
                         'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_42_05',
                         'question_id'=>$question->getDatabaseQuestionId(),
                         'anchor'=>$anchorFlag,
                         'choice'=>$userAnswer,
                         'correct_answer'=>$question->getCorrectChoice(),
                         'pass_fail'=>$correctFlag,
                         ]
                    );
                }
            }
    
            Q4Section2Question5::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q4Section2Question5::raw("past_testee_number + 1"),
                "correct_testee_number" => Q4Section2Question5::raw("correct_testee_number + 1")
            ]);
            Q4Section2Question5::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q4Section2Question5::raw("past_testee_number + 1")
            ]);
    
            $s2Q5Rate = round($scoring * 100 / 3);
        }

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q5_correct' => $scoring,
            's2_q5_question' => 3,
            's2_q5_perfect_score' => 11.5 / 80 * 120,
            's2_q5_anchor_pass' => $anchorFlag,
            's2_q5_rate' => $s2Q5Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q4S2Q5'))
                {
                    if ($key !== 'Q4S2Q5Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ4S2Q5 = $scoring;
        Session::put($userID.'.Q4S2Q5Score', $scoreQ4S2Q5);
        error_log($scoreQ4S2Q5);

    }

    public function Q4S6getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;
        $s2Q6Rate = 0;
        // dd(Session::all());
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        $section2Question6Id = $userID.".Q4S2Q6";
        $question = Session::get($section2Question6Id);
        // dd($questionDataLoad);
        if ($question != null)
        {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q4S2Q6_'.$questionId;
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

            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',6)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',4)->where('section',2)->where('question',6)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_42_06',
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
                     'section'=> 2,
                     'question' => 6,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_42_06',
                     'question_id'=>$question->getDatabaseQuestionId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
            }

        Q4Section2Question6::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q4Section2Question6::raw("past_testee_number + 1"),
            "correct_testee_number" => Q4Section2Question6::raw("correct_testee_number + 1")
        ]);
        Q4Section2Question6::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q4Section2Question6::raw("past_testee_number + 1")
        ]);

        $s2Q6Rate = round($scoring * 100 / 2);
        }

        $s2Q1Correct = Session::get($userID.".Q4S2Q1Score");
        $s2Q2Correct = Session::get($userID.".Q4S2Q2Score");
        $s2Q3Correct = Session::get($userID.".Q4S2Q3Score");
        $s2Q4Correct = Session::get($userID.".Q4S2Q4Score");
        $s2Q5Correct = Session::get($userID.".Q4S2Q5Score");
        $s2Q6Correct = $scoring;
        $section2Total = $s2Q1Correct / 13 * 13.5 / 80 * 120 +
                         $s2Q2Correct / 4 * 5.5 / 80 * 120 +
                         $s2Q3Correct / 4 * 8 / 80 * 120 +
                         $s2Q4Correct / 3 * 10.5 / 80 * 120 +
                         $s2Q5Correct / 3 * 11.5 / 80 * 120 +
                         $s2Q6Correct / 2 * 6 / 80 * 120;
        $s2Rate = ($s2Q1Correct+ $s2Q2Correct+ $s2Q3Correct+ $s2Q4Correct+ $s2Q5Correct + $s2Q6Correct)/(13+4+4+3+3+2);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q6_correct' => $scoring,
            's2_q6_question' => 2,
            's2_q6_perfect_score' => 6 / 80 * 120,
            's2_q6_anchor_pass' => $anchorFlag,
            's2_q6_rate' => $s2Q6Rate,
            's2_end_flag' => 1,
            's2_rate'=>$s2Rate,
            's2_score' => $section2Total]
        );
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q4S2Q6'))
                {
                    if ($key !== 'Q4S2Q6Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $anchorScoreQ4S1Q1 =  Session::get( $userID.'.Q4S1Q1Score_anchor');
        $anchorScoreQ4S1Q3 =  Session::get( $userID.'.Q4S1Q3Score_anchor');
        $anchorScoreQ4S2Q1 =  Session::get( $userID.'.Q4S2Q1Score_anchor');
        $currentAnchorScore = $anchorScoreQ4S1Q1+$anchorScoreQ4S1Q3+$anchorScoreQ4S2Q1;
        $currentAnchorPassRate = round($currentAnchorScore /
                                        (5.25 / 80 * 120 / 7 +
                                        6    / 80 * 120 / 8 +
                                        13.5 / 80 * 120 / 13 +
                                        10.5 / 35 * 60 / 8 +
                                        10.5 / 35 * 60 / 7 +
                                        7.5  / 35 * 60 / 8)
                                        * 100);
        Grades::where('examinee_number', substr($userID, 1))->where('level', 4)->update([
            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec2_score' => $section2Total
            ]);
        $scoreQ4S2Q6 = $scoring;
        // Session::put('Q4S1Q1Score',$scoreQ4S1Q1);    
        Session::put($userID.'.Q4S2Q6Score', $scoreQ4S2Q6);
        error_log($scoreQ4S2Q6);
        
        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 3]);

    }
}