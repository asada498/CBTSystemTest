<?php
namespace App\Http\Controllers\Q3\Listening;

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
use App\QuestionClass\Q3\Listening\Q3S3Q3;
use App\QuestionDatabase\Q3\Listening\Q3Section3Question3;


class Q3S3Q3Controller extends Controller
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
    private $GROUP_CODE_THINKING = '301'; //【概要】考え・意見・話・出来事
    private $GROUP_CODE_TOPIC    = '302'; //【概要】主題・話題
    private $GROUP_CODE_FEELING  = '303'; //【概要】心情
    private $GROUP_CODE_TALK     = '304'; //【概要】話の内容と合っているもの

    public function showQuestion (){

        // DUMMY ID FOR TEST
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20121089031010");
        }
        $currentId = Session::get('idTester');
        $section3Question3Id = $currentId.".Q3S3Q3";
        // if (!(Session::has($section3Question1Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put($section3Question3Id, $questionData);
        // }
        
        return view('Q3\Listening\Q3S3Q3', ['data' => $questionData]);
    }

    public function getResultToCalculate (Request $request){

        $correctAnswer = [];
        $incorrectAnswer = [];        
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        
        if (!(Session::has('idTester'))){
            $userIDTemp = "Q20121089031010";
            Session::put('idTester',$userIDTemp);
        }
        $userID = Session::get('idTester');
        $section3Question3Id = $userID.".Q3S3Q3";
        $questionDataLoad = Session::get($section3Question3Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getNo();
            $currentQuestion = $userID.'.Q3S3Q3_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = 'R';
            //$correctFlag = 0;
            $passFail = 0;

            // アンカー問題がない。０固定にする
            // if($question->getAnchor() == "1")
            //     $anchorFlag = 1;
            // else $anchorFlag = 0;
            $anchorFlag = 0;

            if ($question->getCorrectAnswer() == $userAnswer)
            {
                $passFail = 1;
                if ($anchorFlag == 1)
                {
                    $anchorFlagResult = 1;
                }
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
            if (AnswerRecord::where('examinee_number',$userID)->where('level',3)->where('section',3)->where('question',3)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',$userID)->where('level',3)->where('section',3)->where('question',3)->where('number',$questionId)->update(
                    [
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_33_03',
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
                     'level' => 3,
                     'section'=> 3,
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_33_03',
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
        
        Q3Section3Question3::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q3Section3Question3::raw("past_testee_number + 1"),
            "correct_testee_number" => Q3Section3Question3::raw("correct_testee_number + 1")
        ]);
        Q3Section3Question3::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q3Section3Question3::raw("past_testee_number + 1")
        ]);
        //$perfectScore = Config::get('constants.Q5S3Q1.perfectScore');
        $rate = round($scoring * 100 / 3);
        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's3_q3_correct' => $scoring,
            's3_q3_question' => 3,
            's3_q3_perfect_score' => 6 / 40 * 60,
            's3_q3_anchor_pass' => $anchorFlagResult,
            's3_q3_rate' => $rate]);
        // $scoreQ5S3Q3 = Session::get('Q5S3Q3Score');
        // $request->session()->flush();
        // Session::put('Q5S3Q3Score',$scoreQ5S3Q3);
        Session::put($userID.'.Q3S3Q3Score', $scoring);
        Session::put('idTester',$userID);
        return Redirect::to(url('/Q3ListeningQ4N1'));
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q3S3Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question3Choice = $currentId.".Q3S3Q3_".$questionNumber;

        $sess = Session::get($section3Question3Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $questionMap = []; 
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
        $thinkingIdArray = [];  //【概要】考え・意見・話・出来事
        $topicIdArray = [];     //【概要】主題・話題
        $feelingIdArray = [];   //【概要】心情
        $talkIdArray = [];      //【概要】話の内容と合っているもの
        $newQuestionArray = []; // 新問
        $groupMap = [];

        // 問題を取得する 
        $results = Q3Section3Question3::where('usable', '1')->whereBetween('correct_answer_rate', [0.20, 0.80])->get();

        foreach ($results as $rec) {

            $question = new Q3S3Q3($rec->id, $rec->qid, $rec->group1, $rec->group2, $rec->group3,
                        $rec->past_testee_number, $rec->correct_testee_number, $rec->question,
                        $rec->listening, $rec->correct_answer, $rec->silence, $rec->new_question);

            $idQuestion = $rec->id;
            $questionMap[$idQuestion] = $question;
            $groupMap[$rec->group1] = '1';

            // 新問、アンカー、グループごとに配列に格納する
            if ($rec->new_question == '1'){
                array_push($newQuestionArray, $idQuestion);
            }else{
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
                case $this->GROUP_CODE_THINKING: //【概要】考え・意見・話・出来事
                    array_push($thinkingIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_TOPIC: //【概要】主題・話題
                    array_push($topicIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_FEELING: //【概要】心情
                    array_push($feelingIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_TALK: //【概要】話の内容と合っているもの
                    array_push($talkIdArray, $idQuestion);
                    break;
                default:
                    throw new Exception('GROUP CODE ERROR '.$rec->group1);
                }
            }
        }

        // 出題
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

            // 全部で３問になるよう残りの問題を抽出する
            $count = 0;
            if($newQuestionCount == 1){
                // 新問がある
                $count = 2;
            }else{
                // 新問がない
                $count = 3;
            }

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
                case $this->GROUP_CODE_THINKING: //【概要】考え・意見・話・出来事
                    array_push($questionArray, $questionMap[$thinkingIdArray[array_rand($thinkingIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_TOPIC: //【概要】主題・話題
                    array_push($questionArray, $questionMap[$topicIdArray[array_rand($topicIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_FEELING: //【概要】心情
                    array_push($questionArray, $questionMap[$feelingIdArray[array_rand($feelingIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_TALK: //【概要】話の内容と合っているもの
                    array_push($questionArray, $questionMap[$talkIdArray[array_rand($talkIdArray, 1)]]);
                    break;
                default:
                    throw new Exception('GROUP CODE ERROR '.$groupCode);
                }
                array_splice($groupArray, $groupIndex, 1);
            }

            $array = [];
            $group1Array = [];

            foreach($questionArray as $question){
                array_push($array, $question->getQuestion());
                array_push($group1Array, $question->getGroup1());
            }
            if(!($this->hasDupe($array)) && !($this->hasDupe($group1Array))){
                break;
            }
            $questionArray = [];
        }

        shuffle($questionArray);

        $no = 1;
        foreach($questionArray as $question){
            // // ２行にするか判定する
            // if( max(mb_strlen($this->deleteRuby($question->choiceA)), mb_strlen($this->deleteRuby($question->choiceB)), mb_strlen($this->deleteRuby($question->choiceC)), mb_strlen($this->deleteRuby($question->choiceD))) > 18 ){
            //     // 選択肢に18文字以上があるなら２行にする
            //     $question->rows = 2;
            // }else{
            //     $question->rows = 1;
            // }
            // XX番の音声ファイルをセット
            $question->setBanFile($this->searchBanFile(3, 3, 3, $question->getQid(), $no));
            $question->setNo($no++);
        }
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

    function hasDupe($array)
    {
        return count($array) !== count(array_unique($array));
    }
}