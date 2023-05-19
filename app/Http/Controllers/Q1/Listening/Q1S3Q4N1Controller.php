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
use App\QuestionClass\Q1\Listening\Q1S3Q4;
use App\QuestionDatabase\Q1\Listening\Q1Section3Question4;


class Q1S3Q4N1Controller extends Controller
{
    private $GROUP_CODE1_MISCON = '601'; // 意味の誤認
    private $GROUP_CODE1_MISHEA = '602'; // 聞き間違い
    private $GROUP_CODE1_SELECT = '603'; // 選択的聞き取りによる誤り
    private $GROUP_CODE1_MISSTA = '604'; // 言い間違い

    private $GROUP_CODE2_VOCABULARY = '101'; // 語彙
    private $GROUP_CODE2_PHRASE     = '102'; // フレーズ・挨拶
    private $GROUP_CODE2_SENTENCE   = '103'; // 文型の誤り

    public function showQuestion (){

        // DUMMY ID FOR TEST
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20121089011010");
        }
        $currentId = Session::get('idTester');
        $section3Question4Id = $currentId.".Q1S3Q4";
        $questionData = $this->showDataBase();
        Session::put($section3Question4Id, $questionData);
        
        return view('Q1\Listening\Q1S3Q4N1', ['data' => $questionData]);
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
        $section3Question4Id = $userID.".Q1S3Q4";
        $questionDataLoad = Session::get($section3Question4Id);
        Session::put($userID.".Q1S3Q4Score_anchor", 0);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getNo();
            $currentQuestion = $userID.'.Q1S3Q4_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = 'S';
            //$correctFlag = 0;
            $passFail = 0;

            if($question->getAnchor() == "1")
                $anchorFlag = 1;
            else $anchorFlag = 0;

            if ($question->getCorrectAnswer() == $userAnswer)
            {
                $passFail = 1;
                if ($anchorFlag == 1){
                    $anchorFlagResult = 1;
                    Session::put($userID.".Q1S3Q4Score_anchor", 8.5/55*60/14);
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
            if (AnswerRecord::where('examinee_number',$userID)->where('level',1)->where('section',3)->where('question',4)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',$userID)->where('level',1)->where('section',3)->where('question',4)->where('number',$questionId)->update(
                    [
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_13_04',
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
                     'question' => 4,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_13_04',
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
        Q1Section3Question4::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q1Section3Question4::raw("past_testee_number + 1"),
            "correct_testee_number" => Q1Section3Question4::raw("correct_testee_number + 1")
        ]);
        Q1Section3Question4::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q1Section3Question4::raw("past_testee_number + 1")
        ]);

        $rate = round($scoring * 100 / 14);
        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's3_q4_correct' => $scoring,
            's3_q4_question' => 14,
            's3_q4_perfect_score' => 8.5/55*60,
            's3_q4_anchor_pass' => $anchorFlagResult,
            's3_q4_rate' => $rate]);

        $scoreQ1S3Q4 = $scoring;
        Session::put($userID.'.Q1S3Q4Score', $scoreQ1S3Q4);
        Session::put('idTester',$userID);

        return Redirect::to(url('/Q1ListeningQ5'));
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q1S3Q4_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question4Choice = $currentId.".Q1S3Q4_".$questionNumber;

        $sess = Session::get($section3Question4Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $questionMap = []; 

        $misconIdArray = [];
        $misheaIdArray = [];
        $selectIdArray = [];
        $misstaIdArray = [];

        $anchorIdArray = [];    // アンカー
        $newQuestionArray = []; // 新問

        // 問題を取得する 
        $results = Q1Section3Question4::where('usable', '1')->whereBetween('correct_answer_rate', [0.20, 0.80])->get();

        foreach ($results as $rec) {

            $isOK = false;
            switch($rec->group1){
            case $this->GROUP_CODE1_MISCON: // 意味の誤認
            case $this->GROUP_CODE1_MISHEA: // 聞き間違い
            case $this->GROUP_CODE1_SELECT: // 選択的聞き取りによる誤り
            case $this->GROUP_CODE1_MISSTA: // 言い間違い
                $isOK = true;
                break;
            default:
                $isOK = false;
                break;
            }
            if(!$isOK)
                continue;

            $question = new Q1S3Q4($rec->id, $rec->qid, $rec->group1, $rec->group2, $rec->vocabulary, $rec->question,
                        $rec->past_testee_number, $rec->correct_testee_number,
                        $rec->listening, $rec->correct_answer, $rec->anchor, $rec->silence, $rec->new_question);

            $idQuestion = $rec->id;
            $questionMap[$idQuestion] = $question;

            // 新問、アンカー、グループごとに配列に格納する
            if ($rec->new_question == '1'){
                array_push($newQuestionArray, $idQuestion);
            }else if ($rec->anchor == '1'){
                array_push($anchorIdArray, $idQuestion);
            }else{
                switch ($rec->group1){
                case $this->GROUP_CODE1_MISCON: // 意味の誤認
                    array_push($misconIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE1_MISHEA: // 聞き間違い
                    array_push($misheaIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE1_SELECT: // 選択的聞き取りによる誤り
                    array_push($selectIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE1_MISSTA: // 言い間違い
                    array_push($misstaIdArray, $idQuestion);
                    break;
                default:
                    throw new Exception('GROUP1 CODE ERROR '.$rec->group1);
                }
            }
        }

        // 出題
        $questionArray = []; // 問題返却用
        while(true){
            $newQuestionAnchor = 0;
            $misconCount= 7;
            $misheaCount= 3;
            $selectCount= 2;
            $misstaCount= 2;

            // 新問が有る場合１問抽出
            if(count($newQuestionArray) != 0){
                $newQuestion = $questionMap[$newQuestionArray[array_rand($newQuestionArray, 1)]];
                array_push($questionArray, $newQuestion);
                switch ($newQuestion->group1){
                case $this->GROUP_CODE1_MISCON: // 意味の誤認
                    $misconCount--;
                    break;
                case $this->GROUP_CODE1_MISHEA: // 聞き間違い
                    $misheaCount--;
                    break;
                case $this->GROUP_CODE1_SELECT: // 選択的聞き取りによる誤り
                    $selectCount--;
                    break;
                case $this->GROUP_CODE1_MISSTA: // 言い間違い
                    $misstaCount--;
                    break;
                default:
                    throw new Exception('GROUP1 CODE ERROR '.$newQuestion->group1);
                }
                if($newQuestion->anchor == '1'){
                    // 新問がアンカーの場合
                    $newQuestionAnchor = 1;
                }
            }

            // アンカーより１問抽出
            if($newQuestionAnchor == 0){
                $anchorQuestion = $questionMap[$anchorIdArray[array_rand($anchorIdArray, 1)]];
                array_push($questionArray, $anchorQuestion);
                switch ($anchorQuestion->group1){
                case $this->GROUP_CODE1_MISCON: // 意味の誤認
                    $misconCount--;
                    break;
                case $this->GROUP_CODE1_MISHEA: // 聞き間違い
                    $misheaCount--;
                    break;
                case $this->GROUP_CODE1_SELECT: // 選択的聞き取りによる誤り
                    $selectCount--;
                    break;
                case $this->GROUP_CODE1_MISSTA: // 言い間違い
                    $misstaCount--;
                    break;
                default:
                    throw new Exception('GROUP1 CODE ERROR '.$newQuestion->group1);
                }
            }

            // 各分類ごとに問題抽出
            // 意味の誤認
            for($i = 0; $i < $misconCount; $i++){
                array_push($questionArray, $questionMap[$misconIdArray[array_rand($misconIdArray, 1)]]);
            }
            // 聞き間違い
            for($i = 0; $i < $misheaCount; $i++){
                array_push($questionArray, $questionMap[$misheaIdArray[array_rand($misheaIdArray, 1)]]);
            }
            // 選択的聞き取りによる誤り
            for($i = 0; $i < $selectCount; $i++){
                array_push($questionArray, $questionMap[$selectIdArray[array_rand($selectIdArray, 1)]]);
            }
            // 言い間違い
            for($i = 0; $i < $misstaCount; $i++){
                array_push($questionArray, $questionMap[$misstaIdArray[array_rand($misstaIdArray, 1)]]);
            }

            // // MT_Class_Listening_groupのカウント
            // $v = 0;
            // $p = 0;
            // $s = 0;
            // $array = [];
            // foreach($questionArray as $question){
            //     switch($question->getGroup2()){
            //     case $this->GROUP_CODE2_VOCABULARY: // 語彙
            //         $v++;
            //         break;
            //     case $this->GROUP_CODE2_PHRASE:     // フレーズ・挨拶
            //         $p++;
            //         break;
            //     case $this->GROUP_CODE2_SENTENCE:   // 文型の誤り
            //         $s++;
            //         break;
            //     default:
            //         throw new Exception('GROUP2 CODE ERROR '.$question->getGroup2());
            //     }
            //     array_push($array, $question->getQuestion());
            // }
            // // 同じ文型、語彙を避ける
            // if($v == 5 && $p == 1 && $s == 8 && !($this->hasDupe($array)) ){
            //     break;
            // }
            // MT_Class_Listening_groupのカウント
            $v = 0;
            $ps = 0;
            $array = [];
            foreach($questionArray as $question){
                switch($question->getGroup2()){
                case $this->GROUP_CODE2_VOCABULARY: // 語彙
                    $v++;
                    break;
                case $this->GROUP_CODE2_PHRASE:     // フレーズ・挨拶
                case $this->GROUP_CODE2_SENTENCE:   // 文型の誤り
                    $ps++;
                    break;
                default:
                    throw new Exception('GROUP2 CODE ERROR '.$question->getGroup2());
                }
                array_push($array, $question->getQuestion());
            }
            // 同じ文型、語彙を避ける
            if($v == 5 && $ps == 9 && !($this->hasDupe($array)) ){
                break;
            }
            $questionArray = [];
        }
        shuffle($questionArray);

        $no = 1;
        foreach($questionArray as $question){
            $question->rows = 1;
            // XX番の音声ファイルをセット
            $question->setBanFile($this->searchBanFile(1, 3, 4, $question->getQid(), $no));
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
            case 12:
                return Config::get('constants.Q1S3.no12DefaultFile');
                break;
            case 13:
                return Config::get('constants.Q1S3.no13DefaultFile');
                break;
            case 14:
                return Config::get('constants.Q1S3.no14DefaultFile');
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
        case 12:
            return Config::get('constants.Q1S3.no12DefaultFile');
            break;
        case 13:
            return Config::get('constants.Q1S3.no13DefaultFile');
            break;
        case 14:
            return Config::get('constants.Q1S3.no14DefaultFile');
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