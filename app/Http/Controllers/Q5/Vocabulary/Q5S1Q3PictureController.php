<?php

namespace App\Http\Controllers\Q5\Vocabulary;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\QuestionDatabase\Q5\Vocabulary\Q5Section1Question3;
use App\TestInformation;
use Illuminate\Support\Facades\Storage;
use App\QuestionClass\Q5\Vocabulary\Q5S1Q3;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Session;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class Q5S1Q3PictureController extends Controller
{
    public function showQuestion (){
        // $currentId = Session::get('idTester');
        // $section1Question3Id = $currentId.".Q5S1Q3";
        // if (!(Session::has($section1Question3Id))) {
        //     $questionData = $this->showDataBase();
        //     Session::put($section1Question3Id, $questionData);
        // }
        // $questionData = $this->showDataBase();
        // Session::put('Q5S1Q3',$questionData);   
        $data = $this->showDataBase();

        // $questionDataLoad = Session::get($section1Question3Id);
        // dd($questionDataLoad);

        // $data = $this->paginate($questionDataLoad);
        // $tempUrl =  Storage::disk('s3')->url('Q5/Vocabulary/N11251030500.jpg');
        // $expiryDate = now()->addHours(4);
        // $temporaryUrl = Storage::disk('s3')->temporaryUrl("Q5/Vocabulary/N11251030500.jpg", $expiryDate);
        // dd($questionData);
        return view('Q5\Vocabulary\pictureTestQ5S1Q3', compact('data'));

    }

    public function getResultToCalculate (Request $request){
        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $perfectScore = Config::get('constants.Q5S1Q3.perfectScore');
        $testeeScore = 0;
        $percentageScore = 0;
        $anchorFlagResult = 0;
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "130test";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        $section1Question3Id = $userID.".Q5S1Q3";
        
        $questionDataLoad = Session::get($section1Question3Id);

        if ($questionDataLoad != null)
        {
            foreach ($questionDataLoad as $question) {
                $questionId = $question->getQuestionId();
                $currentQuestion = $userID.'.Q5S1Q3_'.$questionId;
                $userAnswer = Session::get($currentQuestion);
                $codeQuestion = QuestionType::where('comment','5-1-3')->first()->code;
                $correctFlag;
                $passFail;
                // dd($question->getId());
                if($question->getAnchorStatus() == "R")
                        $anchorFlag = 1;
                    else $anchorFlag = 0;
                if ($question->getCorrectChoice() == $userAnswer)
                {
                    $correctFlag = 1;
                    if ($anchorFlag == 1)
                        $anchorFlagResult = 1;
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
                if (AnswerRecord::where('examinee_number',$userID)->where('level',5)->where('section',1)->where('question',3)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',$userID)->where('level',5)->where('section',1)->where('question',3)->where('number',$questionId)->update(
                    [
                    'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_51_03',
                     'question_id'=>$question->getId(),
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
                     'section'=> 1,
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_51_03',
                     'question_id'=>$question->getId(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectChoice(),
                     'pass_fail'=>$correctFlag,
                     ]
                );
               }
            }
            //update record on database
            
            Q5Section1Question3::whereIn('id', $correctAnswer)->update([
                "past_testee_number" => Q5Section1Question3::raw("past_testee_number + 1"),
                "correct_testee_number" => Q5Section1Question3::raw("correct_testee_number + 1")
            ]);
            Q5Section1Question3::whereIn('id', $incorrectAnswer)->update([
                "past_testee_number" => Q5Section1Question3::raw("past_testee_number + 1")
            ]);
            $testeeScore = ScoreSheet::where('level',5)->where('section',1)->where('question',3)->where('number_of_correct',$scoring)->first()->score;
            $percentageScore = round($testeeScore * 100 / $perfectScore);
        }
        ScoreSummary::where('examinee_number',substr($userID, 1))->update([
            's1_q3_correct' => $testeeScore,
            's1_q3_perfect_score' => $perfectScore,
            's1_q3_perfect_score' => $percentageScore,
            's1_q3_anchor_pass' => $anchorFlagResult]);

        foreach(Session::get($userID) as $key => $obj)
            {
                if (str_starts_with($key,'Q5S1Q3'))
                {
                    if ($key !== 'Q5S1Q3Score' )
                    {
                        $afterSubmitSession = $userID.'.'.$key;
    
                        Session::forget($afterSubmitSession);
                    }
                }
            }
        $scoreQ5S1Q3 = $testeeScore;
        // Session::put('Q5S1Q1Score',$scoreQ5S1Q1);    
        // Session::put('Q5S1Q2Score',$scoreQ5S1Q2);    
        Session::put($userID.'.Q5S1Q3Score', $scoreQ5S1Q3);
        // Session::put('idTester',$userID);    

        error_log($scoreQ5S1Q3);
        return Redirect::to(url('/Q5VocabularyQ4'));

    }

   function fetchData(Request $request)
   {
    $currentId = Session::get('idTester');
    $section1Question3Id = $currentId.".Q5S1Q3";

    if ($request->ajax()) {
        $questionDataLoad = Session::get($section1Question3Id);
        $data = $this->paginate($questionDataLoad);

        return view('Q5\Vocabulary\paginationDataQ5S1Q3', compact('data'))->render(); //
    }
   }
   public function saveChoiceRequestPost(Request $request)
   {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        error_log($questionNumber);

        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S1Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);

   }

    public static function checkValue($questionNumber,$questionChoice){
        $currentId = Session::get('idTester');
        $section1Question3Choice = $currentId.".Q5S1Q3_".$questionNumber;

        $sess = Session::get($section1Question3Choice);
        if ($sess == $questionChoice)
            return "checked";
        else return "";
    }

    function showDataBase()
    {
        $valueArray = []; 
        $nounId = [];
        $katakanaId = [];
        $verbId = [];
        $iAdjectiveId = [];
        $naAdjectiveId = [];
        $adverbId = [];
        $anchorId = [];

        $dailyLifeId = [];
        $schoolId = [];
        $friendId = [];

        $imageQuestionId = [];
        //Save index of picture question
        $indexImageValue = -1;

        $results = Q5Section1Question3::whereNotNull('illustration')->get();
        foreach ($results as $user) {
            $value = new Q5S1Q3(
                $user->id,
                $user->question_id,
                $user->vocabulary,
                $user->part_of_speech,
                $user->past_testee_number,
                $user->correct_testee_number,
                $user->illustration,
                $user->anchor,
                $user->question,
                $user->choice_a,
                $user->choice_b,
                $user->choice_c,
                $user->choice_d,
                $user->correct_answer,
                $user->new_question
            );
            array_push($valueArray,$value);

        }
        // $questionList = [];

        // $questionImageId = $questionIdArray[$indexImageValue];

        // array_splice($questionIdArray,$indexImageValue,1);
        // array_push($questionIdArray,$questionImageId);

        // foreach($questionIdArray as $id)
        // {
        //     $idValue = static::searchForId($id, $valueArray);
        //     array_push($questionList,$valueArray[$idValue]);
        // }

        // $tempUrl =  Storage::disk('s3')->temporaryUrl('N11251030500.jpg', now()->addHours(5));

        foreach($valueArray as $key=>$elements)
        {
            $valueKey = $key;
            $elements->setQuestionId(30);
        }
        return $valueArray;
    }

    function hasDupe($array) {
        if (count($array) !== count(array_unique($array)))
            error_log("DUPLICATE");
        return         count($array) !== count(array_unique($array));
    }
    
    public function paginate($items, $perPage = 5, $page = null, $options = [])
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

    function getRandomQuestionId ($nounId,$katakanaId,$verbId,$iAdjectiveId,$naAdjectiveId,$adverbId,$anchorId,$valueArray)
    {
        $arrayAnchorId = array_rand($anchorId, 1);
        $anchorId1 = $anchorId[$arrayAnchorId];
        $idValue = static::searchForId($anchorId1, $valueArray);

        $valueAnchor = $valueArray[$idValue]->getPartOfSpeech();
        $result = [];

        $nounCounter = 3;
        $katakanaCounter = 2;
        $verbCounter = 2;
        $iAdjectiveCounter = 1;
        $naAdjectiveCounter = 1;
        $adverbCounter = 1;

        if ($valueAnchor == "010")
            $nounCounter = 2;
        else if ($valueAnchor == "020")
            $katakanaCounter = 1;
        else if ($valueAnchor == "040")
            $verbCounter = 1;
        else if ($valueAnchor == "050")
            $iAdjectiveCounter = 0;
        else if ($valueAnchor == "060")
            $naAdjectiveCounter = 0;    
        else 
            $adverbCounter  = 0;

        $nounArray = [];
        $katakanaArray = [];
        $verbArray = [];
        $iAdjectiveArray = [];
        $naAdjectiveArray = [];
        $adverbArray = [];

        $arrayNounId = array_rand($nounId, $nounCounter);
        for ($counter = 0; $counter < $nounCounter; $counter++)
            {
                $value = $nounId[$arrayNounId[$counter]];
                array_push($nounArray,$value);
            }
        
        if ($katakanaCounter == 2)
        {
            $arrayKatakanaId = array_rand($katakanaId, $katakanaCounter);
            for ($counter = 0; $counter < $katakanaCounter; $counter++)
            {
                $value = $katakanaId[$arrayKatakanaId[$counter]];
                array_push($katakanaArray,$value);
            }
        }
        else {
            $arrayKatakanaId = array_rand($katakanaId, 1);
            $katakanaValue = $katakanaId[$arrayKatakanaId];
            array_push($katakanaArray,$katakanaValue);
        }

        if ($verbCounter == 2)
        {
            $arrayVerbId = array_rand($verbId, $verbCounter);
            for ($counter = 0; $counter < $verbCounter; $counter++)
            {
                $value = $verbId[$arrayVerbId[$counter]];
                array_push($verbArray,$value);  
            }
        }
        else {
            $arrayVerbId = array_rand($verbId, 1);
            $verbValue = $verbId[$arrayVerbId];
            array_push($verbArray,$verbValue);
        }

        if($iAdjectiveCounter != 0)
        {
            $arrayIAdjectiveId = array_rand($iAdjectiveId, 1);
            $iAdjectiveValue = $iAdjectiveId[$arrayIAdjectiveId];
            array_push($iAdjectiveArray,$iAdjectiveValue);
        }

        if($naAdjectiveCounter != 0)
        {
            $arrayNaAdjectiveId = array_rand($naAdjectiveId, 1);
            $naAdjectiveValue = $naAdjectiveId[$arrayNaAdjectiveId];
            array_push($naAdjectiveArray,$naAdjectiveValue);
        }

        if($adverbArray != 0)
        {
            $arrayAdverbId = array_rand($adverbId, 1);
            $adverbValue = $adverbId[$arrayAdverbId];
            array_push($adverbArray,$adverbValue);
        }
        
        $result = array_merge($nounArray,$katakanaArray,$verbArray,$iAdjectiveArray,$naAdjectiveArray,$adverbArray);
        array_push($result,$anchorId1);
        return $result;
    }
}