<?php

namespace App\Http\Controllers\Q4\Reading;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q4\Reading\Q4Section2Question3;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q4\Reading\Q4S2Q3;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Session;
use Illuminate\Support\Facades\Config;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;
use Illuminate\Support\Facades\Redirect;

class Q4S2Q3Controller extends Controller
{
    public function showQuestion (){
        $currentId = Session::get('idTester');
        $section2Question3Id = $currentId.".Q4S2Q3";
        // if (!(Session::has($section2Question3Id))) {
            $questionData = $this->showDataBase();
            Session::put($section2Question3Id, $questionData);
        // }

        $questionData = Session::get($section2Question3Id);
        // dd ($questionData);
        // dd(Session::all());
        // $threeQuestionList = $questionData[0];
        // $threeQuestionText = $threeQuestionList[0]->getText();
        // $twoQuestionList = $questionData[1];
        // $twoQuestionText = $twoQuestionList[0]->getText();
        $questionText = $questionData[0]->getText();
        return view('Q4\Reading\pageQ4S2Q3', compact('questionData','questionText'));

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
        $section2Question3Id = $userID.".Q4S2Q3";
        $questionDataLoad = Session::get($section2Question3Id);
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

        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's2_q3_correct' => $scoring,
            's2_q3_question' => 4,
            's2_q3_perfect_score' => 8 / 80 * 12,
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

        return Redirect::to(url('/Q4ReadingQ4'));

    }

   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q4S2Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section2Question3Choice = $currentId.".Q4S2Q3_".$questionNumber;

        $sess = Session::get($section2Question3Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 
        $particalId = [];
        $contextConjugationConnectionId = [];
        $otherPatternsId = [];
        $contextId = [];

        //this is special case because this is in set of questions. do the correct answer rate below.
        $results = Q4Section2Question3::where('usable', 1)->where('not_in_use',0)->get();
        $groupByTextNumberArray = [];

        foreach ($results as $question) {
            $value = new Q4S2Q3(
                $question->id,
                $question->question_id,
                $question->grammar,
                $question->class_grammar,
                $question->correct_answer_rate,
                $question->past_testee_number,
                $question->correct_testee_number,
                $question->question,
                $question->text,
                $question->text_number,
                $question->choice_a,
                $question->choice_b,
                $question->choice_c,
                $question->choice_d,
                $question->correct_answer,
                $question->new_question);
            array_push($valueArray,$value);

            $idQuestion = $question->id;
            // $textNumber = $question->text_number;
            $classGrammarType = $question->class_grammar;
            $textNumberGroup = $question->text_number;

            if(array_key_exists($textNumberGroup,$groupByTextNumberArray))
                array_push($groupByTextNumberArray[$textNumberGroup],$value);
            else 
                $groupByTextNumberArray[$textNumberGroup] = [$value];  
            switch ($classGrammarType){
                case "040":
                case "050":
                    array_push($contextConjugationConnectionId,$textNumberGroup);
                    break;
                case "010":
                case "020":
                case "030":
                    array_push($otherPatternsId,$textNumberGroup);
                    break;
                default:
                    break;
            }
        }
        $counter = 0;
        $result = [];

        while($counter == 0)
        {
            $arrayResultId = array_rand($groupByTextNumberArray, 1);
            $result = $groupByTextNumberArray[$arrayResultId];

            $counterContextConjugationConnection = 0;
            $counterOtherPattern = 0;
            foreach ($result as $val) {
                $classGrammarCase = $val->getGrammarClass();
                switch ($classGrammarCase){
                    case "040":
                    case "050":
                        $counterContextConjugationConnection++;
                        break;
                    case "010":
                    case "020":
                    case "030":
                        $counterOtherPattern++;
                        break;
                    default:
                        break;
                }            
            }
            if ($counterContextConjugationConnection == 1 && $counterOtherPattern == 3)
                $counter = 1;
        }


        // $arrayResultId = array_rand($intersection, 1);
        // $resultsId1 = $intersection[$arrayResultId];
        

        foreach($result as $key=>$elements)
        {
            $valueKey = $key;
            $elements->setQuestionId($key+17);
        }
        return $result;
    }

    function hasDupe($array) {
        return count($array) !== count(array_unique($array));
    }

    function correctAnswerRateInRage($array) {
        foreach ($array as $value) {
            if ( (0.2 > $value) || ($value > 0.8))
                return false;
        }    
        return true;
    }

    public function paginate($items, $perPage = 2, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    function getRandomQuestionId ($particalId,$conjugationConnectionId,$sentencePatternsId,$anchorId,$valueArray)
    {
        $arrayAnchorId = array_rand($anchorId, 1);
        $anchorId1 = $anchorId[$arrayAnchorId];
        $valueAnchor = $valueArray[$anchorId1-1]->getPartOfSpeech();

        $result = [];

        $particalCounter = 7;
        $conjugationConnectionCounter = 4;
        $sentencePatternsCounter = 5;
        if ($valueAnchor == "020")
            $conjugationConnectionCounter = 3;
        else if ($valueAnchor == "030")
            $sentencePatternsCounter = 4;
        else 
            $particalCounter  = 6;

        $particalArray = [];
        $conjugationConnectionArray = [];
        $sentencePatternsArray = [];

        $arraypParticalId = array_rand($particalId, $particalCounter);
        $arrayConjugationConnectionId = array_rand($conjugationConnectionId, $conjugationConnectionCounter);
        $arraySentencePatternsIdId = array_rand($sentencePatternsId, $sentencePatternsCounter);

        for ($counter = 0; $counter < $particalCounter; $counter++)
            {
                $particalIdValue = $particalId[$arraypParticalId[$counter]];
                array_push($particalArray,$particalIdValue);
            }
        for ($counter = 0; $counter < $conjugationConnectionCounter; $counter++)
            {
                $conjugationConnectionIdValue = $conjugationConnectionId[$arrayConjugationConnectionId[$counter]];
                array_push($conjugationConnectionArray,$conjugationConnectionIdValue);
            }
        for ($counter = 0; $counter < $sentencePatternsCounter; $counter++)
            {
                $sentencePatternsIdValue = $sentencePatternsId[$arraySentencePatternsIdId[$counter]];
                array_push($sentencePatternsArray,$sentencePatternsIdValue);
            }
        
            $result = array_merge($particalArray,$conjugationConnectionArray,$sentencePatternsArray);
            array_push($result,$anchorId1);
        return $result;
    
    }
}