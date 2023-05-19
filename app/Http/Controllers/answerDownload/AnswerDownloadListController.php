<?php

namespace App\Http\Controllers\answerDownload;

use Auth;
use Session;
use Validator;
use DB;
use App\ExamineeList;
use App\AnswerRecord;
use App\AnswerDownloadRecord;
use App\AnswerDownloadCondition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnswerDownloadListController extends Controller
{
    function search(Request $request)
    {
        $examineeId = $request->get('examineeId');
        $targetDate = $request->get('targetDate');
        $fromDate = $request->get('fromDate');
        $toDate = $request->get('toDate');
        $level = $request->get('level');
        $op = $request->get('op');

        $condition = new AnswerDownloadCondition($examineeId, $targetDate, $fromDate, $toDate, $level, '0');
        Session::put('AnswerDownloadCondition', $condition);

        $query = ExamineeList::whereNotNull('q'); // gradeテーブルがあるもの
        if($examineeId != null){
            $query->where('examinee_id', 'like', "{$examineeId}%");
        }
        if($targetDate != null){
            $query->where('test_day', '=', $targetDate);
        }
        if($fromDate != null){
            $query->where('test_day', '>=', $fromDate);
        }
        if($toDate != null){
            $query->where('test_day', '<=', $toDate);
        }
        if($level != null){
            $query->where('q', '=', $level);
        }
        $query->orderBy('examinee_id');
        $list = $query->get();

        if($op == 'search'){
            $ret = [];
            foreach($list as $rec){
                $arr = [];
                switch($rec['q']){
                case 5:
                    $arr = $this->level5($rec['examinee_id']);
                    break;
                case 4:
                    $arr = $this->level4($rec['examinee_id']);
                    break;
                case 3:
                    $arr = $this->level3($rec['examinee_id']);
                    break;
                case 2:
                    $arr = $this->level2($rec['examinee_id']);
                    break;
                case 1:
                    $arr = $this->level1($rec['examinee_id']);
                    break;    
                }

                $ret = array_merge($ret, $arr);
            }
            return view('answerDownload/answerDownloadList', ['data' => $ret, 'condition' => $condition]);
        }

        $headers = [
            'Content-type' => 'text/csv;',
            'Content-Disposition' => 'attachment; filename=answer_list.csv'
        ];

        $callback = function() use($list) {
            ob_clean();
            $handle = fopen('php://output', 'w');
            $columns = [
                '受験番号',
                '級',
                '問題ＩＤ',
                '分野',
                '大問',
                '小問',
                '解答',
                '正否'
            ];
            mb_convert_variables('SJIS-win', 'UTF-8', $columns);
            fputcsv($handle, $columns);
            foreach($list as $rec) {
                $arr = [];
                switch($rec['q']){
                case 5:
                    $arr = $this->level5($rec['examinee_id']);
                    break;
                case 4:
                    $arr = $this->level4($rec['examinee_id']);
                    break;
                case 3:
                    $arr = $this->level3($rec['examinee_id']);
                    break;
                case 2:
                    $arr = $this->level2($rec['examinee_id']);
                    break;
                case 1:
                    $arr = $this->level1($rec['examinee_id']);
                    break;    
                }

                foreach($arr as $ele) {
                    $csv = [
                        $ele->getExamineeId(),
                        $ele->getLevel(),
                        $ele->getQuestionId(),
                        $ele->getSection(),
                        $ele->getQuestionNumber(),
                        $ele->getNumber(),
                        $ele->getAnswer(),
                        $ele->getPassFail()
                    ];
                    mb_convert_variables('SJIS-win', 'UTF-8', $csv);
                    fputcsv($handle, $csv);
                }
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    function level1($examinee_id){
        $ret = [];
        array_push($ret, $this->select($examinee_id, 1,1,1));
        array_push($ret, $this->select($examinee_id, 1,1,2));
        array_push($ret, $this->select($examinee_id, 1,1,3));
        array_push($ret, $this->select($examinee_id, 1,1,4));
        array_push($ret, $this->select($examinee_id, 1,1,5));
        array_push($ret, $this->select($examinee_id, 1,1,6));
        array_push($ret, $this->select($examinee_id, 1,2,7));
        array_push($ret, $this->select($examinee_id, 1,2,8));
        array_push($ret, $this->select($examinee_id, 1,2,9));
        array_push($ret, $this->select($examinee_id, 1,2,10));
        array_push($ret, $this->select($examinee_id, 1,2,11));
        array_push($ret, $this->select($examinee_id, 1,2,12));
        array_push($ret, $this->select($examinee_id, 1,2,13));
        array_push($ret, $this->select($examinee_id, 1,3,14));
        array_push($ret, $this->select($examinee_id, 1,3,15));
        array_push($ret, $this->select($examinee_id, 1,3,16));
        array_push($ret, $this->select($examinee_id, 1,3,17));
        array_push($ret, $this->select($examinee_id, 1,3,18));
        array_push($ret, $this->select($examinee_id, 1,3,19));
        array_push($ret, $this->select($examinee_id, 1,4,20));
        array_push($ret, $this->select($examinee_id, 1,4,21));
        array_push($ret, $this->select($examinee_id, 1,4,22));
        array_push($ret, $this->select($examinee_id, 1,4,23));
        array_push($ret, $this->select($examinee_id, 1,4,24));
        array_push($ret, $this->select($examinee_id, 1,4,25));
        array_push($ret, $this->select($examinee_id, 1,5,26));
        array_push($ret, $this->select($examinee_id, 1,5,27));
        array_push($ret, $this->select($examinee_id, 1,5,28));
        array_push($ret, $this->select($examinee_id, 1,5,29));
        array_push($ret, $this->select($examinee_id, 1,5,30));
        array_push($ret, $this->select($examinee_id, 1,5,31));
        array_push($ret, $this->select($examinee_id, 1,5,32));
        array_push($ret, $this->select($examinee_id, 1,5,33));
        array_push($ret, $this->select($examinee_id, 1,5,34));
        array_push($ret, $this->select($examinee_id, 1,5,35));
        array_push($ret, $this->select($examinee_id, 1,6,36));
        array_push($ret, $this->select($examinee_id, 1,6,37));
        array_push($ret, $this->select($examinee_id, 1,6,38));
        array_push($ret, $this->select($examinee_id, 1,6,39));
        array_push($ret, $this->select($examinee_id, 1,6,40));
        array_push($ret, $this->select($examinee_id, 1,7,41));
        array_push($ret, $this->select($examinee_id, 1,7,42));
        array_push($ret, $this->select($examinee_id, 1,7,43));
        array_push($ret, $this->select($examinee_id, 1,7,44));
        array_push($ret, $this->select($examinee_id, 1,7,45));
        array_push($ret, $this->select($examinee_id, 1,8,46));
        array_push($ret, $this->select($examinee_id, 1,8,47));
        array_push($ret, $this->select($examinee_id, 1,8,48));
        array_push($ret, $this->select($examinee_id, 1,8,49));
        array_push($ret, $this->select($examinee_id, 1,9,50));
        array_push($ret, $this->select($examinee_id, 1,9,51));
        array_push($ret, $this->select($examinee_id, 1,9,52));
        array_push($ret, $this->select($examinee_id, 1,9,53));
        array_push($ret, $this->select($examinee_id, 1,9,54));
        array_push($ret, $this->select($examinee_id, 1,9,55));
        array_push($ret, $this->select($examinee_id, 1,9,56));
        array_push($ret, $this->select($examinee_id, 1,9,57));
        array_push($ret, $this->select($examinee_id, 1,9,58));
        array_push($ret, $this->select($examinee_id, 1,10,59));
        array_push($ret, $this->select($examinee_id, 1,10,60));
        array_push($ret, $this->select($examinee_id, 1,10,61));
        array_push($ret, $this->select($examinee_id, 1,10,62));
        array_push($ret, $this->select($examinee_id, 1,11,63));
        array_push($ret, $this->select($examinee_id, 1,11,64));
        array_push($ret, $this->select($examinee_id, 1,11,65));
        array_push($ret, $this->select($examinee_id, 1,12,66));
        array_push($ret, $this->select($examinee_id, 1,12,67));
        array_push($ret, $this->select($examinee_id, 1,12,68));
        array_push($ret, $this->select($examinee_id, 1,12,69));
        array_push($ret, $this->select($examinee_id, 1,13,70));
        array_push($ret, $this->select($examinee_id, 1,13,71));

        array_push($ret, $this->select($examinee_id, 3,1,1));
        array_push($ret, $this->select($examinee_id, 3,1,2));
        array_push($ret, $this->select($examinee_id, 3,1,3));
        array_push($ret, $this->select($examinee_id, 3,1,4));
        array_push($ret, $this->select($examinee_id, 3,1,5));
        array_push($ret, $this->select($examinee_id, 3,1,6));
        array_push($ret, $this->select($examinee_id, 3,2,1));
        array_push($ret, $this->select($examinee_id, 3,2,2));
        array_push($ret, $this->select($examinee_id, 3,2,3));
        array_push($ret, $this->select($examinee_id, 3,2,4));
        array_push($ret, $this->select($examinee_id, 3,2,5));
        array_push($ret, $this->select($examinee_id, 3,2,6));
        array_push($ret, $this->select($examinee_id, 3,2,7));
        array_push($ret, $this->select($examinee_id, 3,3,1));
        array_push($ret, $this->select($examinee_id, 3,3,2));
        array_push($ret, $this->select($examinee_id, 3,3,3));
        array_push($ret, $this->select($examinee_id, 3,3,4));
        array_push($ret, $this->select($examinee_id, 3,3,5));
        array_push($ret, $this->select($examinee_id, 3,3,6));
        array_push($ret, $this->select($examinee_id, 3,4,1));
        array_push($ret, $this->select($examinee_id, 3,4,2));
        array_push($ret, $this->select($examinee_id, 3,4,3));
        array_push($ret, $this->select($examinee_id, 3,4,4));
        array_push($ret, $this->select($examinee_id, 3,4,5));
        array_push($ret, $this->select($examinee_id, 3,4,6));
        array_push($ret, $this->select($examinee_id, 3,4,7));
        array_push($ret, $this->select($examinee_id, 3,4,8));
        array_push($ret, $this->select($examinee_id, 3,4,9));
        array_push($ret, $this->select($examinee_id, 3,4,10));
        array_push($ret, $this->select($examinee_id, 3,4,11));
        array_push($ret, $this->select($examinee_id, 3,4,12));
        array_push($ret, $this->select($examinee_id, 3,4,13));
        array_push($ret, $this->select($examinee_id, 3,4,14));
        array_push($ret, $this->select($examinee_id, 3,5,1));
        array_push($ret, $this->select($examinee_id, 3,5,2));
        array_push($ret, $this->select($examinee_id, 3,5,3));
        array_push($ret, $this->select($examinee_id, 3,5,4));

        return $ret;
    }

    function level2($examinee_id){
        $ret = [];
        array_push($ret, $this->select($examinee_id, 1,1,1));
        array_push($ret, $this->select($examinee_id, 1,1,2));
        array_push($ret, $this->select($examinee_id, 1,1,3));
        array_push($ret, $this->select($examinee_id, 1,1,4));
        array_push($ret, $this->select($examinee_id, 1,1,5));
        array_push($ret, $this->select($examinee_id, 1,2,6));
        array_push($ret, $this->select($examinee_id, 1,2,7));
        array_push($ret, $this->select($examinee_id, 1,2,8));
        array_push($ret, $this->select($examinee_id, 1,2,9));
        array_push($ret, $this->select($examinee_id, 1,2,10));
        array_push($ret, $this->select($examinee_id, 1,3,11));
        array_push($ret, $this->select($examinee_id, 1,3,12));
        array_push($ret, $this->select($examinee_id, 1,3,13));
        array_push($ret, $this->select($examinee_id, 1,3,14));
        array_push($ret, $this->select($examinee_id, 1,3,15));
        array_push($ret, $this->select($examinee_id, 1,4,16));
        array_push($ret, $this->select($examinee_id, 1,4,17));
        array_push($ret, $this->select($examinee_id, 1,4,18));
        array_push($ret, $this->select($examinee_id, 1,4,19));
        array_push($ret, $this->select($examinee_id, 1,4,20));
        array_push($ret, $this->select($examinee_id, 1,4,21));
        array_push($ret, $this->select($examinee_id, 1,4,22));
        array_push($ret, $this->select($examinee_id, 1,5,23));
        array_push($ret, $this->select($examinee_id, 1,5,24));
        array_push($ret, $this->select($examinee_id, 1,5,25));
        array_push($ret, $this->select($examinee_id, 1,5,26));
        array_push($ret, $this->select($examinee_id, 1,5,27));
        array_push($ret, $this->select($examinee_id, 1,6,28));
        array_push($ret, $this->select($examinee_id, 1,6,29));
        array_push($ret, $this->select($examinee_id, 1,6,30));
        array_push($ret, $this->select($examinee_id, 1,6,31));
        array_push($ret, $this->select($examinee_id, 1,6,32));
        array_push($ret, $this->select($examinee_id, 1,7,33));
        array_push($ret, $this->select($examinee_id, 1,7,34));
        array_push($ret, $this->select($examinee_id, 1,7,35));
        array_push($ret, $this->select($examinee_id, 1,7,36));
        array_push($ret, $this->select($examinee_id, 1,7,37));
        array_push($ret, $this->select($examinee_id, 1,7,38));
        array_push($ret, $this->select($examinee_id, 1,7,39));
        array_push($ret, $this->select($examinee_id, 1,7,40));
        array_push($ret, $this->select($examinee_id, 1,7,41));
        array_push($ret, $this->select($examinee_id, 1,7,42));
        array_push($ret, $this->select($examinee_id, 1,7,43));
        array_push($ret, $this->select($examinee_id, 1,7,44));
        array_push($ret, $this->select($examinee_id, 1,8,45));
        array_push($ret, $this->select($examinee_id, 1,8,46));
        array_push($ret, $this->select($examinee_id, 1,8,47));
        array_push($ret, $this->select($examinee_id, 1,8,48));
        array_push($ret, $this->select($examinee_id, 1,8,49));
        array_push($ret, $this->select($examinee_id, 1,9,50));
        array_push($ret, $this->select($examinee_id, 1,9,51));
        array_push($ret, $this->select($examinee_id, 1,9,52));
        array_push($ret, $this->select($examinee_id, 1,9,53));
        array_push($ret, $this->select($examinee_id, 1,9,54));
        array_push($ret, $this->select($examinee_id, 1,10,55));
        array_push($ret, $this->select($examinee_id, 1,10,56));
        array_push($ret, $this->select($examinee_id, 1,10,57));
        array_push($ret, $this->select($examinee_id, 1,10,58));
        array_push($ret, $this->select($examinee_id, 1,10,59));
        array_push($ret, $this->select($examinee_id, 1,11,60));
        array_push($ret, $this->select($examinee_id, 1,11,61));
        array_push($ret, $this->select($examinee_id, 1,11,62));
        array_push($ret, $this->select($examinee_id, 1,11,63));
        array_push($ret, $this->select($examinee_id, 1,11,64));
        array_push($ret, $this->select($examinee_id, 1,11,65));
        array_push($ret, $this->select($examinee_id, 1,11,66));
        array_push($ret, $this->select($examinee_id, 1,11,67));
        array_push($ret, $this->select($examinee_id, 1,11,68));
        array_push($ret, $this->select($examinee_id, 1,12,69));
        array_push($ret, $this->select($examinee_id, 1,12,70));
        array_push($ret, $this->select($examinee_id, 1,13,71));
        array_push($ret, $this->select($examinee_id, 1,13,72));
        array_push($ret, $this->select($examinee_id, 1,13,73));
        array_push($ret, $this->select($examinee_id, 1,14,74));
        array_push($ret, $this->select($examinee_id, 1,14,75));

        array_push($ret, $this->select($examinee_id, 3,1,1));
        array_push($ret, $this->select($examinee_id, 3,1,2));
        array_push($ret, $this->select($examinee_id, 3,1,3));
        array_push($ret, $this->select($examinee_id, 3,1,4));
        array_push($ret, $this->select($examinee_id, 3,2,5));
        array_push($ret, $this->select($examinee_id, 3,2,1));
        array_push($ret, $this->select($examinee_id, 3,2,2));
        array_push($ret, $this->select($examinee_id, 3,2,3));
        array_push($ret, $this->select($examinee_id, 3,2,4));
        array_push($ret, $this->select($examinee_id, 3,2,5));
        array_push($ret, $this->select($examinee_id, 3,2,6));
        array_push($ret, $this->select($examinee_id, 3,3,1));
        array_push($ret, $this->select($examinee_id, 3,3,2));
        array_push($ret, $this->select($examinee_id, 3,3,3));
        array_push($ret, $this->select($examinee_id, 3,3,4));
        array_push($ret, $this->select($examinee_id, 3,3,5));
        array_push($ret, $this->select($examinee_id, 3,4,1));
        array_push($ret, $this->select($examinee_id, 3,4,2));
        array_push($ret, $this->select($examinee_id, 3,4,3));
        array_push($ret, $this->select($examinee_id, 3,4,4));
        array_push($ret, $this->select($examinee_id, 3,4,5));
        array_push($ret, $this->select($examinee_id, 3,4,6));
        array_push($ret, $this->select($examinee_id, 3,4,7));
        array_push($ret, $this->select($examinee_id, 3,4,8));
        array_push($ret, $this->select($examinee_id, 3,4,9));
        array_push($ret, $this->select($examinee_id, 3,4,10));
        array_push($ret, $this->select($examinee_id, 3,4,11));
        array_push($ret, $this->select($examinee_id, 3,4,12));
        array_push($ret, $this->select($examinee_id, 3,5,1));
        array_push($ret, $this->select($examinee_id, 3,5,2));
        array_push($ret, $this->select($examinee_id, 3,5,3));
        array_push($ret, $this->select($examinee_id, 3,5,4));

        return $ret;
    }

    function level3($examinee_id){
        $ret = [];
        array_push($ret, $this->select($examinee_id, 1,1,1));
        array_push($ret, $this->select($examinee_id, 1,1,2));
        array_push($ret, $this->select($examinee_id, 1,1,3));
        array_push($ret, $this->select($examinee_id, 1,1,4));
        array_push($ret, $this->select($examinee_id, 1,1,5));
        array_push($ret, $this->select($examinee_id, 1,1,6));
        array_push($ret, $this->select($examinee_id, 1,1,7));
        array_push($ret, $this->select($examinee_id, 1,1,8));
        array_push($ret, $this->select($examinee_id, 1,2,9));
        array_push($ret, $this->select($examinee_id, 1,2,10));
        array_push($ret, $this->select($examinee_id, 1,2,11));
        array_push($ret, $this->select($examinee_id, 1,2,12));
        array_push($ret, $this->select($examinee_id, 1,2,13));
        array_push($ret, $this->select($examinee_id, 1,2,14));
        array_push($ret, $this->select($examinee_id, 1,3,15));
        array_push($ret, $this->select($examinee_id, 1,3,16));
        array_push($ret, $this->select($examinee_id, 1,3,17));
        array_push($ret, $this->select($examinee_id, 1,3,18));
        array_push($ret, $this->select($examinee_id, 1,3,19));
        array_push($ret, $this->select($examinee_id, 1,3,20));
        array_push($ret, $this->select($examinee_id, 1,3,21));
        array_push($ret, $this->select($examinee_id, 1,3,22));
        array_push($ret, $this->select($examinee_id, 1,3,23));
        array_push($ret, $this->select($examinee_id, 1,3,24));
        array_push($ret, $this->select($examinee_id, 1,3,25));
        array_push($ret, $this->select($examinee_id, 1,4,26));
        array_push($ret, $this->select($examinee_id, 1,4,27));
        array_push($ret, $this->select($examinee_id, 1,4,28));
        array_push($ret, $this->select($examinee_id, 1,4,29));
        array_push($ret, $this->select($examinee_id, 1,4,30));
        array_push($ret, $this->select($examinee_id, 1,5,31));
        array_push($ret, $this->select($examinee_id, 1,5,32));
        array_push($ret, $this->select($examinee_id, 1,5,33));
        array_push($ret, $this->select($examinee_id, 1,5,34));
        array_push($ret, $this->select($examinee_id, 1,5,35));

        array_push($ret, $this->select($examinee_id, 2,1,1));
        array_push($ret, $this->select($examinee_id, 2,1,2));
        array_push($ret, $this->select($examinee_id, 2,1,3));
        array_push($ret, $this->select($examinee_id, 2,1,4));
        array_push($ret, $this->select($examinee_id, 2,1,5));
        array_push($ret, $this->select($examinee_id, 2,1,6));
        array_push($ret, $this->select($examinee_id, 2,1,7));
        array_push($ret, $this->select($examinee_id, 2,1,8));
        array_push($ret, $this->select($examinee_id, 2,1,9));
        array_push($ret, $this->select($examinee_id, 2,1,10));
        array_push($ret, $this->select($examinee_id, 2,1,11));
        array_push($ret, $this->select($examinee_id, 2,1,12));
        array_push($ret, $this->select($examinee_id, 2,1,13));
        array_push($ret, $this->select($examinee_id, 2,2,14));
        array_push($ret, $this->select($examinee_id, 2,2,15));
        array_push($ret, $this->select($examinee_id, 2,2,16));
        array_push($ret, $this->select($examinee_id, 2,2,17));
        array_push($ret, $this->select($examinee_id, 2,2,18));
        array_push($ret, $this->select($examinee_id, 2,3,19));
        array_push($ret, $this->select($examinee_id, 2,3,20));
        array_push($ret, $this->select($examinee_id, 2,3,21));
        array_push($ret, $this->select($examinee_id, 2,3,22));
        array_push($ret, $this->select($examinee_id, 2,3,23));
        array_push($ret, $this->select($examinee_id, 2,4,24));
        array_push($ret, $this->select($examinee_id, 2,4,25));
        array_push($ret, $this->select($examinee_id, 2,4,26));
        array_push($ret, $this->select($examinee_id, 2,4,27));
        array_push($ret, $this->select($examinee_id, 2,5,28));
        array_push($ret, $this->select($examinee_id, 2,5,29));
        array_push($ret, $this->select($examinee_id, 2,5,30));
        array_push($ret, $this->select($examinee_id, 2,5,31));
        array_push($ret, $this->select($examinee_id, 2,5,32));
        array_push($ret, $this->select($examinee_id, 2,5,33));
        array_push($ret, $this->select($examinee_id, 2,6,34));
        array_push($ret, $this->select($examinee_id, 2,6,35));
        array_push($ret, $this->select($examinee_id, 2,6,36));
        array_push($ret, $this->select($examinee_id, 2,6,37));
        array_push($ret, $this->select($examinee_id, 2,7,38));
        array_push($ret, $this->select($examinee_id, 2,7,39));

        array_push($ret, $this->select($examinee_id, 3,1,1));
        array_push($ret, $this->select($examinee_id, 3,1,2));
        array_push($ret, $this->select($examinee_id, 3,1,3));
        array_push($ret, $this->select($examinee_id, 3,1,4));
        array_push($ret, $this->select($examinee_id, 3,1,5));
        array_push($ret, $this->select($examinee_id, 3,1,6));
        array_push($ret, $this->select($examinee_id, 3,2,1));
        array_push($ret, $this->select($examinee_id, 3,2,2));
        array_push($ret, $this->select($examinee_id, 3,2,3));
        array_push($ret, $this->select($examinee_id, 3,2,4));
        array_push($ret, $this->select($examinee_id, 3,2,5));
        array_push($ret, $this->select($examinee_id, 3,2,6));
        array_push($ret, $this->select($examinee_id, 3,3,1));
        array_push($ret, $this->select($examinee_id, 3,3,2));
        array_push($ret, $this->select($examinee_id, 3,3,3));
        array_push($ret, $this->select($examinee_id, 3,4,1));
        array_push($ret, $this->select($examinee_id, 3,4,2));
        array_push($ret, $this->select($examinee_id, 3,4,3));
        array_push($ret, $this->select($examinee_id, 3,4,4));
        array_push($ret, $this->select($examinee_id, 3,5,1));
        array_push($ret, $this->select($examinee_id, 3,5,2));
        array_push($ret, $this->select($examinee_id, 3,5,3));
        array_push($ret, $this->select($examinee_id, 3,5,4));
        array_push($ret, $this->select($examinee_id, 3,5,5));
        array_push($ret, $this->select($examinee_id, 3,5,6));
        array_push($ret, $this->select($examinee_id, 3,5,7));
        array_push($ret, $this->select($examinee_id, 3,5,8));
        array_push($ret, $this->select($examinee_id, 3,5,9));

        return $ret;
    }

    function level4($examinee_id){
        $ret = [];
        array_push($ret, $this->select($examinee_id, 1, 1, 1));
        array_push($ret, $this->select($examinee_id, 1, 1, 2));
        array_push($ret, $this->select($examinee_id, 1, 1, 3));
        array_push($ret, $this->select($examinee_id, 1, 1, 4));
        array_push($ret, $this->select($examinee_id, 1, 1, 5));
        array_push($ret, $this->select($examinee_id, 1, 1, 6));
        array_push($ret, $this->select($examinee_id, 1, 1, 7));
        array_push($ret, $this->select($examinee_id, 1, 2, 8));
        array_push($ret, $this->select($examinee_id, 1, 2, 9));
        array_push($ret, $this->select($examinee_id, 1, 2, 10));
        array_push($ret, $this->select($examinee_id, 1, 2, 11));
        array_push($ret, $this->select($examinee_id, 1, 2, 12));
        array_push($ret, $this->select($examinee_id, 1, 3, 13));
        array_push($ret, $this->select($examinee_id, 1, 3, 14));
        array_push($ret, $this->select($examinee_id, 1, 3, 15));
        array_push($ret, $this->select($examinee_id, 1, 3, 16));
        array_push($ret, $this->select($examinee_id, 1, 3, 17));
        array_push($ret, $this->select($examinee_id, 1, 3, 18));
        array_push($ret, $this->select($examinee_id, 1, 3, 19));
        array_push($ret, $this->select($examinee_id, 1, 3, 20));
        array_push($ret, $this->select($examinee_id, 1, 4, 21));
        array_push($ret, $this->select($examinee_id, 1, 4, 22));
        array_push($ret, $this->select($examinee_id, 1, 4, 23));
        array_push($ret, $this->select($examinee_id, 1, 4, 24));
        array_push($ret, $this->select($examinee_id, 1, 5, 25));
        array_push($ret, $this->select($examinee_id, 1, 5, 26));
        array_push($ret, $this->select($examinee_id, 1, 5, 27));
        array_push($ret, $this->select($examinee_id, 1, 5, 28));

        array_push($ret, $this->select($examinee_id, 2, 1, 1));
        array_push($ret, $this->select($examinee_id, 2, 1, 2));
        array_push($ret, $this->select($examinee_id, 2, 1, 3));
        array_push($ret, $this->select($examinee_id, 2, 1, 4));
        array_push($ret, $this->select($examinee_id, 2, 1, 5));
        array_push($ret, $this->select($examinee_id, 2, 1, 6));
        array_push($ret, $this->select($examinee_id, 2, 1, 7));
        array_push($ret, $this->select($examinee_id, 2, 1, 8));
        array_push($ret, $this->select($examinee_id, 2, 1, 9));
        array_push($ret, $this->select($examinee_id, 2, 1, 10));
        array_push($ret, $this->select($examinee_id, 2, 1, 11));
        array_push($ret, $this->select($examinee_id, 2, 1, 12));
        array_push($ret, $this->select($examinee_id, 2, 1, 13));
        array_push($ret, $this->select($examinee_id, 2, 2, 14));
        array_push($ret, $this->select($examinee_id, 2, 2, 15));
        array_push($ret, $this->select($examinee_id, 2, 2, 16));
        array_push($ret, $this->select($examinee_id, 2, 2, 17));
        array_push($ret, $this->select($examinee_id, 2, 3, 18));
        array_push($ret, $this->select($examinee_id, 2, 3, 19));
        array_push($ret, $this->select($examinee_id, 2, 3, 20));
        array_push($ret, $this->select($examinee_id, 2, 3, 21));
        array_push($ret, $this->select($examinee_id, 2, 4, 22));
        array_push($ret, $this->select($examinee_id, 2, 4, 23));
        array_push($ret, $this->select($examinee_id, 2, 4, 24));
        array_push($ret, $this->select($examinee_id, 2, 5, 25));
        array_push($ret, $this->select($examinee_id, 2, 5, 26));
        array_push($ret, $this->select($examinee_id, 2, 5, 27));
        array_push($ret, $this->select($examinee_id, 2, 6, 28));
        array_push($ret, $this->select($examinee_id, 2, 6, 29));

        array_push($ret, $this->select($examinee_id, 3, 1, 1));
        array_push($ret, $this->select($examinee_id, 3, 1, 2));
        array_push($ret, $this->select($examinee_id, 3, 1, 3));
        array_push($ret, $this->select($examinee_id, 3, 1, 4));
        array_push($ret, $this->select($examinee_id, 3, 1, 5));
        array_push($ret, $this->select($examinee_id, 3, 1, 6));
        array_push($ret, $this->select($examinee_id, 3, 1, 7));
        array_push($ret, $this->select($examinee_id, 3, 1, 8));
        array_push($ret, $this->select($examinee_id, 3, 2, 1));
        array_push($ret, $this->select($examinee_id, 3, 2, 2));
        array_push($ret, $this->select($examinee_id, 3, 2, 3));
        array_push($ret, $this->select($examinee_id, 3, 2, 4));
        array_push($ret, $this->select($examinee_id, 3, 2, 5));
        array_push($ret, $this->select($examinee_id, 3, 2, 6));
        array_push($ret, $this->select($examinee_id, 3, 2, 7));
        array_push($ret, $this->select($examinee_id, 3, 3, 1));
        array_push($ret, $this->select($examinee_id, 3, 3, 2));
        array_push($ret, $this->select($examinee_id, 3, 3, 3));
        array_push($ret, $this->select($examinee_id, 3, 3, 4));
        array_push($ret, $this->select($examinee_id, 3, 3, 5));
        array_push($ret, $this->select($examinee_id, 3, 4, 1));
        array_push($ret, $this->select($examinee_id, 3, 4, 2));
        array_push($ret, $this->select($examinee_id, 3, 4, 3));
        array_push($ret, $this->select($examinee_id, 3, 4, 4));
        array_push($ret, $this->select($examinee_id, 3, 4, 5));
        array_push($ret, $this->select($examinee_id, 3, 4, 6));
        array_push($ret, $this->select($examinee_id, 3, 4, 7));
        array_push($ret, $this->select($examinee_id, 3, 4, 8));

        return $ret;
    }

    function level5($examinee_id){
        $ret = [];
        array_push($ret, $this->select($examinee_id, 1, 1, 1));
        array_push($ret, $this->select($examinee_id, 1, 1, 2));
        array_push($ret, $this->select($examinee_id, 1, 1, 3));
        array_push($ret, $this->select($examinee_id, 1, 1, 4));
        array_push($ret, $this->select($examinee_id, 1, 1, 5));
        array_push($ret, $this->select($examinee_id, 1, 1, 6));
        array_push($ret, $this->select($examinee_id, 1, 1, 7));
        array_push($ret, $this->select($examinee_id, 1, 2, 8));
        array_push($ret, $this->select($examinee_id, 1, 2, 9));
        array_push($ret, $this->select($examinee_id, 1, 2, 10));
        array_push($ret, $this->select($examinee_id, 1, 2, 11));
        array_push($ret, $this->select($examinee_id, 1, 2, 12));
        array_push($ret, $this->select($examinee_id, 1, 3, 13));
        array_push($ret, $this->select($examinee_id, 1, 3, 14));
        array_push($ret, $this->select($examinee_id, 1, 3, 15));
        array_push($ret, $this->select($examinee_id, 1, 3, 16));
        array_push($ret, $this->select($examinee_id, 1, 3, 17));
        array_push($ret, $this->select($examinee_id, 1, 3, 18));
        array_push($ret, $this->select($examinee_id, 1, 4, 19));
        array_push($ret, $this->select($examinee_id, 1, 4, 20));
        array_push($ret, $this->select($examinee_id, 1, 4, 21));

        array_push($ret, $this->select($examinee_id, 2, 1, 1));
        array_push($ret, $this->select($examinee_id, 2, 1, 2));
        array_push($ret, $this->select($examinee_id, 2, 1, 3));
        array_push($ret, $this->select($examinee_id, 2, 1, 4));
        array_push($ret, $this->select($examinee_id, 2, 1, 5));
        array_push($ret, $this->select($examinee_id, 2, 1, 6));
        array_push($ret, $this->select($examinee_id, 2, 1, 7));
        array_push($ret, $this->select($examinee_id, 2, 1, 8));
        array_push($ret, $this->select($examinee_id, 2, 1, 9));
        array_push($ret, $this->select($examinee_id, 2, 2, 10));
        array_push($ret, $this->select($examinee_id, 2, 2, 11));
        array_push($ret, $this->select($examinee_id, 2, 2, 12));
        array_push($ret, $this->select($examinee_id, 2, 2, 13));
        array_push($ret, $this->select($examinee_id, 2, 3, 14));
        array_push($ret, $this->select($examinee_id, 2, 3, 15));
        array_push($ret, $this->select($examinee_id, 2, 3, 16));
        array_push($ret, $this->select($examinee_id, 2, 3, 17));
        array_push($ret, $this->select($examinee_id, 2, 4, 18));
        array_push($ret, $this->select($examinee_id, 2, 4, 19));
        array_push($ret, $this->select($examinee_id, 2, 5, 20));
        array_push($ret, $this->select($examinee_id, 2, 5, 21));
        array_push($ret, $this->select($examinee_id, 2, 6, 22));

        array_push($ret, $this->select($examinee_id, 3, 1, 1));
        array_push($ret, $this->select($examinee_id, 3, 1, 2));
        array_push($ret, $this->select($examinee_id, 3, 1, 3));
        array_push($ret, $this->select($examinee_id, 3, 1, 4));
        array_push($ret, $this->select($examinee_id, 3, 1, 5));
        array_push($ret, $this->select($examinee_id, 3, 1, 6));
        array_push($ret, $this->select($examinee_id, 3, 1, 7));
        array_push($ret, $this->select($examinee_id, 3, 2, 1));
        array_push($ret, $this->select($examinee_id, 3, 2, 2));
        array_push($ret, $this->select($examinee_id, 3, 2, 3));
        array_push($ret, $this->select($examinee_id, 3, 2, 4));
        array_push($ret, $this->select($examinee_id, 3, 2, 5));
        array_push($ret, $this->select($examinee_id, 3, 2, 6));
        array_push($ret, $this->select($examinee_id, 3, 3, 1));
        array_push($ret, $this->select($examinee_id, 3, 3, 2));
        array_push($ret, $this->select($examinee_id, 3, 3, 3));
        array_push($ret, $this->select($examinee_id, 3, 3, 4));
        array_push($ret, $this->select($examinee_id, 3, 3, 5));
        array_push($ret, $this->select($examinee_id, 3, 4, 1));
        array_push($ret, $this->select($examinee_id, 3, 4, 2));
        array_push($ret, $this->select($examinee_id, 3, 4, 3));
        array_push($ret, $this->select($examinee_id, 3, 4, 4));
        array_push($ret, $this->select($examinee_id, 3, 4, 5));
        array_push($ret, $this->select($examinee_id, 3, 4, 6));

        return $ret;
    }

    function select($examinee_id, $section, $qid, $number){
        $ret = null;
        $level = substr($examinee_id, 9, 1);
        $rec = AnswerRecord::where('examinee_number', $examinee_id)
                     ->where('level', $level)
                     ->where('section', $section)
                     ->where('question', $qid)
                     ->where('number', $number)
                     ->first();

        if($rec != null){
            if($level == 1 or $level == 2){
                if($rec['section'] == 3){
                    $rec['section'] = 2;
                }
            }
            $ret = new AnswerDownloadRecord(
                     $rec['examinee_number'], $rec['level'], $rec['question_id'], $rec['section'], $rec['question'], $rec['number'],
                     $rec['choice'], $rec['pass_fail']
            );
        }else{
            if($level == 1 or $level == 2){
                if($section == 3){
                    $section = 2;
                }
            }
            $ret = new AnswerDownloadRecord($examinee_id, $level, null, $section, $qid, $number, null, 0 );
        }
        return $ret;
    }
}

?>