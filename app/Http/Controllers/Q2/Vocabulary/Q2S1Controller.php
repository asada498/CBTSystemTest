<?php

namespace App\Http\Controllers\Q2\Vocabulary;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question1;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question2;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question3;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question4;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question5;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question6;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question7;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question8;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question9;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question10;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question11;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question12;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question13;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question14;
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

class Q2S1Controller extends Controller
{

    public function timeOutCalculation (){

        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "123test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');

        if (!(Session::has($userID.'.Q2S1Q1Score')))
            static::Q2S1getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q2Score')))
            static::Q2S2getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q3Score')))
            static::Q2S3getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q4Score')))
            static::Q2S4getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q5Score')))
            static::Q2S5getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q6Score')))
            static::Q2S6getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q7Score')))
            static::Q2S7getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q8Score')))
            static::Q2S8getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q9Score')))
            static::Q2S9getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q10Score')))
            static::Q2S10getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q11Score')))
            static::Q2S11getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q12Score')))
            static::Q2S12getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q13Score')))
            static::Q2S13getResultToCalculate($userID);
        if (!(Session::has($userID.'.Q2S1Q14Score')))
            static::Q2S14getResultToCalculate($userID);

        error_log("timeOutCalculation");
        return response(200);
    }

    public function Q2S1getResultToCalculate ($userID)
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
        $section1Question1Id = $userID.".Q2S1Q1";

        $questionDataLoad = Session::get($section1Question1Id);

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q1_' . $questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment', '5-1-1')->first()->code;

                $anchorFlag = 0;

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

                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',1)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',1)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                            'question_table_name'=>'q_21_01',
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
                            'level' => 2,
                            'section' => 1,
                            'question' => 1,
                            'number' => $questionId,
                            'question_type' => $codeQuestion,
                            'question_table_name' => 'q_21_01',
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
            Q2Section1Question1::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question1::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question1::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question1::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question1::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q2Section1Question1::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q2Section1Question1::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }

        $s1Q1Rate = round($scoring * 100 / 5);
        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q1_correct' => $scoring,
            's1_q1_question' => 5,
            's1_q1_perfect_score' => 3.5/45*60,
            's1_q1_anchor_pass' => $anchorPassFlag,
            's1_q1_rate' => $s1Q1Rate]);    
       

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q2S1Q1'))
            {
                if ($key !== 'Q2S1Q1Score' )
                {
                    $afterSubmitSession = $userID.'.'.$key;
                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ2S1Q1 = $scoring;
        Session::put($userID.'.Q2S1Q1Score', $scoreQ2S1Q1);
    }

    public function Q2S2getResultToCalculate ($userID)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;

        if (!(Session::has('idTester'))) {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp); 
        }

        $userID = Session::get('idTester');
        $section1Question2Id = $userID.".Q2S1Q2";
        $questionDataLoad = Session::get($section1Question2Id);
        Session::put($userID.".Q2S1Q2Score_anchor", 0);

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q2_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-2')->first()->code;
                $correctFlag = null;
                $passFail = null;

                if($question->getAnchor() == 0 or $question->getAnchor()==null ) //if($question->getAnchor() == 1)     
                            $anchorFlag = 0;
                else $anchorFlag = 1;
                if ($question->getCorrectChoice() == $userAnswer)
                {
                    $correctFlag = 1;
                    if ($anchorFlag == 1)
                        {
                            $anchorFlagResult = 1;
                            Session::put($userID.".Q2S1Q2Score_anchor", 3.5/45*60/5);
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
                
                //TESTING CODE ONLY. DELETE LATER.
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',2)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',2)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                            'question_table_name'=>'q_21_02',
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
                            'level' => 2,
                            'section'=> 1,
                            'question' => 2,
                            'number'=> $questionId,
                            'question_type'=>$codeQuestion,
                            'question_table_name'=>'q_21_02',
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
            
            Q2Section1Question2::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question2::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question2::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question2::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question2::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q2Section1Question2::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q2Section1Question2::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s1Q2Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q2_correct' => $scoring,
            's1_q2_question' => 5,
            's1_q2_perfect_score' => 3.5/45*60,
            's1_q2_anchor_pass' => $anchorFlagResult,
            's1_q2_rate' => $s1Q2Rate]);
            
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q2'))
                {
                    if ($key !== 'Q2S1Q2Score' && $key !== 'Q2S1Q2Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
                        Session::forget($afterSubmitSession);
                    }
                }
            }
            
        $scoreQ2S1Q2 = $scoring;
        Session::put($userID.'.Q2S1Q2Score', $scoreQ2S1Q2);
    }

    public function Q2S3getResultToCalculate ($userID)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;

        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "130test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');        

        $section1Question3Id = $userID.".Q2S1Q3";
       
        $questionDataLoad = Session::get($section1Question3Id);
        // Session::put($userID.".Q2S1Q3Score_anchor", 0);
                
        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q3_'.$questionId;
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
                    if ($anchorFlag == 1)
                    {
                        $anchorFlagResult = 1;
                        // Session::put($userID.".Q2S1Q3Score_anchor", 1.125);
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',3)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',3)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_03',
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
                     'level' => 2,
                     'section'=> 1,
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_21_03',
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
            
            Q2Section1Question3::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question3::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question3::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question3::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question3::raw("past_testee_number + 1")
            ]);
        }

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q2Section1Question3::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q2Section1Question3::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q3Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q3_correct' => $scoring,
            's1_q3_question' => 5,
            's1_q3_perfect_score' => 4/45*60,
            's1_q3_anchor_pass' => $anchorFlagResult,
            's1_q3_rate' => $s1Q3Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q3'))
                {
                    if ($key !== 'Q2S1Q3Score' && $key !== 'Q2S1Q3Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        
        $scoreQ2S1Q3 = $scoring;    
        Session::put($userID.'.Q2S1Q3Score', $scoreQ2S1Q3);
    }

    public function Q2S4getResultToCalculate ($userID)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "Q20061744050201";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');        

        $section1Question4Id = $userID.".Q2S1Q4";
       
        $questionDataLoad = Session::get($section1Question4Id);
        Session::put($userID.".Q2S1Q4Score_anchor", 0);
                
        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q4_'.$questionId;
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
                    if ($anchorFlag == 1)
                    {
                        $anchorFlagResult = 1;
                        Session::put($userID.".Q2S1Q4Score_anchor", 5/45*60/7);
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',4)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',4)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_04',
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
                     'level' => 2,
                     'section'=> 1,
                     'question' => 4,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_21_04',
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
            
            Q2Section1Question4::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question4::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question4::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question4::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question4::raw("past_testee_number + 1")
            ]);
        }

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q2Section1Question4::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q2Section1Question4::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q3Rate = round($scoring * 100 / 7);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q4_correct' => $scoring,
            's1_q4_question' => 7,
            's1_q4_perfect_score' => 5/45*60,
            's1_q4_anchor_pass' => $anchorFlagResult,
            's1_q4_rate' => $s1Q3Rate
        ]);
        //dd(session());
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q4'))
                {
                    if ($key !== 'Q2S1Q4Score' && $key !== 'Q2S1Q4Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
            //dd(session());
        $scoreQ2S1Q4 = $scoring;    
        Session::put($userID.'.Q2S1Q4Score', $scoreQ2S1Q4);
    }

    public function Q2S5getResultToCalculate ($userID)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "130test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');        

        $section1Question5Id = $userID.".Q2S1Q5";
       
        $questionDataLoad = Session::get($section1Question5Id);
        // Session::put($userID.".Q2S1Q5Score_anchor", 0);
                
        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q5_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-3')->first()->code;
                // $correctFlag;
                // $passFail;
                //dd($question->getAnchor());
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',5)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',5)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_05',
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
                     'level' => 2,
                     'section'=> 1,
                     'question' => 5,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_21_05',
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
            
            Q2Section1Question5::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question5::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question5::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question5::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question5::raw("past_testee_number + 1")
            ]);
        }

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q2Section1Question5::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q2Section1Question5::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q3Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q5_correct' => $scoring,
            's1_q5_question' => 5,
            's1_q5_perfect_score' => 5/45*60,
            's1_q5_anchor_pass' => $anchorFlagResult,
            's1_q5_rate' => $s1Q3Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q5'))
                {
                    if ($key !== 'Q2S1Q5Score' && $key !== 'Q2S1Q5Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        
        $scoreQ2S1Q5 = $scoring;    
        Session::put($userID.'.Q2S1Q5Score', $scoreQ2S1Q5);
    }

    public function Q2S6getResultToCalculate ($userID)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;

        if (!(Session::has('idTester'))) {
            $userIDTemp = "123test";
            Session::put('idTester', $userIDTemp);
        }
        $userID = Session::get('idTester');
        $section1Question6Id = $userID.".Q2S1Q6";

        $questionDataLoad = Session::get($section1Question6Id);

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q6_' . $questionId;
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',6)->where('number',$questionId)->exists())
                    {
                        AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',6)->where('number',$questionId)->update(
                            [
                            'question_type'=>$codeQuestion,
                            'question_table_name'=>'q_21_06',
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
                            'level' => 2,
                            'section' => 1,
                            'question' => 6,
                            'number' => $questionId,
                            'question_type' => $codeQuestion,
                            'question_table_name' => 'q_21_06',
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
            Q2Section1Question6::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question6::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question6::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question6::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question6::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q2Section1Question6::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q2Section1Question6::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s1Q6Rate = round($scoring * 100 / 5);

        //dd($s1Q1Correct,$s1Q2Correct,$s1Q3Correct,$s1Q4Correct,$s1Q5Correct,$section1Total);
        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q6_correct' => $scoring,
            's1_q6_question' => 5,
            's1_q6_perfect_score' => 6/45*60,
            's1_q6_anchor_pass' => 0,
            's1_q6_rate' => $s1Q6Rate
        ]);

        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q2S1Q6'))
            {
                if ($key !== 'Q2S1Q6Score' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
 
        $scoreQ2S1Q6 = $scoring;
        Session::put($userID.'.Q2S1Q6Score', $scoreQ2S1Q6);
    }

    public function Q2S7getResultToCalculate ($userID)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "130test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');        

        $section1Question7Id = $userID.".Q2S1Q7";
       
        $questionDataLoad = Session::get($section1Question7Id);
        Session::put($userID.".Q2S1Q7Score_anchor", 0);
                
        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q7_'.$questionId;
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
                    if ($anchorFlag == 1)
                    {
                        $anchorFlagResult = 1;
                        Session::put($userID.".Q2S1Q7Score_anchor", 7/45*60/12);
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',7)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',7)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_07',
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
                     'level' => 2,
                     'section'=> 1,
                     'question' => 7,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_21_07',
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
            
            Q2Section1Question7::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question7::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question7::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question7::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question7::raw("past_testee_number + 1")
            ]);
        }

        // foreach($correctAnswer as $correct)
        // {
        //     $question = Q2Section1Question7::where('id', $correct)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        // foreach($incorrectAnswer as $incorrect)
        // {
        //     $question = Q2Section1Question7::where('id', $incorrect)->first();
        //     if($question->new_question and $question->past_testee_number >= 400)
        //     {
        //         $question->new_question=0;
        //         $question->save();
        //     }
        // }

        $s1Q7Rate = round($scoring * 100 / 12);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q7_correct' => $scoring,
            's1_q7_question' => 12,
            's1_q7_perfect_score' => 7/45*60,
            's1_q7_anchor_pass' => $anchorFlagResult,
            's1_q7_rate' => $s1Q7Rate
        ]);
        //dd(session());
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q7'))
                {
                    if ($key !== 'Q2S1Q7Score' && $key !== 'Q2S1Q7Score_anchor' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
            //dd(session());
        $scoreQ2S1Q7 = $scoring;    
        Session::put($userID.'.Q2S1Q7Score', $scoreQ2S1Q7);
    }

    public function Q2S8getResultToCalculate ($userID)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorPassFlag = 0;
        
        $userID = Session::get('idTester');
        $section1Question8Id = $userID.".Q2S1Q8";

        $questionDataLoad = Session::get($section1Question8Id);
        // Session::put($userID.".Q2S1Q8Score_anchor", 0);

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q8_' . $questionId;
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
                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',8)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',8)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_08',
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
                            'level' => 2,
                            'section' => 1,
                            'question' => 8,
                            'number' => $questionId,
                            'question_type' => $codeQuestion,
                            'question_table_name' => 'q_21_08',
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
            Q2Section1Question8::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question8::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question8::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question8::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question8::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q2Section1Question8::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q2Section1Question8::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s1Q8Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number', substr($userID, 1))->where('level', 2)->update([
            's1_q8_correct' => $scoring,
            's1_q8_question' => 5,
            's1_q8_perfect_score' => 5/45*60,
            's1_q8_rate' => $s1Q8Rate
            ]);
        

        // $request->session()->flush();
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q2S1Q8'))
            {
                if ($key !== 'Q2S1Q8Score' && $key !== 'Q2S1Q8Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }
        $scoreQ2S1Q8 = $scoring;
        Session::put($userID.'.Q2S1Q8Score', $scoreQ2S1Q8);
    }

    public function Q2S9getResultToCalculate ($userID)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;

        if (!(Session::has('idTester')))
        {
            $userIDTemp = "Q20061744050201";
            Session::put('idTester',$userIDTemp);        
        }
        $userID = Session::get('idTester');
        $section1Question9Id = $userID.".Q2S1Q9";
        $questionDataLoad = Session::get($section1Question9Id);

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {

                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q9_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-2-3')->first()->code;
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

                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',9)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',9)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_09',
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
                        'level' => 2,
                        'section'=> 1,
                        'question' => 9,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_09',
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

            Q2Section1Question9::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question9::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question9::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question9::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question9::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q2Section1Question9::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q2Section1Question9::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s1Q9Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q9_correct' => $scoring,
            's1_q9_question' => 5,
            's1_q9_perfect_score' => 6/45*60,
            's1_q9_anchor_pass' => $anchorFlag,
            's1_q9_rate' => $s1Q9Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q9'))
                {
                    if ($key !== 'Q2S1Q9Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ2S1Q9 = $scoring;
        Session::put($userID.'.Q2S1Q9Score', $scoreQ2S1Q9);
    }

    public function Q2S10getResultToCalculate ($userID)
    {
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
        
        $section1Question10Id = $userID.".Q2S1Q10";
        $questionDataLoad = Session::get($section1Question10Id); 

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q10_'.$questionId;
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

                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',10)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',10)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_10',
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
                        'level' => 2,
                        'section'=> 1,
                        'question' => 10,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_10',
                        'question_id'=>$question->getDatabaseQuestionId(),
                        'anchor'=>$anchorFlag,
                        'choice'=>$userAnswer,
                        'correct_answer'=>$question->getCorrectChoice(),
                        'pass_fail'=>$correctFlag,
                        ]
                    );
                }
            }

            Q2Section1Question10::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question10::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question10::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question10::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question10::raw("past_testee_number + 1")
            ]);

            
            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q2Section1Question10::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q2Section1Question10::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }        
        $s1Q10Rate = round($scoring * 100 / 5);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q10_correct' => $scoring,
            's1_q10_question' => 5,
            's1_q10_perfect_score' => 13/60*60,
            's1_q10_anchor_pass' => $anchorFlag,
            's1_q10_rate' => $s1Q10Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q10'))
                {
                    if ($key !== 'Q2S1Q10Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ2S1Q10 = $scoring;
        Session::put($userID.'.Q2S1Q10Score', $scoreQ2S1Q10);
    }

    public function Q2S11getResultToCalculate ($userID)
    {
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
        
        $section2Question11Id = $userID.".Q2S1Q11";
        $questionDataLoad = Session::get($section2Question11Id);
        
        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q11_'.$questionId;
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

                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',11)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',11)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_11',
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
                        'level' => 2,
                        'section'=> 1,
                        'question' => 11,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_11',
                        'question_id'=>$question->getDatabaseQuestionId(),
                        'anchor'=>$anchorFlag,
                        'choice'=>$userAnswer,
                        'correct_answer'=>$question->getCorrectChoice(),
                        'pass_fail'=>$correctFlag,
                        ]
                    );
                }
            }

            Q2Section1Question11::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question11::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question11::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question11::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question11::raw("past_testee_number + 1")
            ]);

            
            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q2Section1Question11::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q2Section1Question11::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s2Q11Rate = round($scoring * 100 / 9);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q11_correct' => $scoring,
            's1_q11_question' => 9,
            's1_q11_perfect_score' => 18/60*60,
            's1_q11_anchor_pass' => $anchorFlag,
            's1_q11_rate' => $s2Q11Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q11'))
                {
                    if ($key !== 'Q2S1Q11Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ2S1Q11 = $scoring;
        Session::put($userID.'.Q2S1Q11Score', $scoreQ2S1Q11);
    }

    public function Q2S12getResultToCalculate ($userID)
    {
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
        
        $section1Question12Id = $userID.".Q2S1Q12";
        $questionDataLoad = Session::get($section1Question12Id);

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q12_'.$questionId;
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

                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',12)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',12)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_12',
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
                        'level' => 2,
                        'section'=> 1,
                        'question' => 12,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_12',
                        'question_id'=>$question->getDatabaseQuestionId(),
                        'anchor'=>$anchorFlag,
                        'choice'=>$userAnswer,
                        'correct_answer'=>$question->getCorrectChoice(),
                        'pass_fail'=>$correctFlag,
                        ]
                    );
                }
            }

            Q2Section1Question12::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question12::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question12::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question12::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question12::raw("past_testee_number + 1")
            ]);

            
            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q2Section1Question12::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q2Section1Question12::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s1Q12Rate = round($scoring * 100 / 2);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q12_correct' => $scoring,
            's1_q12_question' => 2,
            's1_q12_perfect_score' => 10.5/60*60,
            's1_q12_anchor_pass' => $anchorFlag,
            's1_q12_rate' => $s1Q12Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q12'))
                {
                    if ($key !== 'Q2S1Q12Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ2S1Q12 = $scoring;
        Session::put($userID.'.Q2S1Q12Score', $scoreQ2S1Q12);
    }

    public function Q2S13getResultToCalculate ($userID)
    {
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

        $section1Question13Id = $userID.".Q2S1Q13";
        $questionDataLoad = Session::get($section1Question13Id);

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q13_'.$questionId;
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

                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',13)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',13)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_13',
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
                        'level' => 2,
                        'section'=> 1,
                        'question' => 13,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_13',
                        'question_id'=>$question->getDatabaseQuestionId(),
                        'anchor'=>$anchorFlag,
                        'choice'=>$userAnswer,
                        'correct_answer'=>$question->getCorrectChoice(),
                        'pass_fail'=>$correctFlag,
                        ]
                    );
                }
            }

            Q2Section1Question13::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question13::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question13::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question13::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question13::raw("past_testee_number + 1")
            ]);


            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q2Section1Question13::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q2Section1Question13::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        $s1Q13Rate = round($scoring * 100 / 3);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q13_correct' => $scoring,
            's1_q13_question' => 3,
            's1_q13_perfect_score' => 12/60*60,
            's1_q13_anchor_pass' => $anchorFlag,
            's1_q13_rate' => $s1Q13Rate
        ]);
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q13'))
                {
                    if ($key !== 'Q2S1Q13Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;

                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ2S1Q13 = $scoring;
        Session::put($userID.'.Q2S1Q13Score', $scoreQ2S1Q13);
    }

    public function Q2S14getResultToCalculate ($userID)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;
        
        $userID = Session::get('idTester');
        $section1Question14Id = $userID.".Q2S1Q14";
        $questionDataLoad = Session::get($section1Question14Id);

        if ($questionDataLoad !== null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q2S1Q14_'.$questionId;
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

                if (AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',14)->where('number',$questionId)->exists())
                {
                    AnswerRecord::where('examinee_number',substr($userID, 1))->where('level',2)->where('section',1)->where('question',14)->where('number',$questionId)->update(
                        [
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_14',
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
                        'level' => 2,
                        'section'=> 1,
                        'question' => 14,
                        'number'=> $questionId,
                        'question_type'=>$codeQuestion,
                        'question_table_name'=>'q_21_14',
                        'question_id'=>$question->getDatabaseQuestionId(),
                        'anchor'=>$anchorFlag,
                        'choice'=>$userAnswer,
                        'correct_answer'=>$question->getCorrectChoice(),
                        'pass_fail'=>$correctFlag,
                        ]
                    );
                }
            }
            Q2Section1Question14::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q2Section1Question14::raw("past_testee_number + 1"),
                "correct_testee_number" => Q2Section1Question14::raw("correct_testee_number + 1")
            ]);
            Q2Section1Question14::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q2Section1Question14::raw("past_testee_number + 1")
            ]);

            // foreach($correctAnswer as $correct)
            // {
            //     $question = Q2Section1Question14::where('id', $correct)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }

            // foreach($incorrectAnswer as $incorrect)
            // {
            //     $question = Q2Section1Question14::where('id', $incorrect)->first();
            //     if($question->new_question and $question->past_testee_number >= 400)
            //     {
            //         $question->new_question=0;
            //         $question->save();
            //     }
            // }
        }
        
        $s1Q14Rate = round($scoring * 100 / 2);
        $s1Q1Correct = Session::get($userID.".Q2S1Q1Score");
        $s1Q2Correct = Session::get($userID.".Q2S1Q2Score");
        $s1Q3Correct = Session::get($userID.".Q2S1Q3Score");
        $s1Q4Correct = Session::get($userID.".Q2S1Q4Score");
        $s1Q5Correct = Session::get($userID.".Q2S1Q5Score");
        $s1Q6Correct = Session::get($userID.".Q2S1Q6Score");

        $s1Q7Correct = Session::get($userID.".Q2S1Q7Score");
        $s1Q8Correct = Session::get($userID.".Q2S1Q8Score");
        $s1Q9Correct = Session::get($userID.".Q2S1Q9Score");
        $s1Q10Correct = Session::get($userID.".Q2S1Q10Score");
        $s1Q11Correct = Session::get($userID.".Q2S1Q11Score");
        $s1Q12Correct = Session::get($userID.".Q2S1Q12Score");
        $s1Q13Correct = Session::get($userID.".Q2S1Q13Score");
        $s1Q14Correct = $scoring;

        $section1Total = $s1Q1Correct *3.5/45*60/5 + $s1Q2Correct *3.5/45*60/5 + $s1Q3Correct *4/45*60/5 + $s1Q4Correct *5/45*60/7 + $s1Q5Correct *5/45*60/5 + $s1Q6Correct *6/45*60/5 + $s1Q7Correct *7/45*60/12
        + $s1Q8Correct *5/45*60/5 + $s1Q9Correct *6/45*60/5 + $s1Q10Correct *13/60*60/5 + $s1Q11Correct *18/60*60/9 + $s1Q12Correct *10.5/60*60/2 +$s1Q13Correct *12/60*60/3 +$s1Q14Correct *6.5/60*60/2;
        $s1Rate = ($s1Q1Correct+ $s1Q2Correct+ $s1Q3Correct+ $s1Q4Correct+ $s1Q5Correct + $s1Q6Correct + $s1Q7Correct
        + $s1Q8Correct + $s1Q9Correct + $s1Q10Correct + $s1Q11Correct + $s1Q12Correct + $s1Q13Correct + $s1Q14Correct)/(5+5+5+7+5+5+12+5+5+5+9+2+3+2);

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q14_correct' => $scoring,
            's1_q14_question' => 2,
            's1_q14_perfect_score' => 6.5/60*60,
            's1_q14_anchor_pass' => $anchorFlag,
            's1_q14_rate' => $s1Q14Rate,
            's1_end_flag' => 1,
            's1_rate'=>$s1Rate,
            's1_score' => $section1Total]
        );
        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q2S1Q14'))
                {
                    if ($key !== 'Q2S1Q14Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        
        $anchorScoreQ2S1Q2 =  Session::get( $userID.'.Q2S1Q2Score_anchor');
        $anchorScoreQ2S1Q4 =  Session::get( $userID.'.Q2S1Q4Score_anchor');
        $anchorScoreQ2S1Q7 =  Session::get( $userID.'.Q2S1Q7Score_anchor');
        $currentAnchorScore = $anchorScoreQ2S1Q2+$anchorScoreQ2S1Q4+$anchorScoreQ2S1Q7;
        $currentAnchorPassRate = round($currentAnchorScore/ 8.313492063*100);
        
        Grades::where('examinee_number', substr($userID, 1))->where('level', 2)->update([
            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec1_score' => $section1Total
            ]);
        $scoreQ2S1Q14 = $scoring;
        // Session::put('Q4S1Q1Score',$scoreQ4S1Q1);    
        Session::put($userID.'.Q2S1Q14Score', $scoreQ2S1Q14);

        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 2]);
    }
}