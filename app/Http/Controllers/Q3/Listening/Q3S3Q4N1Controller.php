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
use App\QuestionClass\Q3\Listening\Q3S3Q4;
use App\QuestionDatabase\Q3\Listening\Q3Section3Question4;


class Q3S3Q4N1Controller extends Controller
{
    private $GROUP_CODE1_VOCABULARY = '501'; // 語彙・フレーズ・挨拶言葉の誤り
    private $GROUP_CODE1_SENTENCE   = '502'; // 文型の誤り
    private $GROUP_CODE1_HIERARCHY  = '503'; // 発話の視点、動作の方向の誤り

    private $GROUP_CODE2_VOCABULARY = '101'; // 語彙
    private $GROUP_CODE2_PHRASE     = '102'; // フレーズ・挨拶
    private $GROUP_CODE2_SENTENCE   = '103'; // 文型の誤り

    public function showQuestion (){

        // DUMMY ID FOR TEST
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20121089030001"); // 2020/12/10 TOKYO LEVEL3 0001
        }
        $currentId = Session::get('idTester');
        $section3Question4Id = $currentId.".Q3S3Q4";
        // if (!(Session::has($section3Question4Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put($section3Question4Id, $questionData);
        // }
        
        return view('Q3\Listening\Q3S3Q4N1', ['data' => $questionData]);
    }

    public function getResultToCalculate (Request $request){

        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        
        if (!(Session::has('idTester'))){
            $userIDTemp = "Q20121089030101";
            Session::put('idTester',$userIDTemp);        
        }
        $userID = Session::get('idTester');
        $section3Question4Id = $userID.".Q3S3Q4";
        $questionDataLoad = Session::get($section3Question4Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getNo();
            $currentQuestion = $userID.'.Q3S3Q4_'.$questionId;
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
            if (AnswerRecord::where('examinee_number',$userID)->where('level',3)->where('section',3)->where('question',4)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',$userID)->where('level',3)->where('section',3)->where('question',4)->where('number',$questionId)->update(
                    [
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_33_04',
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
                     'question' => 4,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_33_04',
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
        
        Q3Section3Question4::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q3Section3Question4::raw("past_testee_number + 1"),
            "correct_testee_number" => Q3Section3Question4::raw("correct_testee_number + 1")
        ]);
        Q3Section3Question4::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q3Section3Question4::raw("past_testee_number + 1")
        ]);
        // $perfectScore = Config::get('constants.Q3S3Q4.perfectScore');
        $rate = round($scoring * 100 / 4);
        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's3_q4_correct' => $scoring,
            's3_q4_question' => 4,
            's3_q4_perfect_score' => 5.3 / 40 * 60,
            's3_q4_anchor_pass' => $anchorFlagResult,
            's3_q4_rate' => $rate]);
        // $scoreQ3S321 = Session::get('Q3S3Q4Score');
        // $request->session()->flush();
        $scoreQ3S3Q4 = $scoring;
        // Session::put('Q3S3Q4Score',$scoreQ3S3Q4);
        Session::put($userID.'.Q3S3Q4Score', $scoreQ3S3Q4);
        Session::put('idTester',$userID);

        return Redirect::to(url('/Q3ListeningQ5'));
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q3S3Q4_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question3Choice = $currentId.".Q3S3Q4_".$questionNumber;

        $sess = Session::get($section3Question3Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $questionMap = [];
        $vocabularyIdArray = [];
        $sentenceIdArray = [];
        $hierarchyIdArray = [];
        $newQuestionArray = []; // 新問

        // 問題を取得する 
        $results = Q3Section3Question4::where('usable', '1')->whereBetween('correct_answer_rate', [0.20, 0.80])->get();

        foreach ($results as $rec) {

            $question = new Q3S3Q4($rec->id, $rec->qid, $rec->group1, $rec->group2, $rec->sentence, $rec->vocabulary, $rec->question,
                            $rec->past_testee_number, $rec->correct_testee_number,
                            $rec->illustration, $rec->listening, $rec->correct_answer, $rec->silence, $rec->new_question);

            $idQuestion = $rec->id;
            $questionMap[$idQuestion] = $question;

            if ($rec->new_question == '1'){
                array_push($newQuestionArray, $idQuestion);
            }else{
                // グループ１ごとに配列に格納する
                switch ($rec->group1){
                case $this->GROUP_CODE1_VOCABULARY: // 語彙・フレーズ・挨拶言葉の誤り
                    array_push($vocabularyIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE1_SENTENCE: // 文型の誤り
                    array_push($sentenceIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE1_HIERARCHY: // 発話の視点、動作の方向の誤り
                    array_push($hierarchyIdArray, $idQuestion);
                    break;
                default:
                    throw new Exception('GROUP1 CODE ERROR');
                }
            }
        }

        $questionArray = []; // 問題返却用

        while(true){
            $questionArray = [];
            $newQuestionCount = 0;
            $newQuestion = null;

            // 新問が有る場合１問抽出
            if(count($newQuestionArray) != 0){
                $newQuestion = $questionMap[$newQuestionArray[array_rand($newQuestionArray, 1)]];
                array_push($questionArray, $newQuestion);
                $newQuestionCount = 1;
            }

            // 語彙・フレーズ・挨拶言葉の誤りから２問、文型の誤りから２問、発話の視点・動作の方向の誤りから１問
            if($newQuestionCount == 1){
                // 新問ありの場合
                switch($newQuestion->group1){
                    case $this->GROUP_CODE1_VOCABULARY: // 語彙・フレーズ・挨拶の誤り
                        array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$sentenceIdArray[array_rand($sentenceIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$hierarchyIdArray[array_rand($hierarchyIdArray, 1)]]);
                        break;
                    case $this->GROUP_CODE1_SENTENCE: // 文型の誤り
                        array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$hierarchyIdArray[array_rand($hierarchyIdArray, 1)]]);
                        break;
                    case $this->GROUP_CODE1_HIERARCHY: // 発話の視点、動作の方向の誤り
                        array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$sentenceIdArray[array_rand($sentenceIdArray, 1)]]);
                        break;
                    default:
                        throw new Exception('GROUP1 CODE ERROR');
                    }
            }else{
                // 新問が無い場合、語彙・フレーズ・挨拶言葉の誤りから２問、文型の誤りから２問、発話の視点・動作の方向の誤りから１問ランダムで抽出する
                array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                array_push($questionArray, $questionMap[$sentenceIdArray[array_rand($sentenceIdArray, 1)]]);
                array_push($questionArray, $questionMap[$hierarchyIdArray[array_rand($hierarchyIdArray, 1)]]);
            }

            // 出題分類の問題数と同一問題のチェック
            $v = 0;
            $p = 0;
            $s = 0;
            $idDupeCheckArray = [];
            $questionCheckArray = [];
            $sentenceCheckArray = [];
            $vocabularyCheckArray = [];

            for($i = 0; $i < 4; $i++){
                $question = $questionArray[$i];
                array_push($idDupeCheckArray, $question->getId());
                array_push($questionCheckArray, $question->getQuestion());
                if(!$this->isEmpty($question->getSentence())){
                    array_push($sentenceCheckArray, $question->getSentence());
                }
                if(!$this->isEmpty($question->getVocabulary())){
                    array_push($vocabularyCheckArray, $question->getVocabulary());
                }
                switch($questionArray[$i]->group2){
                case $this->GROUP_CODE2_VOCABULARY: // 語彙
                    $v++;
                    break;                     
                case $this->GROUP_CODE2_PHRASE:     // フレーズ・挨拶
                    $p++;
                    break;
                case $this->GROUP_CODE2_SENTENCE:   // 文型の誤り
                    $s++;
                    break;
                default:
                    throw new Exception("code2 error");
                }
            }

            if( !($this->hasDupe($idDupeCheckArray)) &&
                !($this->hasDupe($questionCheckArray)) &&
                !($this->hasDupe($sentenceCheckArray)) &&
                !($this->hasDupe($vocabularyCheckArray)) &&
                $v == 1 && $p == 1 && $s == 2 ){
                // HIT
                break;
            }
        }

        shuffle($questionArray);

        $no = 1;
        foreach($questionArray as $question){
            // XX番の音声ファイルをセット
            $question->setBanFile($this->searchBanFile(3, 3, 4, $question->getQid(), $no));
            $question->setNo($no++);
          
        }
        return $questionArray;
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