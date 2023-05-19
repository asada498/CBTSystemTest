<?php
namespace App\Http\Controllers\Q2\Listening;

use Exception;
use App\ScoreSheet;
use App\AnswerRecord;
use App\Grades;
use App\QuestionType;
use App\ScoreSummary;
use App\TestResult;
use App\TestInformation;
use App\ExamineeLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\QuestionClass\Q2\Listening\Q2S3Q5;
use App\QuestionDatabase\Q2\Listening\Q2Section3Question5;

class Q2S3Q5Controller extends Controller
{
    private $GROUP_CODE_PRICE    = '020'; // 値段
    private $GROUP_CODE_QUANTITY = '021'; // 人数・数量
    private $GROUP_CODE_NUMBER   = '022'; // 番号 (電話番号、ページなど)
    private $GROUP_CODE_ORDER    = '040'; // 順番
    private $GROUP_CODE_MOTION   = '050'; // 動作・行為
    private $GROUP_CODE_MULTIPLE = '060'; // 選択・複数
    private $GROUP_CODE_SINGULAR = '061'; // 選択・単数
    private $GROUP_CODE_PURPOSE  = '080'; // 目的
    private $GROUP_CODE_FEATURE  = '090'; // 人や物の特徴・様子
    private $GROUP_CODE_SCHEDULE = '101'; // 日程、時間
    private $GROUP_CODE_GUIDANCE = '102'; // 道案内・地図
    private $GROUP_CODE_REASON   = '103'; // 理由・原因
    private $GROUP_CODE_PLACE    = '104'; // 場所・(体などの)部位
    private $GROUP_CODE_METHOD   = '105'; // 方法・手段
    private $GROUP_CODE_WHEATHER = '106'; // 天気
    private $GROUP_CODE_INTEG    = '401'; // 統合

    public function showQuestion (){

        // DUMMY ID FOR TEST
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20121089021010");
        }
        $currentId = Session::get('idTester');
        $section3Question5Id = $currentId.".Q2S3Q5";
        // if (!(Session::has($section3Question1Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put($section3Question5Id, $questionData);
        // }
        
        return view('Q2\Listening\Q2S3Q5', ['data' => $questionData]);
    }

    public function getResultToCalculate(Request $request)
    {
        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;

        if (!(Session::has('idTester'))) {
            $userIDTemp = "Q20121089021010";
            Session::put('idTester', $userIDTemp);
        }
        $userID = Session::get('idTester');
        $section3Question4Id = $userID.".Q2S3Q5";
        $questionDataLoad = Session::get($section3Question4Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getNo();
            $currentQuestion = $userID.'.Q2S3Q5_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = 'U';
            //$correctFlag = 0;
            $passFail = 0;

            if ($question->getCorrectAnswer() == $userAnswer) {
                $passFail = 1;
                $scoring++;
                array_push($correctAnswer, $question->getId());
            } else {
                if ($question->getCorrectAnswer() == null)
                    $passFail = null;
                else {
                    $passFail = 0;
                    array_push($incorrectAnswer, $question->getId());
                }
            }

            //TESTING CODE ONLY. DELETE LATER.
            if (AnswerRecord::where('examinee_number', $userID)->where('level', 2)->where('section', 3)->where('question', 5)->where('number', $questionId)->exists()) {
                AnswerRecord::where('examinee_number', $userID)->where('level', 2)->where('section', 3)->where('question', 5)->where('number', $questionId)->update(
                    [
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_23_05',
                        'question_id' => $question->getQid(),
                        'anchor' => 0,
                        'choice' => $userAnswer,
                        'correct_answer' => $question->getCorrectAnswer(),
                        'pass_fail' => $passFail,
                    ]
                );
            } else {
                AnswerRecord::insert(
                    [
                        'examinee_number' => substr($userID, 1),
                        'level' => 2,
                        'section' => 3,
                        'question' => 5,
                        'number' => $questionId,
                        'question_type' => $codeQuestion,
                        'question_table_name' => 'q_23_05',
                        'question_id' => $question->getQid(),
                        'anchor' => 0,
                        'choice' => $userAnswer,
                        'correct_answer' => $question->getCorrectAnswer(),
                        'pass_fail' => $passFail,
                    ]
                );
            }
        }
        // update record on database
        Q2Section3Question5::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q2Section3Question5::raw("past_testee_number + 1"),
            "correct_testee_number" => Q2Section3Question5::raw("correct_testee_number + 1")
        ]);
        Q2Section3Question5::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q2Section3Question5::raw("past_testee_number + 1")
        ]);

        $rate = round($scoring * 100 / 4);
        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's3_q5_correct' => $scoring,
            's3_q5_question' => 4,
            's3_q5_perfect_score' => 10.8,
            's3_q5_anchor_pass' => 0,
            's3_q5_rate' => $rate,
            's3_end_flag' => 1
        ]);
        if (!TestResult::where('examinee_id', substr($userID, 1))->where('level', 2)->exists()) {
            TestResult::insert(
                [
                    'examinee_id' => substr($userID, 1),
                    'level' => 2,
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
        foreach(Session::get($userID) as $key => $obj)
        {
            if (str_starts_with($key,'Q2S3Q5'))
            {
                if ($key !== 'Q2S3Q5Score' && $key !== 'Q2S3Q5Score_anchor' )
                {
                    $afterSubmitSession = $userID.'.'.$key;

                    Session::forget($afterSubmitSession);
                }
            }
        }

        $s3Q1Correct = Session::get($userID.".Q2S3Q1Score");
        $s3Q2Correct = Session::get($userID.".Q2S3Q2Score");
        $s3Q3Correct = Session::get($userID.".Q2S3Q3Score");
        $s3Q4Correct = Session::get($userID.".Q2S3Q4Score");
        $s3Q5Correct = $scoring;
        $section3Total = $s3Q1Correct *10/50*60/5 + $s3Q2Correct *11.5/50*60/6 + $s3Q3Correct *10/50*60/5 +
                         $s3Q4Correct *9.5/50*60/12 + $s3Q5Correct *9/50*60/4;

        $anchorScoreQ2S1Q2 =  Session::get( $userID.'.Q2S1Q2Score_anchor');
        $anchorScoreQ2S1Q4 =  Session::get( $userID.'.Q2S1Q4Score_anchor');
        $anchorScoreQ2S1Q7 =  Session::get( $userID.'.Q2S1Q7Score_anchor');
        $anchorScoreQ2S3Q1 =  Session::get( $userID.'.Q2S3Q1Score_anchor');
        $anchorScoreQ2S3Q2 =  Session::get( $userID.'.Q2S3Q2Score_anchor');
        $anchorScoreQ2S3Q4 =  Session::get( $userID.'.Q2S3Q4Score_anchor');
        $currentAnchorScore = $anchorScoreQ2S1Q2 + 
                              $anchorScoreQ2S1Q4 + 
                              $anchorScoreQ2S1Q7 + 
                              $anchorScoreQ2S3Q1 + 
                              $anchorScoreQ2S3Q2 + 
                              $anchorScoreQ2S3Q4;

        $currentAnchorPassRate = round($currentAnchorScore / 8.313492063 * 100);

        Grades::where('examinee_number', substr($userID, 1))->where('level', 2)->update([
            'anchor_score' => $currentAnchorScore,
            'anchor_pass_rate' => $currentAnchorPassRate,
            'sec3_score' => $section3Total
            ]);
        
        $scoreQ2S3Q5 = $scoring;
        // Session::put('Q4S3Q1Score',$scoreQ4S3Q1);
        Session::put($userID . '.Q2S3Q5Score', $scoreQ2S3Q5);
        Session::put('idTester', $userID);
        ExamineeLogin::where('examinee_id', substr($userID, 1))->update(['progress' => 4, 'login' => 0]);

        $realUserId = substr($userID, 1);
        $query = ScoreSummary::where('examinee_number',$realUserId)->first();
        $section1And2Total = $query->s1_q1_correct *3.5/45*60/5 +
                             $query->s1_q2_correct *3.5/45*60/5 +
                             $query->s1_q3_correct *4/45*60/5 +
                             $query->s1_q4_correct *5/45*60/7 +
                             $query->s1_q5_correct *5/45*60/5 +
                             $query->s1_q6_correct *6/45*60/5 +
                             $query->s1_q7_correct *7/45*60/12 +
                             $query->s1_q8_correct *5/45*60/5 +
                             $query->s1_q9_correct *6/45*60/5 +
                             $query->s1_q10_correct *13/60*60/5 +
                             $query->s1_q11_correct *18/60*60/9 +
                             $query->s1_q12_correct *10.5/60*60/2 +
                             $query->s1_q13_correct *12/60*60/3 +
                             $query->s1_q14_correct *6.5/60*60/2;
        $section3Total = $query->s3_q1_correct *10/50*60/5 +
                         $query->s3_q2_correct *11.5/50*60/6 +
                         $query->s3_q3_correct *10/50*60/5 +
                         $query->s3_q4_correct *9.5/50*60/12 +
                         $query->s3_q5_correct *9/50*60/4;
        $totalScore = $section1And2Total + $section3Total;

        $s12Rate = $section1And2Total / 120;
        $s3Rate  = $section3Total / 60;
        $passFlag = 0 ;
        // if ($s1Rate >= 0.25 && $s2Rate >= 0.25 && $s3Rate >= 0.25)
        if ($s12Rate >= 0.25 && $s3Rate >= 0.25)
        {
//todo 合否閾値
            if ($totalScore >= 110)
                $passFlag = 1;
            else if ($totalScore >= 85 && $totalScore < 110)
            {
                $passRateAnchor = Grades::where('examinee_number',$realUserId)->first()->anchor_pass_rate;
                if ($passRateAnchor >= 60)
                    $passFlag = 1;
            }
        }

        // $testInformationQuery = TestInformation::where('examinee_id',$realUserId)->first();
        // $userInformationQuery = ExamineeInformation::where('examinee_id',$realUserId)->first();
        // $testDay = $testInformationQuery->test_day;
        // $testDayString = explode("-",$testDay);
        // $testDayModified = $testDayString[0]."年　".$testDayString[1]."月　".$testDayString[2]."日";
        // $testSiteCode = $testInformationQuery->test_site;
        // $testSiteCity = TestSiteInformation::where('test_site',$testSiteCode)->first()->city;
        ScoreSummary::where('examinee_number',$realUserId)->update([
            's3_score' => $section3Total,
            'score' => $totalScore,
            's3_rate'=> $s3Rate
        ]);
        Grades::where('examinee_number',$realUserId)->update([
            'pass_fail' => $passFlag
        ]);
        // $testeeInformation = [
        //     "id" => $realUserId,
        //     "name"=> $userInformationQuery->name,
        //     "date" => $testDayModified,
        //     "place"=> $testSiteCity
        // ];
        
        return Redirect::to(url('/Gradehomepage2'));
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q2S3Q5_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question5Choice = $currentId.".Q2S3Q5_".$questionNumber;

        $sess = Session::get($section3Question5Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        // グループを取得する
        $groupMap = [];
        $results = DB::select("select distinct group1 from q_23_05 where usable = 1 and correct_answer_rate between 0.2 and 0.8");
        foreach ($results as $rec) {
            $groupMap[$rec->group1] = 1;
        }

        $id1 = 0;
        $id2 = 0;
        $id3 = 0;
        $id4 = 0;
        $flag401 = false;
        // 新問の取得
        $results = DB::select("select id, group1 from q_23_05 where new_question = 1 and usable = 1 and correct_answer_rate between 0.2 and 0.8 limit 1");
        if($results != null){
            unset($groupMap[$results[0]->group1]);
            if($results[0]->group1 == $this->GROUP_CODE_INTEG){
                //新問が統合
                $id3 = $results[0]->id;
                $flag401 = true;
            }else{
                $id1 = $results[0]->id;
            }
        }

        // 問題を取得しMAPとARRAYに振り分け
        $questionMap = [];
        $priceArray = [];
        $quantityArray = [];
        $numberArray = [];
        $orderArray = [];
        $motionArray = [];
        $multipleArray = [];
        $singularArray = [];
        $purposeArray = [];
        $featureArray = [];
        $scheduleArray = [];
        $guidanceArray = [];
        $reasonArray = [];
        $placeArray = [];
        $methodArray = [];
        $weatherArray = [];
        $integArray = [];
        $integ0Array = [];
        $results = Q2Section3Question5::where('usable', '1')->whereBetween('correct_answer_rate', [0.20, 0.80])->get();
        foreach ($results as $rec) {
            $question = new Q2S3Q5($rec->id, $rec->qid, $rec->group1, $rec->group2, $rec->group3,
                        $rec->past_testee_number, $rec->correct_testee_number, 
                        $rec->choice_a, $rec->choice_b, $rec->choice_c, $rec->choice_d,
                        $rec->listening, $rec->correct_answer, $rec->silence, $rec->new_question, $rec->same_passage);
            $questionMap[$rec->id] = $question;
            switch($rec->group1){
            case $this->GROUP_CODE_PRICE:    // 値段
                array_push($priceArray, $rec->id);
                break;
            case $this->GROUP_CODE_QUANTITY: // 人数・数量
                array_push($quantityArray, $rec->id);
                break;
            case $this->GROUP_CODE_NUMBER:   // 番号 (電話番号、ページなど)
                array_push($numberArray, $rec->id);
                break;
            case $this->GROUP_CODE_ORDER:    // 順番
                array_push($orderArray, $rec->id);
                break;
            case $this->GROUP_CODE_MOTION:   // 動作・行為
                array_push($motionArray, $rec->id);
                break;
            case $this->GROUP_CODE_MULTIPLE: // 選択・複数
                array_push($multipleArray, $rec->id);
                break;
            case $this->GROUP_CODE_SINGULAR: // 選択・単数
                array_push($singularArray, $rec->id);
                break;
            case $this->GROUP_CODE_PURPOSE:  // 目的
                array_push($purposeArray, $rec->id);
                break;
            case $this->GROUP_CODE_FEATURE:  // 人や物の特徴・様子
                array_push($featureArray, $rec->id);
                break;
            case $this->GROUP_CODE_SCHEDULE: // 日程、時間
                array_push($scheduleArray, $rec->id);
                break;
            case $this->GROUP_CODE_GUIDANCE: // 道案内・地図
                array_push($guidanceArray, $rec->id);
                break;
            case $this->GROUP_CODE_REASON:   // 理由・原因
                array_push($reasonArray, $rec->id);
                break;
            case $this->GROUP_CODE_PLACE:    // 場所・(体などの)部位
                array_push($placeArray, $rec->id);
                break;
            case $this->GROUP_CODE_METHOD:   // 方法・手段
                array_push($methodArray, $rec->id);
                break;
            case $this->GROUP_CODE_WHEATHER: // 天気
                array_push($weatherArray, $rec->id);
                break;
            case $this->GROUP_CODE_INTEG:    // 統合
                if($rec->same_passage == 0){
                    array_push($integ0Array, $rec->id);
                }else{
                    array_push($integArray, $rec->id);
                }
                break;
            }
        }

        // 統合から２問抽出
        if(!$flag401){
            if(count($integ0Array) < 2){
                // 問題１，２では問題数不足なので問題３で出題する
                $id3 = $integArray[array_rand($integArray, 1)];
            }else{
                if(rand(0,1) == 0){
                    // 問題１，２を統合から出題
                    while(true){
                        $id1 = $integ0Array[array_rand($integ0Array, 1)];
                        $id2 = $integ0Array[array_rand($integ0Array, 1)];
                        if($id1 != $id2)
                            break;
                    }
                }else{
                    $id3 = $integArray[array_rand($integArray, 1)];
                }
            }
            unset($groupMap[$this->GROUP_CODE_INTEG]);
            $flag401 = true;
        }

        // １番が抽出されていなかったら、抽出する
        if($id1 == 0){
            $group = null;
            while(true){
                $groups = array_keys($groupMap);
                $group = $groups[array_rand($groups, 1)];
                switch($group){
                case $this->GROUP_CODE_PRICE:    // 値段
                    $id1 = $priceArray[array_rand($priceArray, 1)];
                    break;
                case $this->GROUP_CODE_QUANTITY: // 人数・数量
                    $id1 = $quantityArray[array_rand($quantityArray, 1)];
                    break;
                case $this->GROUP_CODE_NUMBER:   // 番号 (電話番号、ページなど)
                    $id1 = $numberArray[array_rand($numberArray, 1)];
                    break;
                case $this->GROUP_CODE_ORDER:    // 順番
                    $id1 = $orderArray[array_rand($orderArray, 1)];
                    break;
                case $this->GROUP_CODE_MOTION:   // 動作・行為
                    $id1 = $motionArray[array_rand($motionArray, 1)];
                    break;
                case $this->GROUP_CODE_MULTIPLE: // 選択・複数
                    $id1 = $multipleArray[array_rand($multipleArray, 1)];
                    break;
                case $this->GROUP_CODE_SINGULAR: // 選択・単数
                    $id1 = $singularArray[array_rand($singularArray, 1)];
                    break;
                case $this->GROUP_CODE_PURPOSE:  // 目的
                    $id1 = $purposeArray[array_rand($purposeArray, 1)];
                    break;
                case $this->GROUP_CODE_FEATURE:  // 人や物の特徴・様子
                    $id1 = $featureArray[array_rand($featureArray, 1)];
                    break;
                case $this->GROUP_CODE_SCHEDULE: // 日程、時間
                    $id1 = $scheduleArray[array_rand($scheduleArray, 1)];
                    break;
                case $this->GROUP_CODE_GUIDANCE: // 道案内・地図
                    $id1 = $guidanceArray[array_rand($guidanceArray, 1)];
                    break;
                case $this->GROUP_CODE_REASON:   // 理由・原因
                    $id1 = $reasonArray[array_rand($reasonArray, 1)];
                    break;
                case $this->GROUP_CODE_PLACE:    // 場所・(体などの)部位
                    $id1 = $placeArray[array_rand($placeArray, 1)];
                    break;
                case $this->GROUP_CODE_METHOD:   // 方法・手段
                    $id1 = $methodArray[array_rand($methodArray, 1)];
                    break;
                case $this->GROUP_CODE_WHEATHER: // 天気
                    $id1 = $weatherArray[array_rand($weatherArray, 1)];
                    break;
                case $this->GROUP_CODE_INTEG:    // 統合
                    $id1 = $integ0Array[array_rand($integ0Array, 1)];
                    break;
                }
                if($questionMap[$id1]->same_passage == 0)
                    break;
            }
            unset($groupMap[$group]);
        }

        // ２番が抽出されていなかったら、抽出する
        if($id2 == 0){
            while(true){
                $groups = array_keys($groupMap);
                $group = null;
                $group = $groups[array_rand($groups, 1)];
                switch($group){
                case $this->GROUP_CODE_PRICE:    // 値段
                    $id2 = $priceArray[array_rand($priceArray, 1)];
                    break;
                case $this->GROUP_CODE_QUANTITY: // 人数・数量
                    $id2 = $quantityArray[array_rand($quantityArray, 1)];
                    break;
                case $this->GROUP_CODE_NUMBER:   // 番号 (電話番号、ページなど)
                    $id2 = $numberArray[array_rand($numberArray, 1)];
                    break;
                case $this->GROUP_CODE_ORDER:    // 順番
                    $id2 = $orderArray[array_rand($orderArray, 1)];
                    break;
                case $this->GROUP_CODE_MOTION:   // 動作・行為
                    $id2 = $motionArray[array_rand($motionArray, 1)];
                    break;
                case $this->GROUP_CODE_MULTIPLE: // 選択・複数
                    $id2 = $multipleArray[array_rand($multipleArray, 1)];
                    break;
                case $this->GROUP_CODE_SINGULAR: // 選択・単数
                    $id2 = $singularArray[array_rand($singularArray, 1)];
                    break;
                case $this->GROUP_CODE_PURPOSE:  // 目的
                    $id2 = $purposeArray[array_rand($purposeArray, 1)];
                    break;
                case $this->GROUP_CODE_FEATURE:  // 人や物の特徴・様子
                    $id2 = $featureArray[array_rand($featureArray, 1)];
                    break;
                case $this->GROUP_CODE_SCHEDULE: // 日程、時間
                    $id2 = $scheduleArray[array_rand($scheduleArray, 1)];
                    break;
                case $this->GROUP_CODE_GUIDANCE: // 道案内・地図
                    $id2 = $guidanceArray[array_rand($guidanceArray, 1)];
                    break;
                case $this->GROUP_CODE_REASON:   // 理由・原因
                    $id2 = $reasonArray[array_rand($reasonArray, 1)];
                    break;
                case $this->GROUP_CODE_PLACE:    // 場所・(体などの)部位
                    $id2 = $placeArray[array_rand($placeArray, 1)];
                    break;
                case $this->GROUP_CODE_METHOD:   // 方法・手段
                    $id2 = $methodArray[array_rand($methodArray, 1)];
                    break;
                case $this->GROUP_CODE_WHEATHER: // 天気
                    $id2 = $weatherArray[array_rand($weatherArray, 1)];
                    break;
                case $this->GROUP_CODE_INTEG:    // 統合
                    $id2 = $integ0Array[array_rand($integArray, 1)];
                    break;
                }
                if($questionMap[$id2]->same_passage == 0)
                    break;
            }
            unset($groupMap[$group]);
        }

        // ３番が抽出されていなかったら、抽出する
        if($id3 == 0){
            // same_passageが同じid3,id4を取得する
            $sameArray = [];
            $results = DB::select("SELECT same_passage, count(*) as cnt FROM q_23_05 where usable = 1 and correct_answer_rate between 0.2 and 0.8 and same_passage <> 0 group by same_passage having count(*) = 2");
            foreach ($results as $rec) {
                array_push($sameArray, $rec->same_passage);
            }
            $same = $sameArray[array_rand($sameArray, 1)];
            $param = ['same' => $same];
            $results = DB::select("SELECT id FROM q_23_05 where same_passage = :same order by qid", $param);
            $id3 = $results[0]->id;
            $id4 = $results[1]->id;
        }else{
            // id3と同じsame_passageのid4を取得する
            $param = ['same' => $questionMap[$id3]->same_passage];
            $results = DB::select("SELECT id FROM q_23_05 where same_passage = :same order by qid", $param);
            $id3 = $results[0]->id;
            $id4 = $results[1]->id;
        }

        // 出題
        $questionArray = []; // 問題返却用
        array_push($questionArray, $questionMap[$id1]);
        array_push($questionArray, $questionMap[$id2]);
        array_push($questionArray, $questionMap[$id3]);
        array_push($questionArray, $questionMap[$id4]);

        // XX番の取得
        $no = 1;
        foreach($questionArray as $question){
            $question->rows = 1;
            // XX番の音声ファイルをセット
            switch($no){
            case 1:
            case 2:
            case 3:
                $question->setBanFile($this->searchBanFile(2, 3, 5, $question->getQid(), $no));
            }
            $question->setNo($no++);
        }

        //dd($questionArray);

        return $questionArray;
    }

    function deleteRuby($str)
    {
        $str = str_replace("<ruby>", "", $str);
        $str = str_replace("<rb>", "", $str);
        $str = str_replace("</rb>", "", $str);
        $str = str_replace("<rt>", "", $str);
        $str = str_replace("</rt>", "", $str);
        $str = str_replace("</ruby>", "", $str);
        return $str;
    }

    function hasDupe($array)
    {
        return count($array) !== count(array_unique($array));
    }

    // for LEVEL2
    function searchBanFile($level, $section, $question, $qid, $no)
    {
        if($level != 2){
            throw new Exception('level 2 only. When using at another level, modify this function');
        }

        // 新問とTは整理されていないので、特殊ロジック
        if((substr($qid, 0, 1) == 'T') || (substr($qid, 0, 3) == 'NEW')){
            switch($no){
            case 1:
                return Config::get('constants.Q2S3.no1DefaultFile');
                break;
            case 2:
                return Config::get('constants.Q2S3.no2DefaultFile');
                break;
            case 3:
                return Config::get('constants.Q2S3.no3DefaultFile');
                break;
            case 4:
                return Config::get('constants.Q2S3.no4DefaultFile');
                break;
            case 5:
                return Config::get('constants.Q2S3.no5DefaultFile');
                break;
            case 6:
                return Config::get('constants.Q2S3.no6DefaultFile');
                break;
            case 7:
                return Config::get('constants.Q2S3.no7DefaultFile');
                break;
            case 8:
                return Config::get('constants.Q2S3.no8DefaultFile');
                break;
            case 9:
                return Config::get('constants.Q2S3.no9DefaultFile');
                break;
            case 10:
                return Config::get('constants.Q2S3.no10DefaultFile');
                break;
            case 11:
                return Config::get('constants.Q2S3.no11DefaultFile');
                break;
            case 12:
                return Config::get('constants.Q2S3.no12DefaultFile');
                break;
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
            $ban = str_replace('/', '/NO_', $records[0]->listening);
            return $ban;
        }

        // なければ問題４から検索（一番質問数が多い問題を使うこと）
        $tableName = 'q_'.$level.$section.'_0'.$question;
        $searchQid = substr($qid, 0, 6).'04'.sprintf('%02d', $no).'00';
        $records = DB::select("SELECT listening FROM ".$tableName." WHERE qid = '".$searchQid."'");
        if($records != null){
            $ban = str_replace('/', '/NO_', $records[0]->listening);
            return $ban;
        }

        // それでもなければ定数ファイルを返す
        switch($no){
        case 1:
            return Config::get('constants.Q2S3.no1DefaultFile');
            break;
        case 2:
            return Config::get('constants.Q2S3.no2DefaultFile');
            break;
        case 3:
            return Config::get('constants.Q2S3.no3DefaultFile');
            break;
        case 4:
            return Config::get('constants.Q2S3.no4DefaultFile');
            break;
        case 5:
            return Config::get('constants.Q2S3.no5DefaultFile');
            break;
        case 6:
            return Config::get('constants.Q2S3.no6DefaultFile');
            break;
        case 7:
            return Config::get('constants.Q2S3.no7DefaultFile');
            break;
        case 8:
            return Config::get('constants.Q2S3.no8DefaultFile');
            break;
        case 9:
            return Config::get('constants.Q2S3.no9DefaultFile');
            break;
        case 10:
            return Config::get('constants.Q2S3.no10DefaultFile');
            break;
        case 11:
            return Config::get('constants.Q2S3.no11DefaultFile');
            break;
        case 12:
            return Config::get('constants.Q2S3.no12DefaultFile');
            break;
        case 13:
            throw new Exception('No. error');
        case 14:
            throw new Exception('No. error');
        default:
            throw new Exception('No. error');
        }
    }
}