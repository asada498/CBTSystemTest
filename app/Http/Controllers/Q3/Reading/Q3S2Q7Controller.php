<?php

namespace App\Http\Controllers\Q3\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question7;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q3\Reading\Q3S2Q7;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;
use App\ExamineeLogin;
use Illuminate\Support\Facades\Redirect;
use App\Grades;

class Q3S2Q7Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question7Id = $currentId.".Q3S2Q7";
        // if (!(Session::has($section2Question6Id))) {
            $questionData = $this->showDataBase();

            Session::put($section2Question7Id, $questionData);
        // }

        $questionData = Session::get($section2Question7Id);
        // $questionList = $questionData[0];
        // $questionText = $questionList[0]->getText();
        
        return view('Q3\Reading\pageQ3S2Q7', compact('questionData'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;
        // dd(Session::all());
        
        $userID = Session::get('idTester');
        //dd($userID);
        $section2Question7Id = $userID.".Q3S2Q7";
        $questionDataLoad = Session::get($section2Question7Id);
        // dd($questionDataLoad);
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

        return Redirect::to(url('/Q3S3Start'));
    }

   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q3S2Q7_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question7Choice = $currentId.".Q3S2Q7_".$questionNumber;

        $sess = Session::get($section2Question7Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $classReadingTheme =['1000','2000','2800','2900','2905','3100','3105','3200','3205','3300',
        '3305','3307','3600','3603','3605','3607','3700','3800','4000','4400','4500','4600','4800',
        '4900','4905','5000','5100','5200','5300','5400','5800','5900','5905','6000','6700','6800',
        '6805','7200','7500','7600','7700','7800','7900','8000','9000','9100']; 
        
        $classInfoRetrieval = ['030','070','080','090','100','120','130'];
        

        $results = Q3Section2Question7::where('usable', '1')
        ->where('same_passage','!=' , '0')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->whereIn('class_reading_theme', $classReadingTheme)
        ->whereIn('Class_Info_Retrieval', $classInfoRetrieval)
        ->get();        

        
        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];         


        $groupByTextNumberArray = [];
        foreach($array as $val)
        { 
            $value = new Q3S2Q7(
                $val['id'],
                $val['question_id'],
                $val['past_testee_number'],
                $val['correct_testee_number'],
                $val['title'],
                $val['explanation_text'],
                $val['illustration'],
                $val['same_passage'],
                $val['question'],
                $val['choice_a'],
                $val['choice_b'],
                $val['choice_c'],
                $val['choice_d'],
                $val['correct_answer'],
                $val['new_question'],
            );         

            $textNumberGroup = $val['same_passage'];

            if(array_key_exists($textNumberGroup,$groupByTextNumberArray))
                array_push($groupByTextNumberArray[$textNumberGroup],$value);
            else 
                $groupByTextNumberArray[$textNumberGroup] = [$value];
        }

        

        foreach($groupByTextNumberArray as $val)
        { 
            if(count($val)>=2)
            { 
                $newQuestionId=999;
                for($i=0; $i<count($val); $i++)
                {                    
                    if($val[$i]->newQuestion==1)
                    {
                        $newQuestionId = $i; 
                        break;                       
                    } 
                }
                if($newQuestionId!=999)
                {
                    $randArrId = array_rand($val,2);
                    if(array_search($newQuestionId, $randArrId) !== null)
                    {
                        for($i=0; $i<2; $i++)
                        {
                            array_push($resultarray, $val[$randArrId[$i]]);
                        }
                    }
                    else
                    {
                        array_splice($randArrId,1,1,$newQuestionId);// array, start, length, array
                        for($i=0; $i<2; $i++)
                        {
                            array_push($resultarray, $val[$randArrId[$i]]);
                        } 
                    }
                    
                }
                if($resultarray!=null)
                {
                    break;
                }
                
            }
            
        }
        if($resultarray==null)
        {
            foreach($groupByTextNumberArray as $val)
            {
                if(count($val)>=2)
                {                
                    $randArrId = array_rand($val,2);
                    for($i=0; $i<2; $i++)
                    {
                        array_push($resultarray, $val[$randArrId[$i]]);
                    }                    
                    break;
                }    
                
            }    
        }

        

        foreach ($resultarray as $key => $elements) {
            $elements->setQuestionId($key + 38);
        } 
        return $resultarray;
                
        
    }

    function hasDupe($array) {
        return count($array) !== count(array_unique($array));
    }

    public function paginate($items, $perPage = 2, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    function searchForId($id, $array) {
        foreach ($array as $key => $val) {
            if ($val->getId() === $id) {
                return $key;
            }
        }
        return null;
     }
}