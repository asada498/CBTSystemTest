<?php
namespace App\Http\Controllers\Q1\Listening;

use Exception;
use App\ScoreSheet;
use App\AnswerRecord;
use App\QuestionType;
use App\ScoreSummary;
use App\TestInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\QuestionClass\Q1\Listening\Q1S3Q3;
use App\QuestionDatabase\Q1\Listening\Q1Section3Question3;


class Q1S3Q3Controller extends Controller
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
    private $GROUP_CODE_IDEA     = '301'; // 考え・意見・話・出来事
    private $GROUP_CODE_TOPIC    = '302'; // 主題・話題
    private $GROUP_CODE_FEEL     = '304'; // 心情

    public function showQuestion (){

        // DUMMY ID FOR TEST
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20121089011010");
        }
        $currentId = Session::get('idTester');
        $section3Question3Id = $currentId.".Q1S3Q3";
        $questionData = $this->showDataBase();
        Session::put($section3Question3Id, $questionData);
        
        return view('Q1\Listening\Q1S3Q3', ['data' => $questionData]);
    }

    public function getResultToCalculate (Request $request){

        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        
        if (!(Session::has('idTester'))){
            $userIDTemp = "Q20121089011010";
            Session::put('idTester',$userIDTemp);        
        }
        $userID = Session::get('idTester');
        $section3Question3Id = $userID.".Q1S3Q3";
        $questionDataLoad = Session::get($section3Question3Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getNo();
            $currentQuestion = $userID.'.Q1S3Q3_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = 'S';
            //$correctFlag = 0;
            $passFail = 0;

            // アンカー問題がない。０固定にする
            // if($showQuestionquestion->getAnchor() == "1")
            //     $anchorFlag = 1;
            // else $anchorFlag = 0;
            $anchorFlag = 0;

            if ($question->getCorrectAnswer() == $userAnswer)
            {
                $passFail = 1;
                if ($anchorFlag == 1)
                    $anchorFlagResult = 1;
                $scoring++;
                array_push($correctAnswer,$question->getId());
            }
            else 
            {
                if ($question->getCorrectAnswer() == null)
                    $passFail = null;
                else 
                    {   $passFail = 0;
                        array_push($incorrectAnswer,$question->getId());
                    }
            }
            
            //TESTING CODE ONLY. DELETE LATER.
            if (AnswerRecord::where('examinee_number',$userID)->where('level',1)->where('section',3)->where('question',3)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',$userID)->where('level',1)->where('section',3)->where('question',3)->where('number',$questionId)->update(
                    [
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_13_03',
                     'question_id'=>$question->getQid(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectAnswer(),
                     'pass_fail'=>$passFail,
                    ]
                );
            }
            else 
            {
                AnswerRecord::insert(
                    ['examinee_number' => substr($userID, 1), 
                     'level' => 1,
                     'section'=> 3,
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_13_03',
                     'question_id'=>$question->getQid(),
                     'anchor'=>$anchorFlag,
                     'choice'=>$userAnswer,
                     'correct_answer'=>$question->getCorrectAnswer(),
                     'pass_fail'=>$passFail,
                     ]
                );
            }
        }
        //update record on database
        Q1Section3Question3::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q1Section3Question3::raw("past_testee_number + 1"),
            "correct_testee_number" => Q1Section3Question3::raw("correct_testee_number + 1")
        ]);
        Q1Section3Question3::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q1Section3Question3::raw("past_testee_number + 1")
        ]);

        $rate = round($scoring * 100 / 6);
        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's3_q3_correct' => $scoring,
            's3_q3_question' => 6,
            's3_q3_perfect_score' => 12/55*60,
            's3_q3_anchor_pass' => $anchorFlagResult,
            's3_q3_rate' => $rate]);
        $scoreQ1S3Q3 = $scoring;
        Session::put($userID.'.Q1S3Q3Score', $scoreQ1S3Q3);
        Session::put('idTester',$userID);

        return Redirect::to(url('/Q1ListeningQ4'));
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q1S3Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question3Choice = $currentId.".Q1S3Q3_".$questionNumber;

        $sess = Session::get($section3Question3Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $questionMap = [];
        $newQuestionArray = []; // 新問

        $priceIdArray = [];     // 値段
        $quantityIdArray = [];  // 人数・数量
        $numberIdArray = [];    // 番号 (電話番号、ページなど)
        $orderIdArray = [];     // 順番
        $motionIdArray = [];    // 動作・行為
        $multipleIdArray = [];  // 選択・複数
        $singularIdArray = [];  // 選択・単数
        $purposeIdArray = [];   // 目的
        $featureIdArray = [];   // 人や物の特徴・様子
        $scheduleIdArray = [];  // 日程、時間
        $guidanceIdArray = [];  // 道案内・地図
        $reasonIdArray = [];    // 理由・原因
        $placeIdArray = [];     // 場所・(体などの)部位
        $methodIdArray = [];    // 方法・手段
        $weatherIdArray = [];   // 天気
        $ideaIdArray = [];      // 考え・意見・話・出来事
        $topicIdArray = [];     // 主題・話題
        $feelIdArray = [];      // 心情

        $groupMap = [];

        // 問題を取得する 
        $results = Q1Section3Question3::where('usable', '1')->whereBetween('correct_answer_rate', [0.20, 0.80])->get();

        foreach ($results as $rec) {
            $question = new Q1S3Q3($rec->id, $rec->qid, $rec->group1, $rec->group2, $rec->group3, $rec->sentence, $rec->vocabulary, $rec->question,
                            $rec->past_testee_number, $rec->correct_testee_number,
                            $rec->listening, $rec->correct_answer, $rec->silence, $rec->new_question);

            $idQuestion = $rec->id;
            $questionMap[$idQuestion] = $question;
            $groupMap[$rec->group1] = '1';

            if ($rec->new_question == '1'){
                array_push($newQuestionArray, $idQuestion);
            }else{
                // グループごとに配列に格納する
                switch ($rec->group1){
                case $this->GROUP_CODE_PRICE: // 値段
                    array_push($priceIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_QUANTITY: // 人数・数量
                    array_push($quantityIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_NUMBER: // 番号 (電話番号、ページなど)
                    array_push($numberIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_ORDER: // 順番
                    array_push($orderIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_MOTION: // 動作・行為
                    array_push($motionIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_MULTIPLE: // 選択・複数
                    array_push($multipleIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_SINGULAR: // 選択・単数
                    array_push($singularIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_PURPOSE: // 目的
                    array_push($purposeIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_FEATURE: // 人や物の特徴・様子
                    array_push($featureIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_SCHEDULE: // 日程、時間
                    array_push($scheduleIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_GUIDANCE: // 道案内・地図
                    array_push($guidanceIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_REASON: // 理由・原因
                    array_push($reasonIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_PLACE: // 場所・(体などの)部位
                    array_push($placeIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_METHOD: // 方法・手段
                    array_push($methodIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_WHEATHER: // 天気
                    array_push($weatherIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_IDEA: // 考え・意見・話・出来事
                    array_push($ideaIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_TOPIC: // 主題・話題
                    array_push($topicIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_FEEL: // 心情
                    array_push($feelIdArray, $idQuestion);
                    break;    
                default:
                    throw new Exception('GROUP CODE ERROR');
                }
            }
        }

        $questionArray = []; // 問題返却用

        while(true){
            $newQuestionCount = 0;
            $groupArray = array_keys($groupMap);

            // 新問が有る場合１問抽出
            if(count($newQuestionArray) != 0){
                $newQuestion = $questionMap[$newQuestionArray[array_rand($newQuestionArray, 1)]];
                array_push($questionArray, $newQuestion);
                // 新問の出題分類を削除
                array_splice($groupArray, array_search($newQuestion->group1, $groupArray), 1);
                $newQuestionCount = 1;
            }

            // 全部で６問になるよう残りの問題を抽出する
            $count = 0;
            if($newQuestionCount == 1){
                // 新問あり
                $count = 5;
            }else{
                // 新問がない。
                $count = 6;
            }

            // 抽出する
            for($i = 0; $i < $count; $i++){
                $groupIndex = array_rand($groupArray, 1);
                $groupCode = $groupArray[$groupIndex];

                switch($groupCode){
                case $this->GROUP_CODE_PRICE: // 値段
                    array_push($questionArray, $questionMap[$priceIdArray[array_rand($priceIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_QUANTITY: // 人数・数量
                    array_push($questionArray, $questionMap[$quantityIdArray[array_rand($quantityIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_NUMBER: // 番号 (電話番号、ページなど)
                    array_push($questionArray, $questionMap[$numberIdArray[array_rand($numberIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_ORDER: // 順番
                    array_push($questionArray, $questionMap[$orderIdArray[array_rand($orderIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_MOTION: // 動作・行為
                    array_push($questionArray, $questionMap[$motionIdArray[array_rand($motionIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_MULTIPLE: // 選択・複数
                    array_push($questionArray, $questionMap[$multipleIdArray[array_rand($multipleIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_SINGULAR: // 選択・単数
                    array_push($questionArray, $questionMap[$singularIdArray[array_rand($singularIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_PURPOSE: // 目的
                    array_push($questionArray, $questionMap[$purposeIdArray[array_rand($purposeIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_FEATURE: // 人や物の特徴・様子
                    array_push($questionArray, $questionMap[$featureIdArray[array_rand($featureIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_SCHEDULE: // 日程、時間
                    array_push($questionArray, $questionMap[$scheduleIdArray[array_rand($scheduleIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_GUIDANCE: // 道案内・地図
                    array_push($questionArray, $questionMap[$guidanceIdArray[array_rand($guidanceIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_REASON: // 理由・原因
                    array_push($questionArray, $questionMap[$reasonIdArray[array_rand($reasonIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_PLACE: // 場所・(体などの)部位
                    array_push($questionArray, $questionMap[$placeIdArray[array_rand($placeIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_METHOD: // 方法・手段
                    array_push($questionArray, $questionMap[$methodIdArray[array_rand($methodIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_WHEATHER: // 天気
                    array_push($questionArray, $questionMap[$weatherIdArray[array_rand($weatherIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_IDEA: // 考え・意見・話・出来事
                    array_push($questionArray, $questionMap[$ideaIdArray[array_rand($ideaIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_TOPIC: // 主題・話題
                    array_push($questionArray, $questionMap[$topicIdArray[array_rand($topicIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_FEEL: // 心情
                    array_push($questionArray, $questionMap[$feelIdArray[array_rand($feelIdArray, 1)]]);
                    break;
                default:
                    throw new Exception('GROUP1 CODE ERROR '.$groupCode);
                }
                array_splice($groupArray, $groupIndex, 1);
            }

            // 同一問題のチェック
            $idDupeCheckArray = [];
            for($i = 0; $i < 6; $i++){
                $question = $questionArray[$i];
                array_push($idDupeCheckArray, $question->getId());
            }

            if( !($this->hasDupe($idDupeCheckArray)) ){
                // HIT
                break;
            }
        }

        shuffle($questionArray);

        $no = 1;
        foreach($questionArray as $question){
            // XX番の音声ファイルをセット
            $question->setBanFile($this->searchBanFile(1, 3, 3, $question->getQid(), $no));
            $question->setNo($no++);
          
        }
        return $questionArray;
    }

    // for LEVEL1
    function searchBanFile($level, $section, $question, $qid, $no)
    {
        if($level != 1){
            throw new Exception('level 1 only. When using at another level, modify this function');
        }

        // 新問とTは整理されていないので、特殊ロジック
        if((substr($qid, 0, 1) == 'T') || (substr($qid, 0, 3) == 'NEW')){
            switch($no){
            case 1:
                return Config::get('constants.Q1S3.no1DefaultFile');
                break;
            case 2:
                return Config::get('constants.Q1S3.no2DefaultFile');
                break;
            case 3:
                return Config::get('constants.Q1S3.no3DefaultFile');
                break;
            case 4:
                return Config::get('constants.Q1S3.no4DefaultFile');
                break;
            case 5:
                return Config::get('constants.Q1S3.no5DefaultFile');
                break;
            case 6:
                return Config::get('constants.Q1S3.no6DefaultFile');
                break;
            case 7:
                return Config::get('constants.Q1S3.no7DefaultFile');
                break;
            case 8:
                return Config::get('constants.Q1S3.no8DefaultFile');
                break;
            case 9:
                return Config::get('constants.Q1S3.no9DefaultFile');
                break;
            case 10:
                return Config::get('constants.Q1S3.no10DefaultFile');
                break;
            case 11:
                return Config::get('constants.Q1S3.no11DefaultFile');
                break;
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
            return Config::get('constants.Q1S3.no1DefaultFile');
            break;
        case 2:
            return Config::get('constants.Q1S3.no2DefaultFile');
            break;
        case 3:
            return Config::get('constants.Q1S3.no3DefaultFile');
            break;
        case 4:
            return Config::get('constants.Q1S3.no4DefaultFile');
            break;
        case 5:
            return Config::get('constants.Q1S3.no5DefaultFile');
            break;
        case 6:
            return Config::get('constants.Q1S3.no6DefaultFile');
            break;
        case 7:
            return Config::get('constants.Q1S3.no7DefaultFile');
            break;
        case 8:
            return Config::get('constants.Q1S3.no8DefaultFile');
            break;
        case 9:
            return Config::get('constants.Q1S3.no9DefaultFile');
            break;
        case 10:
            return Config::get('constants.Q1S3.no10DefaultFile');
            break;
        case 11:
            return Config::get('constants.Q1S3.no11DefaultFile');
            break;
        default:
            throw new Exception('No. error');
        }
    }

    function hasDupe($array)
    {
        return count($array) !== count(array_unique($array));
    }

    function isEmpty($str){
        if($str == null){
            return true;
        }
        if(trim($str) == ""){
            return true;
        }
        return false;
    }
}