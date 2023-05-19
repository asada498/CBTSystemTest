<?php

namespace App\Http\Controllers\Q3\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q3\Reading\Q3Section2Question4;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q3\Reading\Q3S2Q4;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;


class Q3S2Q4Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question4Id = $currentId.".Q3S2Q4";
        // if (!(Session::has( $section2Question4Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put( $section2Question4Id,$questionData);        
        // }
        $questionDataLoad = Session::get( $section2Question4Id);
        $data = $this->paginate($questionDataLoad);
        //dd($questionDataLoad, $data, session());
        return view('Q3\Reading\pageQ3S2Q4', compact('data')); //

    }

    public function getResultToCalculate (Request $request){

        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        
        
        $userID = Session::get('idTester');
        $section2Question4Id = $userID.".Q3S2Q4";
        $questionDataLoad = Session::get($section2Question4Id);
        //dd($questionDataLoad, session());

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

        return Redirect::to(url('/Q3ReadingQ5'));
        
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q3S2Q4_".$questionNumber;
        Session::put($valueSession,$answer);  
        return response()->json(['success'=>$valueSession]);

    }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question4Choice = $currentId.".Q3S2Q4_".$questionNumber;

        $sess = Session::get($section2Question4Choice);        
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $classReadingTheme =['1000','2000','2800','2900','2905','3100','3105','3200','3205','3300',
        '3305','3307','3600','3603','3605','3607','3700','3800','4000','4400','4500','4600','4800',
        '4900','4905','5000','5100','5200','5300','5400','5800','5900','5905','6000','6700','6705',
        '6800','6805','7200','7500','7600','7700','7800','7900','8000','9000','9100'];   
        

        $results = Q3Section2Question4::select('*')
        ->where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->whereIn('class_reading_theme', $classReadingTheme)
        ->get();


        //dd($results);
        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];
        $newQuestion = null;

        foreach($array as $val)
        { 
            $value = new Q3S2Q4(
                $val['id'],
                $val['question_id'],
                $val['class_reading_theme'],
                $val['past_testee_number'],
                $val['correct_testee_number'],
                $val['text'],
                $val['question'],
                $val['choice_a'],
                $val['choice_b'],
                $val['choice_c'],
                $val['choice_d'],
                $val['correct_answer'],
                $val['letter'],
                $val['new_question']
            ); 

            if($val['new_question']==1 and $newQuestion==null)
            { 
                array_push($resultarray, $value);
                break;
            }                            
            
        }
        
        foreach($array as $val)
        { 
            $value = new Q3S2Q4(
                $val['id'],
                $val['question_id'],
                $val['class_reading_theme'],
                $val['past_testee_number'],
                $val['correct_testee_number'],
                $val['text'],
                $val['question'],
                $val['choice_a'],
                $val['choice_b'],
                $val['choice_c'],
                $val['choice_d'],
                $val['correct_answer'],
                $val['letter'],
                $val['new_question']
            );       
                           
            if($val['new_question']==1) continue;
            else
            {
                $counter = 0;
                foreach ($resultarray as $result ) {
                    if ( $value->text == $result->text ) {
                        $counter++;
                    }
                }
                if($counter==0)
                {
                    array_push($resultarray, $value);
                }

                $lengthArr = count($resultarray);
                if($lengthArr==4){break;}
            }                         
           
        }
        //dd($resultarray);
        shuffle($resultarray);
        foreach ($resultarray as $key => $elements) {
            $elements->setQuestionId($key + 24);
        }        
        return $resultarray;

    }

    function hasDupe($array) {
        return count($array) !== count(array_unique($array));
    }

    public function paginate($items, $perPage = 1, $page = null, $options = [])
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


    function fetchData(Request $request)
    {    
        $currentId = Session::get('idTester');
        $section2Question4Id = $currentId.".Q3S2Q4";

        if ($request->ajax()) {
            $questionDataLoad = Session::get($section2Question4Id);
            $data = $this->paginate($questionDataLoad);

            return view('Q3\Reading\paginationDataQ3S2Q4', compact('data'))->render(); //
        }
    }
}