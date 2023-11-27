<?php

namespace App\Http\Controllers\Q2\Vocabulary;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q2\Vocabulary\Q2Section1Question14;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q2\Vocabulary\Q2S1Q14;
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

class Q2S1Q14Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section1Question14Id = $currentId.".Q2S1Q14";
        // if (!(Session::has($section2Question6Id))) {
            $questionData = $this->showDataBase();

            Session::put($section1Question14Id, $questionData);
        // }

        $questionData = Session::get($section1Question14Id);
        // $questionList = $questionData[0];
        // $questionText = $questionList[0]->getText();
        //dd($questionData);
        
        return view('Q2\Vocabulary\pageQ2S1Q14', compact('questionData'));

    }

    function showDataBase()
    {
        $classReadingTheme =['1000','2000','2800','2900','2905','3100','3105','3200','3205','3300',
        '3305','3307','3600','3603','3605','3607','3700','3800','4000','4400','4500','4600','4800',
        '4900','4905','5000','5100','5200','5300','5400','5800','5900','5905','6000','6700', '6705',
        '6800','6805','7200','7500','7600','7700','7800','7900','8000','9000','9100']; 
        
        $classInfoRetrieval = ['010','020','030','040','050','060','070'];
        

        $results = Q2Section1Question14::where('usable', '1')
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
            $value = new Q2S1Q14(
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

        //dd($groupByTextNumberArray);
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
            $elements->setQuestionId($key + 74);
        } 
        return $resultarray;
        
       
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q2S1Q14_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question14Choice = $currentId.".Q2S1Q14_".$questionNumber;

        $sess = Session::get($section1Question14Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];  
        $scoring = 0;
        $anchorFlag = 0;
        
        $userID = Session::get('idTester');
        //dd($userID);
        $section1Question14Id = $userID.".Q2S1Q14";
        $questionDataLoad = Session::get($section1Question14Id);
        // dd($questionDataLoad);
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
        $currentAnchorPassRate = round($currentAnchorScore/ 9.09126984126*100);
        
        Grades::where('examinee_number', substr($userID, 1))->where('level', 2)->update([
            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec1_score' => $section1Total
            ]);
        $scoreQ2S1Q14 = $scoring;
        // Session::put('Q4S1Q1Score',$scoreQ4S1Q1);    
        Session::put($userID.'.Q2S1Q14Score', $scoreQ2S1Q14);

        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 3]);

        return Redirect::to(url('/Q2S3Start'));
    }
   
    /*

    

   

    

    

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
    */
}