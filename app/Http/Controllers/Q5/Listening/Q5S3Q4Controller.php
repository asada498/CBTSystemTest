<?php
namespace App\Http\Controllers\Q5\Listening;

use Exception;
use App\QuestionClass\Q5\Listening\Q5S3Q4;
use App\QuestionDatabase\Q5\Listening\Q5Section3Question4;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSheet;
use App\ScoreSummary;
use App\TestResult;
use App\ExamineeLogin;
use Illuminate\support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Echo_;
use App\Grades;

class Q5S3Q4Controller extends Controller
{

    public function showQuestion()
    {
        // Session()->forget('idTester');
        // Session::put('idTester', "Q20121089050004");
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20121089050101"); // 2020/12/10 TOKYO LEVEL5 0004
        }
        $currentId = Session::get('idTester');
        $section3Question4Id = $currentId.".Q5S3Q4";


    //    if (!(Session::has($section3Question4Id))) {
            $questionData = $this->showDataBase();
            Session::put($section3Question4Id, $questionData);
    //    }

        $questionDataLoad = Session::get($section3Question4Id);
        $data = $this->paginate($questionDataLoad);
        return view('Q5\Listening/Q5S3Q4',compact('data'));
    }

    public function getResultToCalculate (Request $request){

        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        
        if (!(Session::has('idTester'))){
            $userIDTemp = "Q20121089050101";
            Session::put('idTester',$userIDTemp);
        }
        $userID = Session::get('idTester');
        $section3Question4Id = $userID.".Q5S3Q4";
        $questionDataLoad = Session::get($section3Question4Id);
        Session::put($userID.".Q5S3Q4Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getNo();
            $currentQuestion = $userID.'.Q5S3Q4_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = 'T';
            //$correctFlag = 0;
            $passFail = 0;

            if($question->getAnchor() == "1"){
                $anchorFlag = 1;
            }else 
            {$anchorFlag = 0;
            }
            if ($question->getAnswer() == $userAnswer)
            {
                $passFail = 1;
                if ($anchorFlag == 1)
                {
                    $anchorFlagResult = 1;
                    Session::put($userID.".Q5S3Q4Score_anchor", 6 / 30 * 60 / 6);
                }
                $scoring++;
                array_push($correctAnswer,$question->getId());
            }
            else 
            {
                if ($question->getAnswer() == null)
                    $passFail = null;
                else 
                    {   $passFail = 0;
                        array_push($incorrectAnswer,$question->getId());
                    }
            }
            
            //TESTING CODE ONLY. DELETE LATER.
            if (AnswerRecord::where('examinee_number',$userID)->where('level',5)->where('section',3)->where('question',4)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',$userID)->where('level',5)->where('section',3)->where('question',4)->where('number',$questionId)->update(
                    [
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_53_04',
                     'question_id'=>$question->getQid(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getAnswer(),
                     'pass_fail'=>$passFail,
                    ]
                );
            }
            else 
            {
                AnswerRecord::insert(
                    ['examinee_number' => substr($userID, 1), 
                     'level' => 5,
                     'section'=> 3,
                     'question' => 4,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_53_04',
                     'question_id'=>$question->getQid(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getAnswer(),
                     'pass_fail'=>$passFail,
                     ]
                );
            }
        }
        //update record on database
        
        Q5Section3Question4::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section3Question4::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section3Question4::raw("correct_testee_number + 1")
        ]);
        Q5Section3Question4::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section3Question4::raw("past_testee_number + 1")
        ]);

        // $perfectScore = Config::get('constants.Q5S3Q4.perfectScore');
        $rate = round($scoring * 100 / 6);
        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's3_q4_correct' => $scoring,
            's3_q4_question' => 6,
            's3_q4_perfect_score' => 6 / 30 * 60,
            's3_q4_anchor_pass' => $anchorFlagResult,
            's3_q4_rate' => $rate,
            's3_end_flag' => 1
        ]);
        
        if (!TestResult::where('examinee_id',substr($userID, 1))->where('level',5)->exists()){
            TestResult::insert(
                ['examinee_id' => substr($userID, 1), 
                'level' => 5,
                'acceptance'=> null,
                'grades' => 1,
                'grades_print'=> 0,
                'grades_shipping'=> 0,
                'certificate' => 1,
                'certificate_print'=> 0,
                'certificate_shipping'=> 0,
                'certificate_fee' => null, 
                ]
            );
        }
        
        $s3Q1Correct = Session::get($userID.".Q5S3Q1Score");
        $s3Q2Correct = Session::get($userID.".Q5S3Q2Score");
        $s3Q3Correct = Session::get($userID.".Q5S3Q3Score");
        $s3Q4Correct = $scoring;
        $section3Total = $s3Q1Correct / 7 * 9 / 30 * 60 +
                         $s3Q2Correct / 6 * 9 / 30 * 60 +
                         $s3Q3Correct / 5 * 6 / 30 * 60 +
                         $s3Q4Correct / 6 * 6 / 30 * 60;

        $anchorScoreQ5S1Q1 =  Session::get( $userID.'.Q5S1Q1Score_anchor');
        $anchorScoreQ5S1Q3 =  Session::get( $userID.'.Q5S1Q3Score_anchor');
        $anchorScoreQ5S2Q1 =  Session::get( $userID.'.Q5S2Q1Score_anchor');
        $anchorScoreQ5S3Q1 =  Session::get( $userID.'.Q5S3Q1Score_anchor');
        $anchorScoreQ5S3Q2 =  Session::get( $userID.'.Q5S3Q2Score_anchor');
        $anchorScoreQ5S3Q4 =  Session::get( $userID.'.Q5S3Q4Score_anchor');

        $currentAnchorScore = $anchorScoreQ5S1Q1+$anchorScoreQ5S1Q3+$anchorScoreQ5S2Q1+$anchorScoreQ5S3Q1+$anchorScoreQ5S3Q2+$anchorScoreQ5S3Q4;
        $currentAnchorPassRate = round($currentAnchorScore /
                                        (5.5 / 60 * 120 / 7 +
                                           5 / 60 * 120 / 6 +
                                           8 / 60 * 120 / 9 * 2 +
                                           9 / 30 * 60 / 7 +
                                           9 / 30 * 60 / 6 +
                                           6 / 30 * 60 / 6)
                                        * 100 );
        Grades::where('examinee_number', substr($userID, 1))->where('level', 5)->update([
            'anchor_soten' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec3_soten' => $section3Total
            ]);

        $scoreQ5S3Q4 = Session::get('Q5S3Q4Score');
        $request->session()->flush();
        $scoreQ5S3Q4 = $scoring;
        // Session::put('Q5S3Q1Score',$scoreQ5S3Q1);
        Session::put($userID.'.Q5S34Score', $scoreQ5S3Q4);
        Session::put('idTester',$userID);

        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 4, 'login' => 0]);

        $realUserId = substr($userID, 1);
        $query = ScoreSummary::where('examinee_number',$realUserId)->first();
        $section1And2Total = $query->s1_q1_correct / 7 * 5.5 / 60 * 120 +
                             $query->s1_q2_correct / 5 * 4.5 / 60 * 120 +
                             $query->s1_q3_correct / 6 * 5 / 60 * 120 +
                             $query->s1_q4_correct / 3 * 5 / 60 * 120 +
                             $query->s2_q1_correct / 9 * 8 / 60 * 120 +
                             $query->s2_q2_correct / 4 * 5.5 / 60 * 120 +
                             $query->s2_q3_correct / 4 * 7 / 60 * 120 +
                             $query->s2_q4_correct / 2 * 6 / 60 * 120 +
                             $query->s2_q5_correct / 2 * 8 / 60 * 120 +
                             $query->s2_q6_correct / 1 * 5.5 / 60 * 120;
        $section3Total = $query->s3_q1_correct / 7 * 9 / 30 * 60 +
                         $query->s3_q2_correct / 6 * 9 / 30 * 60 +
                         $query->s3_q3_correct / 5 * 6 / 30 * 60 +
                         $query->s3_q4_correct / 6 * 6 / 30 * 60;
        $totalScore = $section1And2Total + $section3Total;
        $s12Rate = $section1And2Total / 120;
        $s3Rate  = $section3Total / 60;
        Grades::where('examinee_number',substr($userID, 1))->update([
            'score3_soten' => $section3Total,
            'soten' => $totalScore
        ]);
 
        $passFlag = 0;
        // 合否判定
        $tokuten = round($this->tokuten($totalScore));
        $tokuten12 = round($tokuten * $section1And2Total / $totalScore);
        $tokuten3 = round($tokuten * $section3Total / $totalScore);

        if($tokuten >= 113){
            if(($tokuten12 >= 38) and ($tokuten3 >= 19)){
                $passFlag = 1;
            }else{
                $passFlag = 0;
            }
        }else if ($tokuten < 74) {
            $passFlag = 0;
        }else{
            if($currentAnchorPassRate >= 50){
                $tokuten12 = round($tokuten * $section1And2Total / $totalScore);
                $tokuten3 = round($tokuten * $section3Total / $totalScore);
                if(($tokuten12 >= 38) and ($tokuten3 >= 19)){
                    $tokuten = 80;
                    $passFlag = 1;
                }else{
                    $tokuten = 79;
                    $passFlag = 0;                    
                }
            }else{
                $tokuten = 79;
                $passFlag = 0;
            }
            $tokuten12 = round($tokuten * $section1And2Total / $totalScore);
            $tokuten3 = round($tokuten * $section3Total / $totalScore);
        }

        Grades::where('examinee_number',$realUserId)->update([
            'score' => $tokuten,
            'sec1_score' => $tokuten12,
            'sec2_score' => $tokuten3,
            'pass_fail' => $passFlag
        ]);

        // if ($s12Rate >= 0.25 && $s3Rate >= 0.25)
        // {
        //     if ($totalScore >= 110)
        //         $passFlag = 1;
        //     else if ($totalScore >= 80 && $totalScore < 110)
        //     {
        //         $passRateAnchor = Grades::where('examinee_number',$realUserId)->first()->anchor_pass_rate;
        //         if ($passRateAnchor >= 60)
        //             $passFlag = 1;
        //     }
        // }

        ScoreSummary::where('examinee_number',$realUserId)->update([
            'sec3_soten' => $section3Total,
            'soten' => $totalScore,
            's3_rate'=>$s3Rate
        ]);

        return Redirect::to(url('/Gradehomepage'));
    }
   
    function tokuten(float $soten)
    {
        if($soten >= 113){
            return 180 - (100 / 66) * (180 - $soten);
        }else if($soten < 74){
            return 79 - (79 / 73) * (73 - $soten);
        }else{
            return 80;
        }
    }

    function fetchData(Request $request)
    {
        if ($request->ajax()) {
            $questionDataLoad = Session::get('Q5S1Q4');
            $data = $this->paginate($questionDataLoad); 

            return view('Q5\Listening\Q5S3Q4', compact('data'))->render();//
        }
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S3Q4_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question4Choice = $currentId.".Q5S3Q4_".$questionNumber;

        $sess = Session::get($section3Question4Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }
    function showDataBase()
    {
         $valueArray = []; 
        $anchorArray=[];
        
        //group
        $vocabularyId = [];
        $phraseId = [];
        $sentenceId = [];

        //question classification
        $misConceptionId = [];//  A-misconception of meaning
        $misHearingId = [];// B-mishearing
        $selectiveMishearingId = []; //C-selective mishearing
        $misstatementId = [];//D-misstatement 
        
        $newQuestionId = [];

        
        $anchorId= [];

        $results = Q5Section3Question4::where('usable', '1') ->whereBetween('correct_answer_rate', [0.20, 0.80])->get();
        // ->select(DB::raw('MIN(dupe_col)','dupe_col'))

        foreach ($results as $user) {

            $value = new Q5S3Q4(
                $user->id,
                $user->qid,
                $user->sentence_pattern,
                $user->correct_answer_rate,
                $user->listening_class,
                $user->listening_group,
                $user->question_classification,
                $user->past_testee_number,  
                $user->correct_testee_number,
                // $user->dupe,
                $user->question,
                $user->choices,
                $user->listening,
                $user->silence,
                $user->correct_answer,
                $user->number_of_exam,
                $user->usable,
                $user->anchor
                
            );
            

            array_push($valueArray, $value);

            $group= $user->listening_class;
            $questionClassification = $user->question_classification;
            $idQuestion = $user->id;
            $anchor = $user->anchor;
            $listeningGroup= $user->listening_group;
            $newQuestionFlag = $user->new_question;
            // $dupe = $user->dupe;
            
            // echo $dupe;
            // $question = $user->dupe;
            
            // echo $question;
            $anchor = $user->anchor;
            if ($anchor == "1")
            {
                array_push($anchorId,$idQuestion);
            }
            else if ($newQuestionFlag == 1)
            {
                array_push($newQuestionId,$idQuestion);
            }
            else 
            // switch ($group) {
            //     case "010": 
            //         array_push($conventionalexpresId, $idQuestion); //010 vocabulary(conventional expressions) 1 question
            //         break;
            //     case "020":
            //         array_push($greetingId, $idQuestion); // 020 phrases- Greeting 1 question
            //         break;
            //     case "030":
            //         array_push($sentencepatternId, $idQuestion); // 030  sentence pattern 4 
            //        break;

            //     default:
            //         break;
            // }
            switch ($group) {
                
                case "601":
                    array_push($misConceptionId, $idQuestion);
                    break;
                case "602":
                case "604":
                    array_push($misHearingId, $idQuestion);
                    break;
                case "603":
                    array_push($selectiveMishearingId, $idQuestion);
                    break;  
                // case "604":
                //     array_push($misstatementId, $idQuestion);
                //     break;

              
                default:
                    break;
            }

            switch ($listeningGroup) {
                
                case "101":
                    array_push($vocabularyId, $idQuestion);
                    break;
                case "102":
                    array_push($phraseId, $idQuestion);
                    break;
                case "103":
                    array_push($sentenceId, $idQuestion);
                    break;  
                default:
                    break;
            }
        }
            // switch ($questionClassification) {
                
            //     case "A":
            //         array_push($misConceptionId, $idQuestion);
            //         break;
            //     case "B":
            //         array_push($misHearingId, $idQuestion);
            //         break;
            //     case "C":
            //         array_push($selectiveMishearingId, $idQuestion);
            //         break;  
            //     case "D":
            //         array_push($misstatementId, $idQuestion);
            //         break;

              
            //     default:
            //         break;
            // }
     
        $counter = 0;
        $questionIdArray = [];
        
        array_push($valueArray, $value);
        while ($counter == 0) {
        // $questionIdArray = static::getRandomQuestionId(
        // $conventionalexpresId,
        // $greetingId,
        // $sentencepatternId,
        //  $anchorId,
        //     );
        $questionIdArray = static::getRandomQuestionId(
            $misConceptionId,
            $misHearingId,
            $selectiveMishearingId,
            $vocabularyId,
            $phraseId,
            $sentenceId,
            // $misstatementId,
            $anchorId,
            $newQuestionId,
            $valueArray
                );
                // dd($questionIdArray);

            // $idAnchor = static::searchForId($questionIdArray[6], $valueArray);
            // // return [$misConceptionId1, $misHearingId1, $misHearingId2,   $selectiveMishearingId1, $misstatementId1, $misstatementId2, $anchorId1];

            // switch($valueArray[$idAnchor]->group){

            //     case "601": 
            //         array_splice($questionIdArray, 0,1); //010 vocabulary(conventional expressions) 1 question
            //         break;
            //     case "602":
            //     case "604": 
            //         array_splice($questionIdArray, 3,1); // 020 phrases- Greeting 1 question
            //         break;
            //     case "603":
            //         array_splice($questionIdArray, 5,1); // 030  sentence pattern 4 
            //     // case "604": 
            //     //     array_splice($questionIdArray, 4,1); //010 vocabulary(conventional expressions) 1 question
            //     //     break;
            //     default:
            //         break;

            // }

            
            shuffle($questionIdArray);


            // $misconceptionIdCounter = 0;
            // $mishearingIdCounter = 0;
            // $slectivemishearingIdCounter = 0;
            // $misStatementIdCounter = 0;
         
        

            // $answerArray = [];  
            // $dupeArray = [];
            $sentencePatternArray = [];
            $questionTextArray = [];

            foreach ($questionIdArray as $idValueInArray) {

                // if (in_array($idValueInArray, $misConceptionId))
                //     $misconceptionIdCounter++;
                // if (in_array($idValueInArray, $misHearingId))
                //     $mishearingIdCounter++;
                // if (in_array($idValueInArray, $selectiveMishearingId))
                //     $slectivemishearingIdCounter++;

                // if (in_array($idValueInArray, $misstatementId))
                //     $misStatementIdCounter++;
                

                $idValue = static::searchForId($idValueInArray, $valueArray);
                $questionText = $valueArray[$idValue]->getQuestion();
                if ($questionText !== null)
                    array_push($questionTextArray, $questionText);
                $classificationText = $valueArray[$idValue]->getpattern();
                if ($classificationText !== null)

                    array_push($sentencePatternArray, $classificationText);

                // array_push($answerArray, $valueArray[$idValue]);

          
                // array_push($dupeArray,$valueArray[$idValue]->getDupe());

            }
            // if (
            //     $misconceptionIdCounter > 0 && $mishearingIdCounter > 1 && $slectivemishearingIdCounter > 0  && $misStatementIdCounter > 1 && !($this->hasDupe($answerArray,$dupeArray))
            // ) 
            if(!($this->hasDupeValue($sentencePatternArray)) && !($this->hasDupeValue($questionTextArray)))
            $counter = 1;
        }
        // dd("123");
        $questionList = [];
        foreach ($questionIdArray as $id) {

            $idValue = static::searchForId($id, $valueArray);
            array_push($questionList, $valueArray[$idValue]);
           
        }

        $no = 1;
        // foreach ($questionList as $key => $elements) {
        //     $elements->setBanFile($this->searchBanFile(5, 3, 4, $elements->getQid(), $no));
        //     $valueKey = $key;
        //     $elements->setNo($key++);
        // }

        foreach ($questionList as $elements) {
 
            
            $elements->setBanFile($this->searchBanFile(5, 3, 4, $elements->getQid(), $no));
        // echo $this->searchBanFile(5, 3, 4, $elements->getQid(), $no);
        //      echo 'bbb';
        //     echo $elements->getBanFile();
 
//    echo $elements->getAnchor();
 
            $elements->setNo($no++);
        }
        return $questionList;
    }


    function hasDupeValue($array) {
        // streamline per @Felix
        return count($array) !== count(array_unique($array));
     }

    // for LEVEL5 
    function searchBanFile($level, $section, $question, $qid, $no)
    {
        if($level != 5){
            throw new Exception('level 5 only. When using at another level, modify this function');
        }

        // 新問とTは整理されていないので、特殊ロジック
        if((substr($qid, 0, 1) == 'T') || (substr($qid, 0, 3) == 'NEW')){
            switch($no){
            case 1:
                return Config::get('constants.Q5S3.no1DefaultFile');
                break;
            case 2:
                return Config::get('constants.Q5S3.no2DefaultFile');
                break;
            case 3:
                return Config::get('constants.Q5S3.no3DefaultFile');
                break;
            case 4:
                return Config::get('constants.Q5S3.no4DefaultFile');
                break;
            case 5:
                return Config::get('constants.Q5S3.no5DefaultFile');
                break;
            case 6:
                return Config::get('constants.Q5S3.no6DefaultFile');
                break;
            case 7:
                return Config::get('constants.Q5S3.no7DefaultFile');
                break;
            case 8:
                throw new Exception('No. error');
            case 9:
                throw new Exception('No. error');
            case 10:
                throw new Exception('No. error');
            case 11:
                throw new Exception('No. error');
            case 12:
                throw new Exception('No. error');
            case 13:
                throw new Exception('No. error');
            case 14:
                throw new Exception('No. error');
            default:
                throw new Exception('No. error');
            }
        }

        // 当該問題のフォルダから検索
        $tableName = 'q_'.$level.$section.'_0'.$question;
        $searchQid = substr($qid, 0, 8).sprintf('%02d', $no).'00';
        $records = DB::select("SELECT listening FROM ".$tableName." WHERE qid = '".$searchQid."'");
        if($records != null){
            if($records[0]->listening != ""){
                $ban = str_replace('/', '/NO_', $records[0]->listening);
                return $ban;
            }
        }

        // なければ問題１から検索（一番質問数が多い問題を使うこと）
        $tableName = 'q_'.$level.$section.'_0'.$question;
        $searchQid = substr($qid, 0, 6).'01'.sprintf('%02d', $no).'00';
        $records = DB::select("SELECT listening FROM ".$tableName." WHERE qid = '".$searchQid."'");
        if($records != null){
            if($records[0]->listening != ""){
                $ban = str_replace('/', '/NO_', $records[0]->listening);
                return $ban;
            }
        }

        // それでもなければ定数ファイルを返す
        switch($no){
        case 1:
            return Config::get('constants.Q5S3.no1DefaultFile');
            break;
        case 2:
            return Config::get('constants.Q5S3.no2DefaultFile');
            break;
        case 3:
            return Config::get('constants.Q5S3.no3DefaultFile');
            break;
        case 4:
            return Config::get('constants.Q5S3.no4DefaultFile');
            break;
        case 5:
            return Config::get('constants.Q5S3.no5DefaultFile');
            break;
        case 6:
            return Config::get('constants.Q5S3.no6DefaultFile');
            break;
        case 7:
            return Config::get('constants.Q5S3.no7DefaultFile');
            break;
        case 8:
            throw new Exception('No. error');
        case 9:
            throw new Exception('No. error');
        case 10:
            throw new Exception('No. error');
        case 11:
            throw new Exception('No. error');
        case 12:
            throw new Exception('No. error');
        case 13:
            throw new Exception('No. error');
        case 14:
            throw new Exception('No. error');
        default:
            throw new Exception('No. error');
        }
    }

    function hasDupe($array,$dupeArray)
    {
        // {
        //     if (count($array) !== count(array_unique($array)))
        //         error_log("DUPLICATE");
        //     return         count($array) !== count(array_unique($array));
        // }


 if (count($dupeArray) !== count(array_unique($dupeArray))){
    return true;
}
 }

     public function paginate($items, $perPage = 6, $page = null, $options = [])
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

    function removeElementFromArray($del_val,$array){
        // dd($del_val,$array);
        if (($key = array_search($del_val, $array)) !== false) {
            unset($array[$key]);
            return $array;
        }
    }

    function getRandomQuestionId($misConceptionId,$misHearingId,$selectiveMishearingId,$vocabularyId,$phraseId,$sentenceId,$anchorId,$newQuestionId,$valueArray)
    {
        $counterLoop = 0;
        $result = [];

        while ($counterLoop == 0)
        {
        $valueAnchorListeningGroup = "0";
        $valueAnchorListeningClass = "0";
        $valueNewQuestionListeningGroup = "0";
        $valueNewQuestionListeningClass = "0";
        $anchorId1 = 0;
        $newQuestionId1 = 0;
        
        if (!empty($anchorId))
        {
            $arrayAnchorId = array_rand($anchorId, 1);
            $anchorId1 = $anchorId[$arrayAnchorId];
            // dd($anchorId1);

            foreach ($valueArray as $val) {
                if ($val->getId() === $anchorId1) {
                    $valueAnchorListeningClass = $val->getListeningClass();
                    $valueAnchorListeningGroup = $val->getListeningGroup();           
                    if ($valueAnchorListeningClass == "602" || $valueAnchorListeningClass == "604" ){
                        $valueAnchorListeningClass = "602604";
                    }
                }
            }
        }

        if (!empty($newQuestionId))
        {
            $arrayNewQuestionId = array_rand($newQuestionId, 1);
            $newQuestionId1 = $newQuestionId[$arrayNewQuestionId];
            foreach ($valueArray as $val) {
                if ($val->getId() === $newQuestionId1) {
                    $valueNewQuestionListeningClass = $val->getListeningClass();
                    $valueNewQuestionListeningGroup = $val->getListeningGroup();           
                    if ($valueAnchorListeningClass == "602" || $valueAnchorListeningClass == "604" ){
                        $valueAnchorListeningClass = "602604";
                    }
                }
            }
        }
        // $tempArr = ["101","102","104","105"];
        // $temVal = $tempArr[array_rand($tempArr)];
        $listeningClassArray = ["601","601","601","603","602604","602604"];
        $listeningGroupArray = ["101","101","102","103","103","103"];
        // dd($valueAnchorListeningGroup,$valueAnchorListeningGroup,$valueNewQuestionListeningClass,$valueNewQuestionListeningGroup);
        $listeningGroupArray = static::removeElementFromArray($valueAnchorListeningGroup,$listeningGroupArray);
        if (array_search($valueNewQuestionListeningGroup, $listeningGroupArray)!== false || $valueNewQuestionListeningGroup == 0 )
        {   
            if ($valueNewQuestionListeningGroup != 0)
                $listeningGroupArray = static::removeElementFromArray($valueNewQuestionListeningGroup,$listeningGroupArray);
            $listeningClassArray = static::removeElementFromArray($valueAnchorListeningClass,$listeningClassArray);

            if (array_search($valueNewQuestionListeningClass, $listeningClassArray) !== false|| $valueNewQuestionListeningClass == 0)
            {
                if ($valueNewQuestionListeningClass != 0)
                    $listeningClassArray = static::removeElementFromArray($valueNewQuestionListeningClass,$listeningClassArray);
                // dd($listeningClassArray,$listeningGroupArray);
                $result1 = [];
                shuffle($listeningClassArray);
                shuffle($listeningGroupArray);
                $pairArray = [];
                $lengthSearch = count($listeningGroupArray);
                for ($x = 0; $x < $lengthSearch; $x++) {
                    $element1 = array_pop($listeningGroupArray)   ;
                    $element2 = array_pop($listeningClassArray)   ;
                    array_push($pairArray,[$element1,$element2]);
                }
                $lengthPairArray = count($pairArray);
                for ($x = 0; $x < $lengthPairArray; $x++) {
                    $listeningClassId = $pairArray[$x][1];
                    $listeningGroupId = $pairArray[$x][0];
                    $groupArrayChoice = [];
                    $listeningGroupChoice = [];
                    switch ($listeningClassId) {
                        case "601":
                            $groupArrayChoice = $misConceptionId;
                            break;
        
                        case "603":
                            $groupArrayChoice = $misHearingId;
                            break;
        
                        case "602604":
                            $groupArrayChoice = $selectiveMishearingId;
                            break;
                    }

                    switch ($listeningGroupId){
                        case "101":
                            $listeningGroupChoice = $vocabularyId;
                            break;
        
                        case "102":
                            $listeningGroupChoice = $phraseId;
                            break;
        
                        case "103":
                            $listeningGroupChoice = $sentenceId;
                            break;
                    }
                    $arrayVal = array_intersect($groupArrayChoice, $listeningGroupChoice);
                    if (!empty($arrayVal))
                    {
                        $val = $arrayVal[array_rand($arrayVal)];
                        array_push($result1,$val);
                    }
                    else {
                        // dd($groupArrayChoice,$listeningGroupChoice);
                    }

                } 
                if ($anchorId1 != 0)
                    array_push($result1,$anchorId1);
                if ($newQuestionId1 != 0)    
                    array_push($result1,$newQuestionId1);
                if (count($result1) == 6)
                {
                    $result = $result1;
                    $counterLoop = 1;
                }

            }
        }
        }
        return $result;   
    }
}