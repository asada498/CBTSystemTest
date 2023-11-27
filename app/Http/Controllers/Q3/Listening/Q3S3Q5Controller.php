<?php

namespace App\Http\Controllers\Q3\Listening;

use Exception;
use App\QuestionClass\Q3\Listening\Q3S3Q5;
use App\QuestionDatabase\Q3\Listening\Q3Section3Question5;
use App\AnswerRecord;
use App\Grades;
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

class Q3S3Q5Controller extends Controller
{

    public function showQuestion()
    {
        // Session()->forget('idTester');
        // Session::put('idTester', "Q20121089050004");
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20070144050101"); // Vetnam Hanoi  LEVEL3 0101
        }
        $currentId = Session::get('idTester');
        $section3Question5Id = $currentId . ".Q3S3Q5";
        
        $questionData = $this->showDataBase();
        //dd($questionData);
        Session::put($section3Question5Id, $questionData);
        //    }

        $questionDataLoad = Session::get($section3Question5Id);
        //$data = $this->paginate($questionDataLoad);
        
        //dd($questionDataLoad);
        return view('Q3\Listening\Q3S3Q5', ['data' => $questionDataLoad]);
    }

    public function getResultToCalculate(Request $request)
    {

        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20070144050101"); // Vetnam Hanoi  LEVEL3 0101
        }
        
        $userID = Session::get('idTester');
        $section3Question5Id = $userID.".Q3S3Q5";
        $questionDataLoad = Session::get($section3Question5Id);
        Session::put($userID.".Q3S3Q5Score_anchor", 0); 
        

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getNo();
            $currentQuestion = $userID.'.Q3S3Q5_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = 'T';
            //$correctFlag = 0;
            $passFail = 0;

            if ($question->getAnchor() == "1") {
                $anchorFlag = 1;
            } else {
                $anchorFlag = 0;
            }
            if ($question->getAnswer() == $userAnswer) {
                $passFail = 1;
                if ($anchorFlag == 1)
                {
                    $anchorFlagResult = 1;
                    Session::put($userID.".Q3S3Q5Score_anchor", 6.7 / 40 * 60 / 9);
                }
                $scoring++;
                array_push($correctAnswer, $question->getId());
            } else {
                if ($question->getAnswer() == null)
                    $passFail = null;
                else {
                    $passFail = 0;
                    array_push($incorrectAnswer, $question->getId());
                }
            }
            

            //TESTING CODE ONLY. DELETE LATER.
            if (AnswerRecord::where('examinee_number', $userID)->where('level', 3)->where('section', 3)->where('question', 5)->where('number', $questionId)->exists()) {
                AnswerRecord::where('examinee_number', $userID)->where('level', 3)->where('section', 3)->where('question', 5)->where('number', $questionId)->update(
                    [
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_33_05',
                        'question_id' => $question->getQid(),
                        'anchor' => $anchorFlag,
                        'choice' => $userAnswer,
                        'correct_answer' => $question->getAnswer(),
                        'pass_fail' => $passFail,
                    ]
                );
            } else {
                AnswerRecord::insert(
                    [
                        'examinee_number' => substr($userID, 1),
                        'level' => 3,
                        'section' => 3,
                        'question' => 5,
                        'number' => $questionId,
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_33_05',
                        'question_id' => $question->getQid(),
                        'anchor' => $anchorFlag,
                        'choice' => $userAnswer,
                        'correct_answer' => $question->getAnswer(),
                        'pass_fail' => $passFail,
                    ]
                );
            }
        }
        //update record on database
        Q3Section3Question5::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q3Section3Question5::raw("past_testee_number + 1"),
            "correct_testee_number" => Q3Section3Question5::raw("correct_testee_number + 1")
        ]);
        Q3Section3Question5::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q3Section3Question5::raw("past_testee_number + 1")
        ]);
        // $perfectScore = Config::get('constants.Q4S3Q4.perfectScore');
        $rate = round($scoring * 100 / 9);
        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's3_q5_correct' => $scoring,
            's3_q5_question' => 9,
            's3_q5_perfect_score' => 6.7 / 40 * 60,
            's3_q5_anchor_pass' => $anchorFlagResult,
            's3_q5_rate' => $rate,
            's3_end_flag' => 1
        ]);

        if (!TestResult::where('examinee_id', substr($userID, 1))->where('level', 3)->exists()) {
            TestResult::insert(
                [
                    'examinee_id' => substr($userID, 1),
                    'level' => 3,
                    'acceptance' => null,
                    'grades' => 1,
                    'grades_print' => 0,
                    'grades_shipping' => 0,
                    'certificate' => 1,
                    'certificate_print' => 0,
                    'certificate_shipping' => 0,
                    'certificate_fee' => null,
                ]
            );
        }
        //manimcha mana shu joyda sessiya o'chadi
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q3S3Q5'))
            {
                if ($key !== 'Q3S3Q5Score' && $key !== 'Q3S3Q5Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }

        
        $s3Q1Correct = Session::get($userID.".Q3S3Q1Score");
        $s3Q2Correct = Session::get($userID.".Q3S3Q2Score");
        $s3Q3Correct = Session::get($userID.".Q3S3Q3Score");
        $s3Q4Correct = Session::get($userID.".Q3S3Q4Score");
        $s3Q5Correct = $scoring;
        $section3Total = $s3Q1Correct / 6 * 16.5 + $s3Q2Correct / 6 * 16.5 + $s3Q3Correct / 3 * 9 + $s3Q4Correct / 4 * 7.95 + $s3Q5Correct / 9 * 10.05;

        $anchorScoreQ3S1Q1 =  Session::get( $userID.'.Q3S1Q1Score_anchor');
        $anchorScoreQ3S1Q3 =  Session::get( $userID.'.Q3S1Q3Score_anchor');
        $anchorScoreQ3S2Q1 =  Session::get( $userID.'.Q3S2Q1Score_anchor');
        $anchorScoreQ3S3Q1 =  Session::get( $userID.'.Q3S3Q1Score_anchor');
        $anchorScoreQ3S3Q2 =  Session::get( $userID.'.Q3S3Q2Score_anchor');
        $anchorScoreQ3S3Q5 =  Session::get( $userID.'.Q3S3Q5Score_anchor');
        $currentAnchorScore = $anchorScoreQ3S1Q1+$anchorScoreQ3S1Q3+$anchorScoreQ3S2Q1+$anchorScoreQ3S3Q1+$anchorScoreQ3S3Q2+$anchorScoreQ3S3Q5;
        $currentAnchorPassRate = round($currentAnchorScore/9.53704174*100);
        Grades::where('examinee_number', substr($userID, 1))->where('level', 3)->update([
            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec3_score' => $section3Total
            ]);
        
        $scoreQ3S3Q5 = $scoring;
        // Session::put('Q4S3Q1Score',$scoreQ4S3Q1);
        Session::put($userID . '.Q3S3Q5Score', $scoreQ3S3Q5);
        Session::put('idTester', $userID);
        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 4, 'login' => 0]);

        $realUserId = substr($userID, 1);
        $query = ScoreSummary::where('examinee_number',$realUserId)->first();
        $section1Total = $query->s1_q1_correct / 8 * 5.5 / 55 * 60 +
                         $query->s1_q2_correct / 6 * 4 / 55 * 60 +
                         $query->s1_q3_correct / 11 * 7.5 / 55 * 60 +
                         $query->s1_q4_correct / 5 * 7.5 / 55 * 60 +
                         $query->s1_q5_correct / 5 * 7.5 / 55 * 60 +
                         $query->s2_q1_correct / 13 * 8.5 / 55 * 60 +
                         $query->s2_q2_correct / 5 * 6 / 55 * 60 +
                         $query->s2_q3_correct / 5 * 8.5 / 55 * 60;
        $section2Total = $query->s2_q4_correct / 4 * 12 / 45 * 60 +
                         $query->s2_q5_correct / 6 * 13 / 45 * 60 +
                         $query->s2_q6_correct / 4 * 12 / 45 * 60 +
                         $query->s2_q7_correct / 2 * 8 /  45 * 60;
        $section3Total = $query->s3_q1_correct / 6 * 11 / 40 * 60 +
                         $query->s3_q2_correct / 6 * 11 / 40 * 60 +
                         $query->s3_q3_correct / 3 * 6 / 40 * 60 +
                         $query->s3_q4_correct / 4 * 5.3 / 40 * 60 +
                         $query->s3_q5_correct / 9 * 6.7 / 40 * 60;
                         
        $totalScore = $section1Total + $section2Total + $section3Total;
        $s1Rate = $section1Total / 60;
        $s2Rate = $section2Total / 60;
        $s3Rate  = $section3Total / 60;
        $passFlag = 0 ;

        if ($s1Rate >= 0.25 && $s2Rate >= 0.25 && $s3Rate >= 0.25)
        {
            if ($totalScore >= 106)
                $passFlag = 1;
            else if ($totalScore >= 85 && $totalScore < 106)
            {
                $passRateAnchor = Grades::where('examinee_number',$realUserId)->first()->anchor_pass_rate;
                if ($passRateAnchor >= 60)
                    $passFlag = 1;
            }
        }

        ScoreSummary::where('examinee_number',$realUserId)->update([
            's3_score' => $section3Total,
            'score' => $totalScore,
            's3_rate'=> $s3Rate
        ]);
        Grades::where('examinee_number',$realUserId)->update([
            'pass_fail' => $passFlag
        ]);
        
        return Redirect::to(url('/Gradehomepage3'));
    }

    public function saveChoiceRequestPost(Request $request)
    {
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId . ".Q3S3Q5_" . $questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber, $questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question5Choice = $currentId . ".Q4S3Q5_" . $questionNumber;

        $sess = Session::get($section3Question5Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }


    function showDataBase()
    {
        $listening_class =['601','601','601','602603','602603','604','604','604','604'];
        $listening_group =['101','101','101','102','103','103','103','103','103'];

        $questionIdArray = [];

        $count = 0;
        while($count==0)
        {
            $questionIdArray = static::getRandomQuestionId($listening_class, $listening_group);
            
            $lengClass = count($listening_class);
            $lengArr = count($questionIdArray);
            
            if($lengArr==$lengClass)
            $count=1;
        }
        
        //if($lengthArr>1)dd('database information is not enough for requarement'); 
        return $questionIdArray;         
       
    }

    function getRandomQuestionId($listening_class, $listening_group){
                
        $listening_class2 =[];

        //listening classdagi ikkita nuqilay ma'lunotni to'g'irlash
        foreach($listening_class as $class)
        {                
            $len = strlen($class);
            $resultclass=0;
            if($len>3){
                $class1 = substr($class, 0,3);
                $class2 = substr($class, 3, 3);
                $arrclass = [$class1,$class2];
                shuffle($arrclass);
                $resultclass =  $arrclass[0];
            }else  $resultclass = $class;  
            array_push($listening_class2,$resultclass);
        }

        $results = Q3Section3Question5::where('usable', '1')
        ->whereBetween('correct_answer_rate', [0.20, 0.80])
        ->whereIn('listening_class', $listening_class2)
        ->whereIn('listening_group', $listening_group)
        ->get();        

        
        $array = $results->toArray();
        shuffle($array);
        $resultarray = [];
        $newQuestion = null;
        $anchor = null;

       
        array_unshift($listening_class2,'0');
        array_unshift($listening_group,'0');
        
        foreach($array as $val)
        { 
            $value = new Q3S3Q5(
                $val['id'],                
                $val['qid'],
                $val['sentence_pattern'],
                $val['correct_answer_rate'],
                $val['listening_class'],
                $val['listening_group'],
                $val['question_classification'],
                $val['past_testee_number'],  
                $val['correct_testee_number'],
                // $user->dupe,
                $val['question'],
                $val['choices'],
                $val['listening'],
                $val['silence'],
                $val['correct_answer'],
                0,
                $val['usable'],
                $val['anchor']               
                
            );            

            $index = array_search($val['listening_group'], $listening_group);
            $index2 = array_search($val['listening_class'], $listening_class2);

            
            if($index!=null and $index2!=null)
            {
                if($val['anchor']==1 and $anchor==null)
                {
                    $anchor[0] = array_search($val['listening_group'], $listening_group);
                    $anchor[1] = array_search($val['listening_class'], $listening_class2);
                    unset($listening_group[$anchor[0]]);
                    unset($listening_class2[$anchor[1]]);
                    array_push($resultarray, $value);

                    if($anchor!=null and $newQuestion!=null) break;
                    else continue;
                }
                elseif($val['new_question']==1 and $newQuestion==null)
                {
                    $newQuestion[0] = array_search($val['listening_group'], $listening_group);
                    $newQuestion[1] = array_search($val['listening_class'], $listening_class2);
                    unset($listening_group[$newQuestion[0]]);
                    unset($listening_class2[$newQuestion[1]]);
                    array_push($resultarray, $value);

                    if($anchor!=null and $newQuestion!=null) break;
                    else continue;
                }                             
            }
        }

        //anchor va newQuestion ni o'chiramiz        

        foreach($array as $val)
        { 
            $value = new Q3S3Q5(
                $val['id'],                
                $val['qid'],
                $val['sentence_pattern'],
                $val['correct_answer_rate'],
                $val['listening_class'],
                $val['listening_group'],
                $val['question_classification'],
                $val['past_testee_number'],  
                $val['correct_testee_number'],
                // $user->dupe,
                $val['question'],
                $val['choices'],
                $val['listening'],
                $val['silence'],
                $val['correct_answer'],
                0,
                $val['usable'],
                $val['anchor']               
                
            );
            // bo'ldi topdim uning qiymati 0 ga teng bo'lib qolyapti
            // ndi shu muammoni yechishim kerak
            $index = array_search($val['listening_group'], $listening_group);
            $index2 = array_search($val['listening_class'], $listening_class2);

            
            if($index!=null and $index2!=null)
            {
                if($val['anchor']==1) continue;                
                elseif($val['new_question']==1) continue;                              
                else
                {
                    //array_push($resultarray,$val);
                    array_push($resultarray, $value);
                    unset($listening_group[$index]);
                    unset($listening_class2[$index2]);
                    $lengthArr = count($listening_class2);
                    if($lengthArr==1){break;}
                }           
                                               
            }
        }
        shuffle($resultarray);

        $no = 1; //Ban File qo'shish uchun nega kerak bilmadim
        // Savollarni raqamlash ham shu yerda bo'lyapti
        
        foreach ($resultarray as $elements) {
            $elements->setBanFile($this->searchBanFile(3, 3, 5, $elements->getQid(), $no));
            $elements->setNo($no++);
        }
               
        //
        return $resultarray;      
       
    }

    function hasDupeValue($array) {
        // streamline per @Felix
        return count($array) !== count(array_unique($array));
     }

    // for LEVEL3
    function searchBanFile($level, $section, $question, $qid, $no)
    {
        if($level != 3){
            throw new Exception('level 3 only. When using at another level, modify this function');
        }

        // 新問とTは整理されていないので、特殊ロジック
        if((substr($qid, 0, 1) == 'T') || (substr($qid, 0, 3) == 'NEW')){
            switch($no){
            case 1:
                return Config::get('constants.Q3S3.no1DefaultFile');
                break;
            case 2:
                return Config::get('constants.Q3S3.no2DefaultFile');
                break;
            case 3:
                return Config::get('constants.Q3S3.no3DefaultFile');
                break;
            case 4:
                return Config::get('constants.Q3S3.no4DefaultFile');
                break;
            case 5:
                return Config::get('constants.Q3S3.no5DefaultFile');
                break;
            case 6:
                return Config::get('constants.Q3S3.no6DefaultFile');
                break;
            case 7:
                return Config::get('constants.Q3S3.no7DefaultFile');
                break;
            case 8:
                return Config::get('constants.Q3S3.no8DefaultFile');
                break;
            case 9:
                return Config::get('constants.Q3S3.no9DefaultFile');
                break;
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

        // なければ問題５から検索（一番質問数が多い問題を使うこと）
        $tableName = 'q_'.$level.$section.'_0'.$question;
        $searchQid = substr($qid, 0, 6).'05'.sprintf('%02d', $no).'00';
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
            return Config::get('constants.Q3S3.no1DefaultFile');
            break;
        case 2:
            return Config::get('constants.Q3S3.no2DefaultFile');
            break;
        case 3:
            return Config::get('constants.Q3S3.no3DefaultFile');
            break;
        case 4:
            return Config::get('constants.Q3S3.no4DefaultFile');
            break;
        case 5:
            return Config::get('constants.Q3S3.no5DefaultFile');
            break;
        case 6:
            return Config::get('constants.Q3S3.no6DefaultFile');
            break;
        case 7:
            return Config::get('constants.Q3S3.no7DefaultFile');
            break;
        case 8:
            return Config::get('constants.Q3S3.no8DefaultFile');
            break;
        case 9:
            return Config::get('constants.Q3S3.no9DefaultFile');
            break;
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

    function hasDupe($array, $dupeArray)
    {
        if (count($dupeArray) !== count(array_unique($dupeArray))) {
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

    function removeElementFromArray($del_val, $array)
    {
        // dd($del_val,$array);
        if (($key = array_search($del_val, $array)) !== false) {
            unset($array[$key]);
            return $array;
        }
    }    
}
