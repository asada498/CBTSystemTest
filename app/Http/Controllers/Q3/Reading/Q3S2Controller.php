<?php

namespace App\Http\Controllers\Q3\Reading;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question1;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question2;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question3;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question4;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question5;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question6;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question7;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use App\Grades;
use App\ExamineeLogin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Session;
use Illuminate\Support\Facades\Config;

class Q3S2Controller extends Controller
{

    public function timeOutCalculation (){

        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');

        if (!(Session::has($userID.'.Q3S2Q1Score')))
            static::Q3S1getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q3S2Q2Score')))
            static::Q3S2getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q3S2Q3Score')))
            static::Q3S3getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q3S2Q4Score')))
            static::Q3S4getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q3S2Q5Score')))
            static::Q3S5getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q3S2Q6Score')))
            static::Q3S6getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q3S2Q7Score')))
            static::Q3S7getResultToCalculate($userID);
        error_log("timeOutCalculation end");
        return response(200);
    }

    public function Q3S1getResultToCalculate ($userID){
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
        $section2Question1Id = $userID.".Q3S2Q1";

        $questionDataLoad = Session::get($section2Question1Id);
        Session::put($userID.".Q3S2Q1Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getQuestionId();
            $currentQuestion = $userID.'.Q3S2Q1_' . $questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = QuestionType::where('comment', '5-2-1')->first()->code;
            //    $correctFlag;
            //    $passFail;

            if ($question->getAnchor() == '1') {
                $anchorFlag = 1;
            } else {
                $anchorFlag = 0;
            }

            if ($question->getCorrectChoice() == $userAnswer) {
                $correctFlag = 1;
                $scoring++;
                array_push($correctAnswer, $question->getId());

                if ($question->getAnchor() == '1') {
                    $anchorPassFlag = 1;
                    Session::put($userID.".Q3S2Q1Score_anchor", 8.5 / 55 * 60 / 13);
                }
            } else {
                if ($question->getCorrectChoice() == null)
                    $correctFlag = null;
                else {
                    $correctFlag = 0;
                    array_push($incorrectAnswer, $question->getId());
                }
            }
            if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',1)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',1)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_32_01',
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
                    [
                        'examinee_number' => substr($userID, 1),
                        'level' => 3,
                        'section' => 2,
                        'question' => 1,
                        'number' => $questionId,
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_32_01',
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
        Q3Section2Question1::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q3Section2Question1::raw("past_testee_number + 1"),
            "correct_testee_number" => Q3Section2Question1::raw("correct_testee_number + 1")
        ]);
        Q3Section2Question1::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q3Section2Question1::raw("past_testee_number + 1")
        ]);

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q3Section2Question1::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q3Section2Question1::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s2Q1Rate = round($scoring * 100 / 13);


        if ($anchorPassFlag == 1) {

            ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 3)->update([
                    's2_q1_correct' => $scoring,
                    's2_q1_question' => 13,
                    's2_q1_perfect_score' => 8.5/55*60,
                    's2_q1_anchor_pass' => 1,
                    's2_q1_rate' => $s2Q1Rate
                ]);
        } else {

            ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 3)->update([
                's2_q1_correct' => $scoring,
                's2_q1_question' => 13,
                's2_q1_perfect_score' => 8.5/55*60,
                's2_q1_rate' => $s2Q1Rate
                ]);
        }

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q3S2Q1'))
            {
                if ($key !== 'Q3S2Q1Score' && $key !== 'Q3S2Q1Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ3S2Q1 = $scoring;
        Session::put($userID.'.Q3S2Q1Score', $scoreQ3S2Q1);
    }

    public function Q3S2getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorPassFlag = 0;

        $userID = Session::get('idTester');
        $section2Question2Id = $userID.".Q3S2Q2";

        $questionDataLoad = Session::get($section2Question2Id);

        if($questionDataLoad != null){
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S2Q2_' . $questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment', '5-2-1')->first()->code;
                //    $correctFlag;
                //    $passFail;
    
                
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',2)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',2)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_32_02',
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
                        [
                            'examinee_number' => substr($userID, 1),
                            'level' => 3,
                            'section' => 2,
                            'question' => 2,
                            'number' => $questionId,
                            'question_type' => $codeQuestion,
                            'question_table_name' => 'q_32_02',
                            'question_id' => $question->getDatabaseQuestionId(),
                            'anchor' => 0,
                            'choice' => $userAnswer,
                            'correct_answer' => $question->getCorrectChoice(),
                            'pass_fail' => $correctFlag,
                        ]
                    );
                }
                
            }
            //update record on database
            Q3Section2Question2::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section2Question2::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section2Question2::raw("correct_testee_number + 1")
            ]);
            Q3Section2Question2::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section2Question2::raw("past_testee_number + 1")
            ]);
    
            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q3Section2Question2::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
    
            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q3Section2Question2::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }

        $s2Q1Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 3)->update([
            's2_q2_correct' => $scoring,
            's2_q2_question' => 5,
            's2_q2_perfect_score' => 6/55*60,
            's2_q2_rate' => $s2Q1Rate
            ]);
        

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q3S2Q2'))
            {
                if ($key !== 'Q3S2Q2Score' && $key !== 'Q3S2Q2Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ3S2Q2 = $scoring;
        Session::put($userID.'.Q3S2Q2Score', $scoreQ3S2Q2);
    }

    public function Q3S3getResultToCalculate ($userID){
        
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;
       
        $userID = Session::get('idTester');
        $section2Question3Id = $userID.".Q3S2Q3";
        $questionDataLoad = Session::get($section2Question3Id);

        if($questionDataLoad != null){
            foreach ($questionDataLoad as $question) {
                // dd($questionPack);
                // foreach($questionPack as $question)
                // {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S2Q3_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-2-3')->first()->code;
                // $correctFlag;
                // $passFail;
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
    
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',3)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',3)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_32_03',
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
                         'level' => 3,
                         'section'=> 2,
                         'question' => 3,
                         'number'=> $questionId,
                         'question_type'=>$codeQuestion,
                         'question_table_name'=>'q_32_03',
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
    
            Q3Section2Question3::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section2Question3::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section2Question3::raw("correct_testee_number + 1")
            ]);
            Q3Section2Question3::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section2Question3::raw("past_testee_number + 1")
            ]);
    
            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q3Section2Question3::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
    
            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q3Section2Question3::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
    
        }

        $s2Q3Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q3_correct' => $scoring,
            's2_q3_question' => 5,
            's2_q3_perfect_score' => 8.5/55*60,
            's2_q3_anchor_pass' => $anchorFlag,
            's2_q3_rate' => $s2Q3Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S2Q3'))
                {
                    if ($key !== 'Q3S2Q3Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ3S2Q3 = $scoring;
        Session::put($userID.'.Q3S2Q3Score', $scoreQ3S2Q3);
    }

    public function Q3S4getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        
        $userID = Session::get('idTester');
        $section2Question4Id = $userID.".Q3S2Q4";
        $questionDataLoad = Session::get($section2Question4Id);
        //dd($questionDataLoad, session());

        if($questionDataLoad != null){
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S2Q4_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-2-4')->first()->code;
                // $correctFlag;
                // $passFail;

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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',4)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',4)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                            'question_table_name'=>'q_32_04',
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
                            'level' => 3,
                            'section'=> 2,
                            'question' => 4,
                            'number'=> $questionId,
                            'question_type'=>$codeQuestion,
                            'question_table_name'=>'q_32_04',
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
            
            Q3Section2Question4::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section2Question4::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section2Question4::raw("correct_testee_number + 1")
            ]);
            Q3Section2Question4::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section2Question4::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q3Section2Question4::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q3Section2Question4::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }

        $s2Q4Rate = round($scoring * 100 / 4);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q4_correct' => $scoring,
            's2_q4_question' => 4,
            's2_q4_perfect_score' => 12/45*60,
            's2_q4_anchor_pass' => $anchorFlag,
            's2_q4_rate' => $s2Q4Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S2Q4'))
                {
                    if ($key !== 'Q3S2Q4Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ3S2Q4 = $scoring;
        Session::put($userID.'.Q3S2Q4Score', $scoreQ3S2Q4);
    }

    public function Q3S5getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;

        $userID = Session::get('idTester');
        
        $section2Question5Id = $userID.".Q3S2Q5";
        $questionDataLoad = Session::get($section2Question5Id);

        if ($questionDataLoad != null){
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S2Q5_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-2-5')->first()->code;
                // $correctFlag;
                // $passFail;

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

                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',5)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',5)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_32_05',
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
                        'level' => 3,
                        'section'=> 2,
                        'question' => 5,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_32_05',
                        'question_id'=>$question->getDatabaseQuestionId(),
                        'anchor'=>$anchorFlag,
                        'choice'=>$userAnswer,
                        'correct_answer'=>$question->getCorrectChoice(),
                        'pass_fail'=>$correctFlag,
                        ]
                    );
                }
            }

            Q3Section2Question5::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section2Question5::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section2Question5::raw("correct_testee_number + 1")
            ]);
            Q3Section2Question5::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section2Question5::raw("past_testee_number + 1")
            ]);

            
            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q3Section2Question5::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q3Section2Question5::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

        }
        $s2Q5Rate = round($scoring * 100 / 6);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q5_correct' => $scoring,
            's2_q5_question' => 6,
            's2_q5_perfect_score' => 13/45*60,
            's2_q5_anchor_pass' => $anchorFlag,
            's2_q5_rate' => $s2Q5Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S2Q5'))
                {
                    if ($key !== 'Q3S2Q5Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ3S2Q5 = $scoring;
        Session::put($userID.'.Q3S2Q5Score', $scoreQ3S2Q5);
    }

    public function Q3S6getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;

        $userID = Session::get('idTester');
        
        $section2Question6Id = $userID.".Q3S2Q6";
        $questionDataLoad = Session::get($section2Question6Id);

        if($questionDataLoad != null){
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S2Q6_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-2-6')->first()->code;
                // $correctFlag;
                // $passFail;

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

                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',6)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',6)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_32_06',
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
                        'level' => 3,
                        'section'=> 2,
                        'question' => 6,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_32_06',
                        'question_id'=>$question->getDatabaseQuestionId(),
                        'anchor'=>$anchorFlag,
                        'choice'=>$userAnswer,
                        'correct_answer'=>$question->getCorrectChoice(),
                        'pass_fail'=>$correctFlag,
                        ]
                    );
                }
            }

            Q3Section2Question6::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section2Question6::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section2Question6::raw("correct_testee_number + 1")
            ]);
            Q3Section2Question6::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section2Question6::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q3Section2Question6::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q3Section2Question6::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s2Q5Rate = round($scoring * 100 / 4);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q6_correct' => $scoring,
            's2_q6_question' => 4,
            's2_q6_perfect_score' => 12/45*60,
            's2_q6_anchor_pass' => $anchorFlag,
            's2_q6_rate' => $s2Q5Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S2Q6'))
                {
                    if ($key !== 'Q3S2Q6Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ3S2Q6 = $scoring;
        Session::put($userID.'.Q3S2Q6Score', $scoreQ3S2Q6);
    }

    public function Q3S7getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;
        
        $userID = Session::get('idTester');
        $section2Question7Id = $userID.".Q3S2Q7";
        $questionDataLoad = Session::get($section2Question7Id);

        if($questionDataLoad != null){
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S2Q7_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-2-6')->first()->code;
                // $correctFlag;
                // $passFail;

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

                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',7)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',2)->where('question',7)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_32_07',
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
                        'level' => 3,
                        'section'=> 2,
                        'question' => 7,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_32_07',
                        'question_id'=>$question->getDatabaseQuestionId(),
                        'anchor'=>$anchorFlag,
                        'choice'=>$userAnswer,
                        'correct_answer'=>$question->getCorrectChoice(),
                        'pass_fail'=>$correctFlag,
                        ]
                    );
                }
            }
            Q3Section2Question7::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section2Question7::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section2Question7::raw("correct_testee_number + 1")
            ]);
            Q3Section2Question7::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section2Question7::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q3Section2Question7::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q3Section2Question7::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }

        $s2Q7Rate = round($scoring * 100 / 2);
        $s2Q1Correct = Session::get($userID.".Q3S2Q1Score");
        $s2Q2Correct = Session::get($userID.".Q3S2Q2Score");
        $s2Q3Correct = Session::get($userID.".Q3S2Q3Score");
        $s2Q4Correct = Session::get($userID.".Q3S2Q4Score");
        $s2Q5Correct = Session::get($userID.".Q3S2Q5Score");
        $s2Q6Correct = Session::get($userID.".Q3S2Q6Score");
        $s2Q7Correct = $scoring;
        $section2Total = $s2Q1Correct /13*8.5/55*60 + $s2Q2Correct /5*6/55*60 + $s2Q3Correct /5*8.5/55*60 + $s2Q4Correct /4*12/45*60 + $s2Q5Correct /6*13/45*60 + $s2Q6Correct /4*12/45*60 + $s2Q7Correct /2*8/45*60;
        $s2Rate = ($s2Q1Correct+ $s2Q2Correct+ $s2Q3Correct+ $s2Q4Correct+ $s2Q5Correct + $s2Q6Correct + $s2Q7Correct)/(13+5+5+4+6+4+2);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q7_correct' => $scoring,
            's2_q7_question' => 2,
            's2_q7_perfect_score' => 8/45*60,
            's2_q7_anchor_pass' => $anchorFlag,
            's2_q7_rate' => $s2Q7Rate,
            's2_end_flag' => 1,
            's2_rate'=>$s2Rate,
            's2_score' => $section2Total]
        );
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S2Q7'))
                {
                    if ($key !== 'Q3S2Q7Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        
        $anchorScoreQ3S1Q1 =  Session::get( $userID.'.Q3S1Q1Score_anchor');
        $anchorScoreQ3S1Q3 =  Session::get( $userID.'.Q3S1Q3Score_anchor');
        $anchorScoreQ3S2Q1 =  Session::get( $userID.'.Q3S2Q1Score_anchor');
        $currentAnchorScore = $anchorScoreQ3S1Q1+$anchorScoreQ3S1Q3+$anchorScoreQ3S2Q1;
        $currentAnchorPassRate = round($currentAnchorScore/ 10.237335*100);
        
        Grades::where('examinee_number', substr($userID, 1))->where('level', 3)->update([
            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec2_score' => $section2Total
            ]);
        $scoreQ3S2Q7 = $scoring;
        // Session::put('Q4S1Q1Score',$scoreQ4S1Q1);    
        Session::put($userID.'.Q3S2Q7Score', $scoreQ3S2Q7);

        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 3]);
    }
}