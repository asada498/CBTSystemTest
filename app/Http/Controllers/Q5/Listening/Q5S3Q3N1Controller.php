<?php
namespace App\Http\Controllers\Q5\Listening;

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
use App\QuestionClass\Q5\Listening\Q5S3Q3;
use App\QuestionDatabase\Q5\Listening\Q5Section3Question3;


class Q5S3Q3N1Controller extends Controller
{
    private $GROUP_CODE_VOCABULARY = '501'; // 語彙・フレーズ・挨拶の誤り
    private $GROUP_CODE_SENTENCE   = '502'; // 文型の誤り
    private $GROUP_CODE_HIERARCHY  = '503'; // 発話の視点、動作の方向の誤り
    
    public function showQuestion (){

        // DUMMY ID FOR TEST
        if (!(Session::has('idTester'))) {
            Session::put('idTester', "Q20121089050001"); // 2020/12/10 TOKYO LEVEL5 0001
        }
        $currentId = Session::get('idTester');
        $section3Question3Id = $currentId.".Q5S3Q3";
        // if (!(Session::has($section3Question3Id)))
        // {
            $questionData = $this->showDataBase();
            Session::put($section3Question3Id, $questionData);
        // }
        
        return view('Q5\Listening\Q5S3Q3N1', ['data' => $questionData]);
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
        $section3Question3Id = $userID.".Q5S3Q3";
        $questionDataLoad = Session::get($section3Question3Id);

        foreach ($questionDataLoad as $question) {
            $questionId = $question->getNo();
            $currentQuestion = $userID.'.Q5S3Q3_'.$questionId;
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
            if (AnswerRecord::where('examinee_number',$userID)->where('level',5)->where('section',3)->where('question',3)->where('number',$questionId)->exists())
            {
                AnswerRecord::where('examinee_number',$userID)->where('level',5)->where('section',3)->where('question',3)->where('number',$questionId)->update(
                    [
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_53_03',
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
                     'level' => 5,
                     'section'=> 3,
                     'question' => 3,
                     'number'=> $questionId,
                     'question_type'=>$codeQuestion,
                     'question_table_name'=>'q_53_03',
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
        
        Q5Section3Question3::whereIn('id', $correctAnswer)->update([
            "past_testee_number" => Q5Section3Question3::raw("past_testee_number + 1"),
            "correct_testee_number" => Q5Section3Question3::raw("correct_testee_number + 1")
        ]);
        Q5Section3Question3::whereIn('id', $incorrectAnswer)->update([
            "past_testee_number" => Q5Section3Question3::raw("past_testee_number + 1")
        ]);
        // $perfectScore = Config::get('constants.Q5S3Q3.perfectScore');
        $rate = round($scoring * 100 / 5);
        ScoreSummary::where('examinee_number', substr($userID, 1))->update([
            's3_q3_correct' => $scoring,
            's3_q3_question' => 5,
            's3_q3_perfect_score' => 6 / 30 * 60,
            's3_q3_anchor_pass' => $anchorFlagResult,
            's3_q3_rate' => $rate]);
        // $scoreQ5S321 = Session::get('Q5S3Q3Score');
        // $request->session()->flush();
        $scoreQ5S3Q3 = $scoring;
        // Session::put('Q5S3Q3Score',$scoreQ5S3Q3);
        Session::put($userID.'.Q5S3Q3Score', $scoreQ5S3Q3);
        Session::put('idTester',$userID);
        // return Redirect::to(url('/Q5ListeningQ4N1'));
         return Redirect::to(url('/Q5ListeningQ4'));
    }

    public function saveChoiceRequestPost(Request $request)
    {       
        $currentId = Session::get('idTester');
        $questionNumber = $request->get('name');
        $answer = $request->get('answer');
        $valueSession = $currentId.".Q5S3Q3_".$questionNumber;
        Session::put($valueSession, $answer);
        return response()->json(['success' => $valueSession]);
    }

    public static function checkValue($questionNumber,$questionChoice)
    {
        $currentId = Session::get('idTester');
        $section3Question3Choice = $currentId.".Q5S3Q3_".$questionNumber;

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
        
        //$groupMap = [];

        // 問題を取得する 
        $results = Q5Section3Question3::where('usable', '1')->whereBetween('correct_answer_rate', [0.20, 0.80])->get();

        foreach ($results as $rec) {

            $question = new Q5S3Q3($rec->id, $rec->qid, $rec->group1, $rec->group2, $rec->sentence, $rec->vocabulary, $rec->question,
                        $rec->past_testee_number, $rec->correct_testee_number,
                        $rec->illustration, $rec->listening, $rec->correct_answer, $rec->silence, $rec->new_question);

            $idQuestion = $rec->id;
            $questionMap[$idQuestion] = $question;

            if ($rec->new_question == '1'){
                array_push($newQuestionArray, $idQuestion);
            }else{
                // グループ１ごとに配列に格納する
                switch ($rec ->group1){
                case $this->GROUP_CODE_VOCABULARY: // 語彙・フレーズ・挨拶の誤り
                    array_push($vocabularyIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_SENTENCE: // 文型の誤り
                    array_push($sentenceIdArray, $idQuestion);
                    break;
                case $this->GROUP_CODE_HIERARCHY: // 発話の視点、動作の方向の誤り
                    array_push($hierarchyIdArray, $idQuestion);
                    break;
                default:
                    throw new Exception('GROUP1 CODE ERROR');
                }
            }
        }

        $questionArray = []; // 問題返却用

        while(true){
            $newQuestionCount = 0;
            $questionArray = [];
            $newQuestion = null;

            // 新問が有る場合１問抽出
            if(count($newQuestionArray) != 0){
                $newQuestion = $questionMap[$newQuestionArray[array_rand($newQuestionArray, 1)]];
                array_push($questionArray, $newQuestion);
                $newQuestionCount = 1;
            }

            // 語彙から１問、フレーズ・挨拶から２問、文型から２問ランダムで抽出する
            if($newQuestionCount == 1){
                // 新問ありの場合
                switch($newQuestion->group1){
                case $this->GROUP_CODE_VOCABULARY: // 語彙・フレーズ・挨拶の誤り
                    array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                    array_push($questionArray, $questionMap[$sentenceIdArray[array_rand($sentenceIdArray, 1)]]);
                    array_push($questionArray, $questionMap[$sentenceIdArray[array_rand($sentenceIdArray, 1)]]);
                    array_push($questionArray, $questionMap[$hierarchyIdArray[array_rand($hierarchyIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_SENTENCE: // 文型の誤り
                    array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                    array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                    array_push($questionArray, $questionMap[$sentenceIdArray[array_rand($sentenceIdArray, 1)]]);
                    array_push($questionArray, $questionMap[$hierarchyIdArray[array_rand($hierarchyIdArray, 1)]]);
                    break;
                case $this->GROUP_CODE_HIERARCHY: // 発話の視点、動作の方向の誤り
                    array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                    array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                    array_push($questionArray, $questionMap[$sentenceIdArray[array_rand($sentenceIdArray, 1)]]);
                    array_push($questionArray, $questionMap[$sentenceIdArray[array_rand($sentenceIdArray, 1)]]);
                    break;
                default:
                    throw new Exception('GROUP1 CODE ERROR');
                }
            }else{
                // 新問が無い場合
                array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                array_push($questionArray, $questionMap[$vocabularyIdArray[array_rand($vocabularyIdArray, 1)]]);
                array_push($questionArray, $questionMap[$sentenceIdArray[array_rand($sentenceIdArray, 1)]]);
                array_push($questionArray, $questionMap[$sentenceIdArray[array_rand($sentenceIdArray, 1)]]);
                array_push($questionArray, $questionMap[$hierarchyIdArray[array_rand($hierarchyIdArray, 1)]]);
            }

            // 重複チェックとgroup2の数チェック
            $idDupeCheckArray = [];
            $questionCheckArray = [];
            $sentenceCheckArray = [];
            $vocabularyCheckArray = [];
            $countVocabulary = 0;
            $countPhrases = 0;
            $countSentence = 0;

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
                switch($question->getGroup2()){
                case "101": // 語彙(慣用表現含む)
                    $countVocabulary++;
                    break;
                case "102": // フレーズ・挨拶
                    $countPhrases++;
                    break;
                case "103": // 文型
                    $countSentence++;
                    break;
                }
            }
            if( !($this->hasDupe($idDupeCheckArray)) &&
                !($this->hasDupe($questionCheckArray)) &&
                !($this->hasDupe($sentenceCheckArray)) &&
                !($this->hasDupe($vocabularyCheckArray)) &&
                $countVocabulary == 2 &&
                $countPhrases == 1 &&
                $countSentence == 2
                ){
                // HIT
                break;
            }
        }

        shuffle($questionArray);

        $no = 1;
        foreach($questionArray as $question){
            // XX番の音声ファイルをセット
            $question->setBanFile($this->searchBanFile(5, 3, 3, $question->getQid(), $no));
            $question->setNo($no++);
          
        }
        return $questionArray;
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