<?php

namespace App\Http\Controllers\Q4\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question2;
use App\TestInformation;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q4\Reading\Q4S2Q2;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;

use Session;

class Q4S2Q2Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question2Id = $currentId.".Q4S2Q2";
        // if (!(Session::has( $section2Question2Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put( $section2Question2Id,$questionData);        
        // }
        $questionDataLoad = Session::get($section2Question2Id);
        // dd($questionDataLoad);
        $data = $this->paginate($questionDataLoad);
        return view('Q4\Reading\pageQ4S2Q2', compact('data')); //

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
        $section2Question2Id = $userID.".Q4S2Q2";
        $questionDataLoad = Session::get($section2Question2Id);

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

        return Redirect::to(url('/Q4ReadingQ3'));
        
    }
   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q4S2Q2_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question2Choice = $currentId.".Q4S2Q2_".$questionNumber;

        $sess = Session::get($section2Question2Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 
        $othersId = [];
        $sentencePatternId = [];
        $newQuestionId = [];
        $results = Q4Section2Question2::where('usable', 1)->whereBetween('correct_answer_rate',[0.2,0.8])->get();
        foreach ($results as $user) {
            $value = new Q4S2Q2( //q4s2
                $user->id,
                $user->question_id,
                $user->grammar,
                $user->class_grammar,
                $user->past_testee_number,
                $user->correct_testee_number,
                $user->question,
                $user->choice_a,
                $user->choice_b,
                $user->choice_c,
                $user->choice_d,
                $user->correct_answer,
                $user->new_question
            );
            array_push($valueArray,$value);

            $grammarType = $user->class_grammar;
            $idQuestion = $user->id;
            $newQuestionFlag= $user->new_question;

            if ($newQuestionFlag == 1)
            {
                array_push($newQuestionId,$idQuestion);
            }
            else switch ($grammarType){
                case "010":
                case "020":
                case "060":
                    array_push($othersId,$idQuestion);
                    break;
                case "030":
                    array_push($sentencePatternId,$idQuestion);
                    break;
                default:
                    break;
            }
        }

        $counter = 0;
        $questionIdArray = [];
        while($counter == 0)
        {
        $questionIdArray = static::getRandomQuestionId($othersId,$sentencePatternId);
        //shuffle($questionIdArray);
        if(!empty($newQuestionId))
        {
            $arrayNewQuestionId = array_rand($newQuestionId, 1);
            $newQuestionId1 = $newQuestionId[$arrayNewQuestionId];
            foreach ($valueArray as $val) {
            if ($val->getId() === $newQuestionId1) {
                $valueGrammarClass = $val->getGrammarClass();
                switch ($valueGrammarClass){
                    case "010":
                    case "020":
                    case "060":
                        array_splice($questionIdArray, 0,1);
                        array_push($questionIdArray,$newQuestionId1);
                        break;
                    case "030":
                        array_splice($questionIdArray, 1,1);
                        array_push($questionIdArray,$newQuestionId1);
                        break;
                    default:
                        break;
                    }
                }
            }
        }
        
        
        shuffle($questionIdArray);
        $answerArray = [];
        foreach($questionIdArray as $idValueInArray)
        {
            $idValue = static::searchForId($idValueInArray, $valueArray);
            array_push($answerArray,$valueArray[$idValue]->getGrammar());
        }
        if (!($this->hasDupe($answerArray)))
            $counter = 1;
        }

        $questionList = [];
        foreach($questionIdArray as $id)
        {
            $idValue = static::searchForId($id, $valueArray);
            array_push($questionList,$valueArray[$idValue]);
        }
        foreach($questionList as $key=>$elements)
        {
            $valueKey = $key;
            $elements->setQuestionId($key+14);
        }
        return $questionList;
    }

    function hasDupe($array) {
        return count($array) !== count(array_unique($array));
    }

    public function paginate($items, $perPage = 4, $page = null, $options = [])
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

    function getRandomQuestionId ($othersId,$sentencePatternId)
    {

        $arrayOthersId = array_rand($othersId, 1);
        $othersId1 = $othersId[$arrayOthersId];

        $arraySentencePatternId = array_rand($sentencePatternId, 3);
        $sentencePatternId1 = $sentencePatternId[$arraySentencePatternId[0]];
        $sentencePatternId2 = $sentencePatternId[$arraySentencePatternId[1]];
        $sentencePatternId3 = $sentencePatternId[$arraySentencePatternId[2]];

        return [$othersId1,$sentencePatternId1,$sentencePatternId2,$sentencePatternId3];
    }
}