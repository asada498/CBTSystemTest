<?php
namespace App\Http\Controllers\Q2\Listening;

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
use App\QuestionClass\Q2\Listening\Q2S3Q3;
use App\QuestionDatabase\Q2\Listening\Q2Section3Question3;


class Q2S3Q3Controller extends Controller
{
    private $GROUP_CODE_IDEA  = '301'; // 考え・意見・話・出来事
    private $GROUP_CODE_TOPIC = '302'; // 主題・話題

    public function showQuestion (){

        // DUMMY ID FOR TEST
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20121089021010");
        }
        $currentId = Session::get('idTester');
        $section3Question3Id = $currentId.".Q2S3Q3";
        // if (!(Session::has($section3Question4Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put($section3Question3Id, $questionData);
        // }
        
        return view('Q2\Listening\Q2S3Q3', ['data' => $questionData]);
    }

    public function getResultToCalculate (Request $request){

        $correctAnswer = [];
        $incorrectAnswer = [];
        $scoring = 0;
        $anchorFlag = 0;
        $anchorFlagResult = 0;
        
        if (!(Session::has('idTester'))){
            $userIDTemp = "Q20121089021010";
            Session::put('idTester',$userIDTemp);        
        }
        $userID = Session::get('idTester');
        $section3Question3Id = $userID.".Q2S3Q3";
        $questionDataLoad = Session::get($section3Question3Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getNo();
            $currentQuestion = $userID.'.Q2S3Q3_'.$questionId;
            $userAnswer = Session::get($currentQuestion);
            $codeQuestion = 'S'; // TODO ?
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
            if (AnswerRecord::where('examinee_number',$userID)->where('level',2)->where('section',3)->where('question',3)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',$userID)->where('level',2)->where('section',3)->where('question',3)->where('number',$questionId)->update(
                    [
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_23_03',
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
                     'level' => 2,
                     'section'=> 3,
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_23_03',
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
        Q2Section3Question3::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q2Section3Question3::raw("past_testee_number + 1"),
            "correct_testee_number" => Q2Section3Question3::raw("correct_testee_number + 1")
        ]);
        Q2Section3Question3::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q2Section3Question3::raw("past_testee_number + 1")
        ]);
        // $perfectScore = Config::get('constants.Q3S3Q4.perfectScore');
        $rate = round($scoring * 100 / 5);
        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's3_q3_correct' => $scoring,
            's3_q3_question' => 5,
            's3_q3_perfect_score' => 12,
            's3_q3_anchor_pass' => $anchorFlagResult,
            's3_q3_rate' => $rate]);
        // $scoreQ3S321 = Session::get('Q3S3Q4Score');
        // $request->session()->flush();
        $scoreQ2S3Q3 = $scoring;
        // Session::put('Q3S3Q4Score',$scoreQ3S3Q4);
        Session::put($userID.'.Q2S3Q3Score', $scoreQ2S3Q3);
        Session::put('idTester',$userID);

        return Redirect::to(url('/Q2ListeningQ4N1'));
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q2S3Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question3Choice = $currentId.".Q2S3Q3_".$questionNumber;

        $sess = Session::get($section3Question3Choice);
        if ($sess == $questionChoice)
            return "checked ";

        else return "";
    }

    function showDataBase()
    {
        $questionMap = [];
        $newQuestionArray = []; // 新問

        $ideaIdArray = [];
        $topicIdArray = [];

        // 問題を取得する 
        $results = Q2Section3Question3::where('usable', '1')->whereBetween('correct_answer_rate', [0.20, 0.80])->get();

        foreach ($results as $rec) {

            if($rec->group1 != $this->GROUP_CODE_IDEA && $rec->group1 != $this->GROUP_CODE_TOPIC){
                continue;
            }

            $question = new Q2S3Q3($rec->id, $rec->qid, $rec->group1, $rec->group2, $rec->group3, $rec->sentence, $rec->vocabulary, $rec->question,
                            $rec->past_testee_number, $rec->correct_testee_number,
                            $rec->listening, $rec->correct_answer, $rec->silence, $rec->new_question);

            $idQuestion = $rec->id;
            $questionMap[$idQuestion] = $question;

            if ($rec->new_question == '1'){
                array_push($newQuestionArray, $idQuestion);
            }else{
                // グループごとに配列に格納する
                switch ($rec->group1){
                case $this->GROUP_CODE_IDEA:
                    array_push($ideaIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_TOPIC:
                    array_push($topicIdArray, $idQuestion);
                    break;
                default:
                    throw new Exception('GROUP CODE ERROR');
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

            // 考え・意見・話・出来事から２問、主題・話題から３問
            if($newQuestionCount == 1){
                // 新問ありの場合
                switch($newQuestion->group1){
                    case $this->GROUP_CODE_IDEA:
                        array_push($questionArray, $questionMap[$ideaIdArray[array_rand($ideaIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$topicIdArray[array_rand($topicIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$topicIdArray[array_rand($topicIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$topicIdArray[array_rand($topicIdArray, 1)]]);
                        break;
                    case $this->GROUP_CODE_TOPIC:
                        array_push($questionArray, $questionMap[$ideaIdArray[array_rand($ideaIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$ideaIdArray[array_rand($ideaIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$topicIdArray[array_rand($topicIdArray, 1)]]);
                        array_push($questionArray, $questionMap[$topicIdArray[array_rand($topicIdArray, 1)]]);
                        break;
                    default:
                        throw new Exception('GROUP CODE ERROR');
                    }
            }else{
                // 新問が無い場合
                array_push($questionArray, $questionMap[$ideaIdArray[array_rand($ideaIdArray, 1)]]);
                array_push($questionArray, $questionMap[$ideaIdArray[array_rand($ideaIdArray, 1)]]);
                array_push($questionArray, $questionMap[$topicIdArray[array_rand($topicIdArray, 1)]]);
                array_push($questionArray, $questionMap[$topicIdArray[array_rand($topicIdArray, 1)]]);
                array_push($questionArray, $questionMap[$topicIdArray[array_rand($topicIdArray, 1)]]);
            }

            // 同一問題のチェック
            $idDupeCheckArray = [];
            $questionCheckArray = [];
            $sentenceCheckArray = [];
            $vocabularyCheckArray = [];
            for($i = 0; $i < 5; $i++){
                $question = $questionArray[$i];
                array_push($idDupeCheckArray, $question->getId());
                array_push($questionCheckArray, $question->getQuestion());
                if(!$this->isEmpty($question->getSentence())){
                    array_push($sentenceCheckArray, $question->getSentence());
                }
                if(!$this->isEmpty($question->getVocabulary())){
                    array_push($vocabularyCheckArray, $question->getVocabulary());
                }
            }

            if( !($this->hasDupe($idDupeCheckArray)) &&
                !($this->hasDupe($questionCheckArray)) &&
                !($this->hasDupe($sentenceCheckArray)) &&
                !($this->hasDupe($vocabularyCheckArray))
                ){
                // HIT
                break;
            }
        }

        shuffle($questionArray);

        $no = 1;
        foreach($questionArray as $question){
            // XX番の音声ファイルをセット
            $question->setBanFile($this->searchBanFile(2, 3, 3, $question->getQid(), $no));
            $question->setNo($no++);
          
        }
        return $questionArray;
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