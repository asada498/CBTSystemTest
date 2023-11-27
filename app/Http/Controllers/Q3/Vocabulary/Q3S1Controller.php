<?php

namespace App\Http\Controllers\Q3\Vocabulary;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q3\Vocabulary\Q3Section1Question1;
use App\QuestionDatabase\Q3\Vocabulary\Q3Section1Question2;
use App\QuestionDatabase\Q3\Vocabulary\Q3Section1Question3;
use App\QuestionDatabase\Q3\Vocabulary\Q3Section1Question4;
use App\QuestionDatabase\Q3\Vocabulary\Q3Section1Question5;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use App\ExamineeLogin;
use App\Grades;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Session;
use Illuminate\Support\Facades\Config;

class Q3S1Controller extends Controller
{

    public function timeOutCalculation (){

        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');

        if (!(Session::has($userID.'.Q3S1Q1Score')))
            static::Q3S1getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q3S1Q2Score')))
            static::Q3S2getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q3S1Q3Score')))
            static::Q3S3getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q3S1Q4Score')))
            static::Q3S4getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q3S1Q5Score')))
            static::Q3S5getResultToCalculate($userID);

        return response(200);
    }

    public function Q3S1getResultToCalculate ($userID)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorPassFlag = 0;

        $userID = Session::get('idTester');
        $section1Question1Id = $userID.".Q3S1Q1";

        $questionDataLoad = Session::get($section1Question1Id);
        Session::put($userID.".Q3S1Q1Score_anchor", 0);

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S1Q1_' . $questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment', '5-1-1')->first()->code;

                if ($question->getAnchor() == '1') {
                    $anchorFlag = 1;
                } else {
                    $anchorFlag = 0;
                }

                if ($question->getCorrectChoice() == $userAnswer) {
                    $correctFlag = 1;
                    if ($anchorFlag == 1) {
                        $anchorPassFlag = 1;
                        Session::put($userID.".Q3S1Q1Score_anchor", 5.5 / 55 * 60 / 8);
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',1)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',1)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_31_01',
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
                            'section' => 1,
                            'question' => 1,
                            'number' => $questionId,
                            'question_type' => $codeQuestion,
                            'question_table_name' => 'q_31_01',
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
            Q3Section1Question1::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section1Question1::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section1Question1::raw("correct_testee_number + 1")
            ]);
            Q3Section1Question1::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section1Question1::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q3Section1Question1::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q3Section1Question1::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s1Q1Rate = round($scoring * 100 / 8);

        if ($anchorPassFlag == 1) {            
            ScoreSummary::where('examinee_number',substr($userID, 1))->update([
                's1_q1_correct' => $scoring,
                's1_q1_question' => 8,
                's1_q1_perfect_score' => 6,
                's1_q1_anchor_pass' => 1,
                's1_q1_rate' => $s1Q1Rate]);    
        } else {

            ScoreSummary::where('examinee_number',substr($userID, 1))->update([
                's1_q1_correct' => $scoring,
                's1_q1_question' => 8,
                's1_q1_perfect_score' => 6,
                's1_q1_rate' => $s1Q1Rate]);
        }

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q3S1Q1'))
            {
                if ($key !== 'Q3S1Q1Score' && $key !== 'Q3S1Q1Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;
                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ3S1Q1 = $scoring;
        Session::put($userID.'.Q3S1Q1Score', $scoreQ3S1Q1);
    }

    public function Q3S2getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
 
        $userID = Session::get('idTester');
        $section1Question2Id = $userID.".Q3S1Q2";
        $questionDataLoad = Session::get($section1Question2Id);

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S1Q2_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-2')->first()->code;
                $correctFlag = null;
                $passFail = null;

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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',2)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',2)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_31_02',
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
                        'section'=> 1,
                        'question' => 2,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_31_02',
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
            
            Q3Section1Question2::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section1Question2::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section1Question2::raw("correct_testee_number + 1")
            ]);
            Q3Section1Question2::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section1Question2::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q3Section1Question2::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q3Section1Question2::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s1Q2Rate = round($scoring * 100 / 6);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q2_correct' => $scoring,
            's1_q2_question' => 6,
            's1_q2_perfect_score' => 4 / 55 * 60,
            's1_q2_anchor_pass' => $anchorFlag,
            's1_q2_rate' => $s1Q2Rate]);
            
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S1Q2'))
                {
                    if ($key !== 'Q3S1Q2Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
            
        $scoreQ3S1Q2 = $scoring;
        Session::put($userID.'.Q3S1Q2Score', $scoreQ3S1Q2);
    }

    public function Q3S3getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
     
        $userID = Session::get('idTester');        
        $section1Question3Id = $userID.".Q3S1Q3";
       
        $questionDataLoad = Session::get($section1Question3Id);
        Session::put($userID.".Q3S1Q3Score_anchor", 0);
        
        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S1Q3_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-3')->first()->code;
                // $correctFlag;
                // $passFail;
                //dd($question->getAnchor());
                if($question->getAnchor() == 0 or $question->getAnchor()==null ) //if($question->getAnchor() == 1)     
                        $anchorFlag = 0;
                else $anchorFlag = 1;
                if ($question->getCorrectChoice() == $userAnswer)
                {
                    $correctFlag = 1;
                    if ($anchorFlag == '1')
                    {
                        $anchorFlagResult = 1;
                        Session::put($userID.".Q3S1Q3Score_anchor", 7.5 / 55 * 60 / 11);
                    }
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',3)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',3)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_31_03',
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
                     'section'=> 1,
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_31_03',
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
            
            Q3Section1Question3::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section1Question3::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section1Question3::raw("correct_testee_number + 1")
            ]);
            Q3Section1Question3::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section1Question3::raw("past_testee_number + 1")
            ]);
        }

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q3Section1Question3::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q3Section1Question3::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q3Rate = round($scoring * 100 / 11);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q3_correct' => $scoring,
            's1_q3_question' => 11,
            's1_q3_perfect_score' => 7.5 / 55 * 60,
            's1_q3_anchor_pass' => $anchorFlagResult,
            's1_q3_rate' => $s1Q3Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S1Q3'))
                {
                    if ($key !== 'Q3S1Q3Score' && $key !== 'Q3S1Q3Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        
        $scoreQ3S1Q3 = $scoring;
        Session::put($userID.'.Q3S1Q3Score', $scoreQ3S1Q3);
    }

    public function Q3S4getResultToCalculate ($userID){
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        
        $userID = Session::get('idTester');
        $section1Question4Id = $userID.".Q3S1Q4";
        
        $questionDataLoad = Session::get($section1Question4Id);
        Session::put($userID.".Q3S1Q4Score_anchor", 0);

        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S1Q4_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-4')->first()->code;
                // $correctFlag;
                // $passFail;
                // dd($question->getId());

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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',4)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',4)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_31_04',
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
                     'section'=> 1,
                     'question' => 4,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_31_04',
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
            
            Q3Section1Question4::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section1Question4::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section1Question4::raw("correct_testee_number + 1")
            ]);
            Q3Section1Question4::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section1Question4::raw("past_testee_number + 1")
            ]);
        }

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q3Section1Question4::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q3Section1Question4::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q3Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q4_correct' => $scoring,
            's1_q4_question' => 5,
            's1_q4_perfect_score' => 7.5 / 55 * 60,
            's1_q4_anchor_pass' => $anchorFlagResult,
            's1_q4_rate' => $s1Q3Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q3S1Q4'))
                {
                    if ($key !== 'Q3S1Q4Score' && $key !== 'Q3S1Q4Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        
        $scoreQ3S1Q4 = $scoring;
        Session::put($userID.'.Q3S1Q4Score', $scoreQ3S1Q4);
    }

    public function Q3S5getResultToCalculate ($userID){

        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;

        $userID = Session::get('idTester');
        $section1Question5Id = $userID.".Q3S1Q5";

        $questionDataLoad = Session::get($section1Question5Id);
        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q3S1Q5_' . $questionId;
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',5)->where('number',$questionId)->exists())
                    {
                        AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',3)->where('section',1)->where('question',5)->where('number',$questionId)->update(
                            [
                            'question_type'=>$codeQuestion,
                            'question_table_name'=>'q_31_05',
                            'question_id'=>$question->getDatabaseQuestionId(),
                            'anchor'=> 0,
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
                            'section' => 1,
                            'question' => 5,
                            'number' => $questionId,
                            'question_type' => $codeQuestion,
                            'question_table_name' => 'q_31_05',
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
            Q3Section1Question5::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q3Section1Question5::raw("past_testee_number + 1"),
                "correct_testee_number" => Q3Section1Question5::raw("correct_testee_number + 1")
            ]);
            Q3Section1Question5::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q3Section1Question5::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q3Section1Question5::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q3Section1Question5::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s1Q5Rate = round($scoring * 100 / 5);

        $s1Q1Correct = Session::get($userID.".Q3S1Q1Score");
        $s1Q2Correct = Session::get($userID.".Q3S1Q2Score");
        $s1Q3Correct = Session::get($userID.".Q3S1Q3Score");
        $s1Q4Correct = Session::get($userID.".Q3S1Q4Score");
        $s1Q5Correct = $scoring;
        $section1Total = $s1Q1Correct /8*5.5/55*60 + $s1Q2Correct /6*4/55*60 + $s1Q3Correct /11*7.5/55*60 + $s1Q4Correct /5*7.5/55*60 + $s1Q5Correct /5*7.5/55*60;
        $s1Rate = ($s1Q1Correct+$s1Q2Correct+$s1Q3Correct+$s1Q4Correct+$s1Q5Correct)/(8+6+11+5+5);

        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 3)->update([
            's1_q5_correct' => $scoring,
            's1_q5_question' => 5,
            's1_q5_perfect_score' => 7.5/55*60,
            's1_end_flag' => 1,
            's1_q5_rate' => $s1Q5Rate,
            's1_rate'=>$s1Rate,
            's1_score' => $section1Total
        ]);

        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q3S1Q5'))
            {
                if ($key !== 'Q3S1Q5Score' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }

        $anchorScoreQ3S1Q1 =  Session::get( $userID.'.Q3S1Q1Score_anchor');
        $anchorScoreQ3S1Q3 =  Session::get( $userID.'.Q3S1Q3Score_anchor');
        $currentAnchorScore = $anchorScoreQ3S1Q1+$anchorScoreQ3S1Q3;
        $currentAnchorPassRate = round($currentAnchorScore/ 9.53704174*100);

        Grades::where('examinee_number', substr($userID, 1))->where('level', 3)->update([
            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec1_score' => $section1Total
        ]);
        $scoreQ3S1Q5 = $scoring;
        Session::put($userID.'.Q3S1Q5Score', $scoreQ3S1Q5);

        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 2]);
    }
}