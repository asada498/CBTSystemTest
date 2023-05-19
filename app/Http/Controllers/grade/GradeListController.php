<?php

namespace App\Http\Controllers\grade;

use Auth;
use Session;
use Validator;
use DB;
use App\ExamineeList;
use App\GradeRecord;
use App\GradeCondition;
use App\TranscriptRecord;
use App\ScoreSummary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GradeListController extends Controller
{
    function search(Request $request)
    {
        $examineeId = $request->get('examineeId');
        $targetDate = $request->get('targetDate');
        $fromDate = $request->get('fromDate');
        $toDate = $request->get('toDate');
        $op = $request->get('op');

        $condition = new GradeCondition($examineeId, $targetDate, $fromDate, $toDate, $op, '0');
        Session::put('GradeListCondition', $condition);

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

        $query->orderBy('examinee_id');
        $list = $query->get();

        if($op == 'search'){
            $ret = [];
            foreach($list as $ele){
                $sum = round($ele['sec1_score'])+round($ele['sec2_score'])+round($ele['sec3_score']);
                if($sum > 180){
                    $sum = 180;
                }
                $level = substr($ele['examinee_id'], 9, 1);
                if($level == 1 or $level == 2){
                    $ele['sec2_score'] = $ele['sec3_score'];
                    $ele['sec3_score'] = 0;
                }
                $rec = new GradeRecord(
                    $ele['examinee_id'], $ele['name'], $ele['birthDay'],
                    $ele['grades_certificate'], $ele['pass_certificate'],
                    $ele['pass_fail'], round($ele['anchor_score']), $ele['anchor_pass_rate'],
                    $sum, round($ele['sec1_score']), round($ele['sec2_score']), round($ele['sec3_score'])
                );
                array_push($ret, $rec);
            }
            return view('grade/gradeList', ['data' => $ret, 'condition' => $condition]);
        }

        $headers = [
            'Content-type' => 'text/csv;',
            'Content-Disposition' => 'attachment; filename=grade_list.csv'
        ];

        $callback = function() use($list) {
            ob_clean();
            $handle = fopen('php://output', 'w');
            foreach($list as $rec) {
                $result = [];
                switch($rec['q']){
                case 5:
                    $result = $this->transcript5($rec);
                    break;
                case 4:
                    $result = $this->transcript4($rec);
                    break;
                case 3:
                    $result = $this->transcript3($rec);
                    break;
                case 2:
                    $result = $this->transcript2($rec);
                    break;
                case 1:
                    $result = $this->transcript1($rec);
                    break;
                }

                $csv = [
                    $result->getJukenBangou(),
                    $result->getHeikinSougou(),
                    $result->getHeikin1(),
                    $result->getHeikin2(),
                    $result->getHeikin3(),
                    $result->getHeikin101(),
                    $result->getHeikin102(),
                    $result->getHeikin103(),
                    $result->getHeikin104(),
                    $result->getHeikin105(),
                    $result->getHeikin106(),
                    $result->getHeikin107(),
                    $result->getHeikin108(),
                    $result->getHeikin109(),
                    $result->getHeikin110(),
                    $result->getHeikin201(),
                    $result->getHeikin202(),
                    $result->getHeikin203(),
                    $result->getHeikin204(),
                    $result->getHeikin205(),
                    $result->getHeikin206(),
                    $result->getHeikin207(),
                    $result->getHeikin208(),
                    $result->getHeikin209(),
                    $result->getHeikin210(),
                    $result->getHeikin301(),
                    $result->getHeikin302(),
                    $result->getHeikin303(),
                    $result->getHeikin304(),
                    $result->getHeikin305(),
                    $result->getHeikin306(),
                    $result->getHeikin307(),
                    $result->getHeikin308(),
                    $result->getHeikin309(),
                    $result->getHeikin310(),
                    $result->getHaitenSougou(),
                    $result->getHaiten1(),
                    $result->getHaiten2(),
                    $result->getHaiten3(),
                    $result->getHaiten101(),
                    $result->getHaiten102(),
                    $result->getHaiten103(),
                    $result->getHaiten104(),
                    $result->getHaiten105(),
                    $result->getHaiten106(),
                    $result->getHaiten107(),
                    $result->getHaiten108(),
                    $result->getHaiten109(),
                    $result->getHaiten110(),
                    $result->getHaiten201(),
                    $result->getHaiten202(),
                    $result->getHaiten203(),
                    $result->getHaiten204(),
                    $result->getHaiten205(),
                    $result->getHaiten206(),
                    $result->getHaiten207(),
                    $result->getHaiten208(),
                    $result->getHaiten209(),
                    $result->getHaiten210(),
                    $result->getHaiten301(),
                    $result->getHaiten302(),
                    $result->getHaiten303(),
                    $result->getHaiten304(),
                    $result->getHaiten305(),
                    $result->getHaiten306(),
                    $result->getHaiten307(),
                    $result->getHaiten308(),
                    $result->getHaiten309(),
                    $result->getHaiten310(),
                    $result->getBunyaMei1(),
                    $result->getBunyaMei2(),
                    $result->getBunyaMei3(),
                    $result->getBunyaMei4(),
                    $result->getBunyaMei5(),
                    $result->getMondai101(),
                    $result->getMondai102(),
                    $result->getMondai103(),
                    $result->getMondai104(),
                    $result->getMondai105(),
                    $result->getMondai106(),
                    $result->getMondai107(),
                    $result->getMondai108(),
                    $result->getMondai109(),
                    $result->getMondai110(),
                    $result->getMondai201(),
                    $result->getMondai202(),
                    $result->getMondai203(),
                    $result->getMondai204(),
                    $result->getMondai205(),
                    $result->getMondai206(),
                    $result->getMondai207(),
                    $result->getMondai208(),
                    $result->getMondai209(),
                    $result->getMondai210(),
                    $result->getMondai301(),
                    $result->getMondai302(),
                    $result->getMondai303(),
                    $result->getMondai304(),
                    $result->getMondai305(),
                    $result->getMondai306(),
                    $result->getMondai307(),
                    $result->getMondai308(),
                    $result->getMondai309(),
                    $result->getMondai310(),
                    $result->getJukenBangou2(),
                    $result->getHyoukaSougou(),
                    $result->getHyouka1(),
                    $result->getHyouka2(),
                    $result->getHyouka3(),
                    $result->getGouhiHantei(),
                    $result->getTokutenSougou(),
                    $result->getTokuten1(),
                    $result->getTokuten2(),
                    $result->getTokuten3(),
                    $result->getTokuten101(),
                    $result->getTokuten102(),
                    $result->getTokuten103(),
                    $result->getTokuten104(),
                    $result->getTokuten105(),
                    $result->getTokuten106(),
                    $result->getTokuten107(),
                    $result->getTokuten108(),
                    $result->getTokuten109(),
                    $result->getTokuten110(),
                    $result->getTokuten201(),
                    $result->getTokuten202(),
                    $result->getTokuten203(),
                    $result->getTokuten204(),
                    $result->getTokuten205(),
                    $result->getTokuten206(),
                    $result->getTokuten207(),
                    $result->getTokuten208(),
                    $result->getTokuten209(),
                    $result->getTokuten210(),
                    $result->getTokuten301(),
                    $result->getTokuten302(),
                    $result->getTokuten303(),
                    $result->getTokuten304(),
                    $result->getTokuten305(),
                    $result->getTokuten306(),
                    $result->getTokuten307(),
                    $result->getTokuten308(),
                    $result->getTokuten309(),
                    $result->getTokuten310()
                ];
                mb_convert_variables('SJIS-win', 'UTF-8', $csv);
                fputcsv($handle, $csv);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    function transcript5($rec){

        $result = new TranscriptRecord();

        $result->setJukenBangou($rec['examinee_id']);
        $result->setHeikinSougou(0);
        $result->setHeikin1(0);
        $result->setHeikin2(0);
        $result->setHeikin3(0);
        $result->setHeikin101(0);
        $result->setHeikin102(0);
        $result->setHeikin103(0);
        $result->setHeikin104(0);
        $result->setHeikin105('');
        $result->setHeikin106('');
        $result->setHeikin107('');
        $result->setHeikin108('');
        $result->setHeikin109('');
        $result->setHeikin110('');
        $result->setHeikin201(0);
        $result->setHeikin202(0);
        $result->setHeikin203(0);
        $result->setHeikin204(0);
        $result->setHeikin205(0);
        $result->setHeikin206(0);
        $result->setHeikin207('');
        $result->setHeikin208('');
        $result->setHeikin209('');
        $result->setHeikin210('');
        $result->setHeikin301(0);
        $result->setHeikin302(0);
        $result->setHeikin303(0);
        $result->setHeikin304(0);
        $result->setHeikin305('');
        $result->setHeikin306('');
        $result->setHeikin307('');
        $result->setHeikin308('');
        $result->setHeikin309('');
        $result->setHeikin310('');
        $result->setHaitenSougou(180);
        $result->setHaiten1(40);
        $result->setHaiten2(80);
        $result->setHaiten3(60);
        $result->setHaiten101(11);
        $result->setHaiten102(9);
        $result->setHaiten103(10);
        $result->setHaiten104(10);
        $result->setHaiten105('');
        $result->setHaiten106('');
        $result->setHaiten107('');
        $result->setHaiten108('');
        $result->setHaiten109('');
        $result->setHaiten110('');
        $result->setHaiten201(16);
        $result->setHaiten202(11);
        $result->setHaiten203(14);
        $result->setHaiten204(12);
        $result->setHaiten205(16);
        $result->setHaiten206(11);
        $result->setHaiten207('');
        $result->setHaiten208('');
        $result->setHaiten209('');
        $result->setHaiten210('');
        $result->setHaiten301(18);
        $result->setHaiten302(18);
        $result->setHaiten303(12);
        $result->setHaiten304(12);
        $result->setHaiten305('');
        $result->setHaiten306('');
        $result->setHaiten307('');
        $result->setHaiten308('');
        $result->setHaiten309('');
        $result->setHaiten310('');
        $result->setBunyaMei1('言語知識 (文字･語彙)');
        $result->setBunyaMei2('言語知識 (文法)・読解');
        $result->setBunyaMei3('聴解');
        $result->setBunyaMei4('');
        $result->setBunyaMei5('');
        $result->setMondai101('問題1');
        $result->setMondai102('問題2');
        $result->setMondai103('問題3');
        $result->setMondai104('問題4');
        $result->setMondai105('');
        $result->setMondai106('');
        $result->setMondai107('');
        $result->setMondai108('');
        $result->setMondai109('');
        $result->setMondai110('');
        $result->setMondai201('問題1');
        $result->setMondai202('問題2');
        $result->setMondai203('問題3');
        $result->setMondai204('問題4');
        $result->setMondai205('問題5');
        $result->setMondai206('問題6');
        $result->setMondai207('');
        $result->setMondai208('');
        $result->setMondai209('');
        $result->setMondai210('');
        $result->setMondai301('問題1');
        $result->setMondai302('問題2');
        $result->setMondai303('問題3');
        $result->setMondai304('問題4');
        $result->setMondai305('');
        $result->setMondai306('');
        $result->setMondai307('');
        $result->setMondai308('');
        $result->setMondai309('');
        $result->setMondai310('');
        $result->setJukenBangou2($rec['examinee_id']);
        $result->setHyoukaSougou('A');  // TODO
        $result->setHyouka1('B');       // TODO
        $result->setHyouka2('C');       // TODO
        $result->setHyouka3('D');       // TODO
        if($rec['pass_fail'] == 1){
            $gouhiHantei = '***合格***';
        }else{
            $gouhiHantei = '********';
        }
        $result->setGouhiHantei($gouhiHantei);
        $sum = round($rec['sec1_score'])+round($rec['sec2_score'])+round($rec['sec3_score']);
        if($sum > 180){
            $sum = 180;
        }
        $result->setTokutenSougou($sum);
        $result->setTokuten1(round($rec['sec1_score']));
        $result->setTokuten2(round($rec['sec2_score']));
        $result->setTokuten3(round($rec['sec3_score']));
        $tokuten101 = 0;
        $tokuten102 = 0;
        $tokuten103 = 0;
        $tokuten104 = 0;
        $tokuten201 = 0;
        $tokuten202 = 0;
        $tokuten203 = 0;
        $tokuten204 = 0;
        $tokuten205 = 0;
        $tokuten206 = 0;
        $tokuten301 = 0;
        $tokuten302 = 0;
        $tokuten303 = 0;
        $tokuten304 = 0;
        $scoreSummary = ScoreSummary::where('examinee_number', $rec['examinee_id'])->first();
        if($scoreSummary != null){
            $tokuten101 = $scoreSummary['s1_q1_correct']*5.5/60*120/7;
            $tokuten102 = $scoreSummary['s1_q2_correct']*4.5/60*120/5;
            $tokuten103 = $scoreSummary['s1_q3_correct']*5.0/60*120/6;
            $tokuten104 = $scoreSummary['s1_q4_correct']*5.0/60*120/3;
            $tokuten201 = $scoreSummary['s2_q1_correct']*8.0/60*120/9;
            $tokuten202 = $scoreSummary['s2_q2_correct']*5.5/60*120/4;
            $tokuten203 = $scoreSummary['s2_q3_correct']*7.0/60*120/4;
            $tokuten204 = $scoreSummary['s2_q4_correct']*6.0/60*120/2;
            $tokuten205 = $scoreSummary['s2_q5_correct']*8.0/60*120/2;
            $tokuten206 = $scoreSummary['s2_q6_correct']*5.5/60*120/1;
            $tokuten301 = $scoreSummary['s3_q1_correct']*9.0/30*60/7;
            $tokuten302 = $scoreSummary['s3_q2_correct']*9.0/30*60/6;
            $tokuten303 = $scoreSummary['s3_q3_correct']*6.0/30*60/5;
            $tokuten304 = $scoreSummary['s3_q4_correct']*6.0/30*60/6;
        }
        $result->setTokuten101($tokuten101);
        $result->setTokuten102($tokuten102);
        $result->setTokuten103($tokuten103);
        $result->setTokuten104($tokuten104);
        $result->setTokuten105('');
        $result->setTokuten106('');
        $result->setTokuten107('');
        $result->setTokuten108('');
        $result->setTokuten109('');
        $result->setTokuten110('');
        $result->setTokuten201($tokuten201);
        $result->setTokuten202($tokuten202);
        $result->setTokuten203($tokuten203);
        $result->setTokuten204($tokuten204);
        $result->setTokuten205($tokuten205);
        $result->setTokuten206($tokuten206);
        $result->setTokuten207('');
        $result->setTokuten208('');
        $result->setTokuten209('');
        $result->setTokuten210('');
        $result->setTokuten301($tokuten301);
        $result->setTokuten302($tokuten302);
        $result->setTokuten303($tokuten303);
        $result->setTokuten304($tokuten304);
        $result->setTokuten305('');
        $result->setTokuten306('');
        $result->setTokuten307('');
        $result->setTokuten308('');
        $result->setTokuten309('');
        $result->setTokuten310('');
        return $result;
    }

    function transcript4($rec){

        $result = new TranscriptRecord();

        $result->setJukenBangou($rec['examinee_id']);
        $result->setHeikinSougou(0);
        $result->setHeikin1(0);
        $result->setHeikin2(0);
        $result->setHeikin3(0);
        $result->setHeikin101(0);
        $result->setHeikin102(0);
        $result->setHeikin103(0);
        $result->setHeikin104(0);
        $result->setHeikin105(0);
        $result->setHeikin106('');
        $result->setHeikin107('');
        $result->setHeikin108('');
        $result->setHeikin109('');
        $result->setHeikin110('');
        $result->setHeikin201(0);
        $result->setHeikin202(0);
        $result->setHeikin203(0);
        $result->setHeikin204(0);
        $result->setHeikin205(0);
        $result->setHeikin206(0);
        $result->setHeikin207('');
        $result->setHeikin208('');
        $result->setHeikin209('');
        $result->setHeikin210('');
        $result->setHeikin301(0);
        $result->setHeikin302(0);
        $result->setHeikin303(0);
        $result->setHeikin304(0);
        $result->setHeikin305('');
        $result->setHeikin306('');
        $result->setHeikin307('');
        $result->setHeikin308('');
        $result->setHeikin309('');
        $result->setHeikin310('');
        $result->setHaitenSougou(180);
        $result->setHaiten1(37.5);
        $result->setHaiten2(82.5);
        $result->setHaiten3(60);
        $result->setHaiten101(5.25/80*120);
        $result->setHaiten102(3.75/80*120);
        $result->setHaiten103(9);
        $result->setHaiten104(7.5);
        $result->setHaiten105(7.5);
        $result->setHaiten106('');
        $result->setHaiten107('');
        $result->setHaiten108('');
        $result->setHaiten109('');
        $result->setHaiten110('');
        $result->setHaiten201(20.25);
        $result->setHaiten202(8.25);
        $result->setHaiten203(12);
        $result->setHaiten204(15.75);
        $result->setHaiten205(17.25);
        $result->setHaiten206(9);
        $result->setHaiten207('');
        $result->setHaiten208('');
        $result->setHaiten209('');
        $result->setHaiten210('');
        $result->setHaiten301(18);
        $result->setHaiten302(18);
        $result->setHaiten303(6.5/35*60);
        $result->setHaiten304(7.5/35*60);
        $result->setHaiten305('');
        $result->setHaiten306('');
        $result->setHaiten307('');
        $result->setHaiten308('');
        $result->setHaiten309('');
        $result->setHaiten310('');
        $result->setBunyaMei1('言語知識 (文字･語彙)');
        $result->setBunyaMei2('言語知識 (文法)・読解');
        $result->setBunyaMei3('聴解');
        $result->setBunyaMei4('');
        $result->setBunyaMei5('');
        $result->setMondai101('問題1');
        $result->setMondai102('問題2');
        $result->setMondai103('問題3');
        $result->setMondai104('問題4');
        $result->setMondai105('問題5');
        $result->setMondai106('');
        $result->setMondai107('');
        $result->setMondai108('');
        $result->setMondai109('');
        $result->setMondai110('');
        $result->setMondai201('問題1');
        $result->setMondai202('問題2');
        $result->setMondai203('問題3');
        $result->setMondai204('問題4');
        $result->setMondai205('問題5');
        $result->setMondai206('問題6');
        $result->setMondai207('');
        $result->setMondai208('');
        $result->setMondai209('');
        $result->setMondai210('');
        $result->setMondai301('問題1');
        $result->setMondai302('問題2');
        $result->setMondai303('問題3');
        $result->setMondai304('問題4');
        $result->setMondai305('');
        $result->setMondai306('');
        $result->setMondai307('');
        $result->setMondai308('');
        $result->setMondai309('');
        $result->setMondai310('');
        $result->setJukenBangou2($rec['examinee_id']);
        $result->setHyoukaSougou('A');  // TODO
        $result->setHyouka1('B');       // TODO
        $result->setHyouka2('C');       // TODO
        $result->setHyouka3('D');       // TODO
        if($rec['pass_fail'] == 1){
            $gouhiHantei = '***合格***';
        }else{
            $gouhiHantei = '********';
        }
        $result->setGouhiHantei($gouhiHantei);
        $sum = round($rec['sec1_score'])+round($rec['sec2_score'])+round($rec['sec3_score']);
        if($sum > 180){
            $sum = 180;
        }
        $result->setTokutenSougou($sum);
        $result->setTokuten1(round($rec['sec1_score']));
        $result->setTokuten2(round($rec['sec2_score']));
        $result->setTokuten3(round($rec['sec3_score']));
        $tokuten101 = 0;
        $tokuten102 = 0;
        $tokuten103 = 0;
        $tokuten104 = 0;
        $tokuten105 = 0;
        $tokuten201 = 0;
        $tokuten202 = 0;
        $tokuten203 = 0;
        $tokuten204 = 0;
        $tokuten205 = 0;
        $tokuten206 = 0;
        $tokuten301 = 0;
        $tokuten302 = 0;
        $tokuten303 = 0;
        $tokuten304 = 0;
        $scoreSummary = ScoreSummary::where('examinee_number', $rec['examinee_id'])->first();
        if($scoreSummary != null){
            $tokuten101 = $scoreSummary['s1_q1_correct']*5.25/80*120/7;
            $tokuten102 = $scoreSummary['s1_q2_correct']*3.75/80*120/5;
            $tokuten103 = $scoreSummary['s1_q3_correct']*6.00/80*120/8;
            $tokuten104 = $scoreSummary['s1_q4_correct']*5.00/80*120/4;
            $tokuten105 = $scoreSummary['s1_q5_correct']*5.00/80*120/4;
            $tokuten201 = $scoreSummary['s2_q1_correct']*13.5/80*120/13;
            $tokuten202 = $scoreSummary['s2_q2_correct']* 5.5/80*120/4;
            $tokuten203 = $scoreSummary['s2_q3_correct']* 8.0/80*120/4;
            $tokuten204 = $scoreSummary['s2_q4_correct']*10.5/80*120/3;
            $tokuten205 = $scoreSummary['s2_q5_correct']*11.5/80*120/3;
            $tokuten206 = $scoreSummary['s2_q6_correct']* 6.0/80*120/2;
            $tokuten301 = $scoreSummary['s3_q1_correct']*10.5/35*60/8;
            $tokuten302 = $scoreSummary['s3_q2_correct']*10.5/35*60/7;
            $tokuten303 = $scoreSummary['s3_q3_correct']* 6.5/35*60/5;
            $tokuten304 = $scoreSummary['s3_q4_correct']* 7.5/35*60/8;
        }
        $result->setTokuten101($tokuten101);
        $result->setTokuten102($tokuten102);
        $result->setTokuten103($tokuten103);
        $result->setTokuten104($tokuten104);
        $result->setTokuten105($tokuten105);
        $result->setTokuten106('');
        $result->setTokuten107('');
        $result->setTokuten108('');
        $result->setTokuten109('');
        $result->setTokuten110('');
        $result->setTokuten201($tokuten201);
        $result->setTokuten202($tokuten202);
        $result->setTokuten203($tokuten203);
        $result->setTokuten204($tokuten204);
        $result->setTokuten205($tokuten205);
        $result->setTokuten206($tokuten206);
        $result->setTokuten207('');
        $result->setTokuten208('');
        $result->setTokuten209('');
        $result->setTokuten210('');
        $result->setTokuten301($tokuten301);
        $result->setTokuten302($tokuten302);
        $result->setTokuten303($tokuten303);
        $result->setTokuten304($tokuten304);
        $result->setTokuten305('');
        $result->setTokuten306('');
        $result->setTokuten307('');
        $result->setTokuten308('');
        $result->setTokuten309('');
        $result->setTokuten310('');
        return $result;
    }

    function transcript3($rec){

        $result = new TranscriptRecord();

        $result->setJukenBangou($rec['examinee_id']);
        $result->setHeikinSougou(0);
        $result->setHeikin1(0);
        $result->setHeikin2(0);
        $result->setHeikin3(0);
        $result->setHeikin101(0);
        $result->setHeikin102(0);
        $result->setHeikin103(0);
        $result->setHeikin104(0);
        $result->setHeikin105(0);
        $result->setHeikin106('');
        $result->setHeikin107('');
        $result->setHeikin108('');
        $result->setHeikin109('');
        $result->setHeikin110('');
        $result->setHeikin201(0);
        $result->setHeikin202(0);
        $result->setHeikin203(0);
        $result->setHeikin204(0);
        $result->setHeikin205(0);
        $result->setHeikin206(0);
        $result->setHeikin207(0);
        $result->setHeikin208('');
        $result->setHeikin209('');
        $result->setHeikin210('');
        $result->setHeikin301(0);
        $result->setHeikin302(0);
        $result->setHeikin303(0);
        $result->setHeikin304(0);
        $result->setHeikin305(0);
        $result->setHeikin306('');
        $result->setHeikin307('');
        $result->setHeikin308('');
        $result->setHeikin309('');
        $result->setHeikin310('');
        $result->setHaitenSougou(180);
        $result->setHaiten1(5.5*55*60 + 4.0*55*60 + 7.5/55*60 + 7.5/55*60 + 7.5/55*60);
        $result->setHaiten2(8.5/55*60 + 6/55*60 + 8.5/55*60 + 60);
        $result->setHaiten3(60);
        $result->setHaiten101(5.5/55*60);
        $result->setHaiten102(4  /55*60);
        $result->setHaiten103(7.5/55*60);
        $result->setHaiten104(7.5/55*60);
        $result->setHaiten105(7.5/55*60);
        $result->setHaiten106('');
        $result->setHaiten107('');
        $result->setHaiten108('');
        $result->setHaiten109('');
        $result->setHaiten110('');
        $result->setHaiten201(8.5/55*60);
        $result->setHaiten202(6  /55*60);
        $result->setHaiten203(8.5/55*60);
        $result->setHaiten204(12 /45*60);
        $result->setHaiten205(13 /45*60);
        $result->setHaiten206(12 /45*60);
        $result->setHaiten207(8  /45*60);
        $result->setHaiten208('');
        $result->setHaiten209('');
        $result->setHaiten210('');
        $result->setHaiten301(16.5);
        $result->setHaiten302(16.5);
        $result->setHaiten303(9);
        $result->setHaiten304(7.95);
        $result->setHaiten305(10.05);
        $result->setHaiten306('');
        $result->setHaiten307('');
        $result->setHaiten308('');
        $result->setHaiten309('');
        $result->setHaiten310('');
        $result->setBunyaMei1('言語知識 (文字･語彙)');
        $result->setBunyaMei2('言語知識 (文法)・読解');
        $result->setBunyaMei3('聴解');
        $result->setBunyaMei4('');
        $result->setBunyaMei5('');
        $result->setMondai101('問題1');
        $result->setMondai102('問題2');
        $result->setMondai103('問題3');
        $result->setMondai104('問題4');
        $result->setMondai105('問題5');
        $result->setMondai106('');
        $result->setMondai107('');
        $result->setMondai108('');
        $result->setMondai109('');
        $result->setMondai110('');
        $result->setMondai201('問題1');
        $result->setMondai202('問題2');
        $result->setMondai203('問題3');
        $result->setMondai204('問題4');
        $result->setMondai205('問題5');
        $result->setMondai206('問題6');
        $result->setMondai207('問題7');
        $result->setMondai208('');
        $result->setMondai209('');
        $result->setMondai210('');
        $result->setMondai301('問題1');
        $result->setMondai302('問題2');
        $result->setMondai303('問題3');
        $result->setMondai304('問題4');
        $result->setMondai305('問題5');
        $result->setMondai306('');
        $result->setMondai307('');
        $result->setMondai308('');
        $result->setMondai309('');
        $result->setMondai310('');
        $result->setJukenBangou2($rec['examinee_id']);
        $result->setHyoukaSougou('A');  // TODO
        $result->setHyouka1('B');       // TODO
        $result->setHyouka2('C');       // TODO
        $result->setHyouka3('D');       // TODO
        if($rec['pass_fail'] == 1){
            $gouhiHantei = '***合格***';
        }else{
            $gouhiHantei = '********';
        }
        $result->setGouhiHantei($gouhiHantei);
        $sum = round($rec['sec1_score'])+round($rec['sec2_score'])+round($rec['sec3_score']);
        if($sum > 180){
            $sum = 180;
        }
        $result->setTokutenSougou($sum);
        $result->setTokuten1(round($rec['sec1_score']));
        $result->setTokuten2(round($rec['sec2_score']));
        $result->setTokuten3(round($rec['sec3_score']));
        $tokuten101 = 0;
        $tokuten102 = 0;
        $tokuten103 = 0;
        $tokuten104 = 0;
        $tokuten105 = 0;
        $tokuten201 = 0;
        $tokuten202 = 0;
        $tokuten203 = 0;
        $tokuten204 = 0;
        $tokuten205 = 0;
        $tokuten206 = 0;
        $tokuten207 = 0;
        $tokuten301 = 0;
        $tokuten302 = 0;
        $tokuten303 = 0;
        $tokuten304 = 0;
        $tokuten305 = 0;
        $scoreSummary = ScoreSummary::where('examinee_number', $rec['examinee_id'])->first();
        if($scoreSummary != null){
            $tokuten101 = $scoreSummary['s1_q1_correct']*5.5/55*60/ 8;
            $tokuten102 = $scoreSummary['s1_q2_correct']*4.0/55*60/ 6;
            $tokuten103 = $scoreSummary['s1_q3_correct']*7.5/55*60/11;
            $tokuten104 = $scoreSummary['s1_q4_correct']*7.5/55*60/ 5;
            $tokuten105 = $scoreSummary['s1_q5_correct']*7.5/55*60/ 5;
            $tokuten201 = $scoreSummary['s2_q1_correct']* 8.5/55*60/13;
            $tokuten202 = $scoreSummary['s2_q2_correct']* 6.0/55*60/ 5;
            $tokuten203 = $scoreSummary['s2_q3_correct']* 8.5/55*60/ 5;
            $tokuten204 = $scoreSummary['s2_q4_correct']*12  /45*60/ 4;
            $tokuten205 = $scoreSummary['s2_q5_correct']*13  /45*60/ 6;
            $tokuten206 = $scoreSummary['s2_q6_correct']*12  /45*60/ 4;
            $tokuten207 = $scoreSummary['s2_q7_correct']* 8  /45*60/ 2;
            $tokuten301 = $scoreSummary['s3_q1_correct']*11.0/40*60/6;
            $tokuten302 = $scoreSummary['s3_q2_correct']*11.0/40*60/6;
            $tokuten303 = $scoreSummary['s3_q3_correct']* 6.0/40*60/3;
            $tokuten304 = $scoreSummary['s3_q4_correct']* 5.3/40*60/4;
            $tokuten305 = $scoreSummary['s3_q5_correct']* 6.7/40*60/9;
        }
        $result->setTokuten101($tokuten101);
        $result->setTokuten102($tokuten102);
        $result->setTokuten103($tokuten103);
        $result->setTokuten104($tokuten104);
        $result->setTokuten105($tokuten105);
        $result->setTokuten106('');
        $result->setTokuten107('');
        $result->setTokuten108('');
        $result->setTokuten109('');
        $result->setTokuten110('');
        $result->setTokuten201($tokuten201);
        $result->setTokuten202($tokuten202);
        $result->setTokuten203($tokuten203);
        $result->setTokuten204($tokuten204);
        $result->setTokuten205($tokuten205);
        $result->setTokuten206($tokuten206);
        $result->setTokuten207($tokuten207);
        $result->setTokuten208('');
        $result->setTokuten209('');
        $result->setTokuten210('');
        $result->setTokuten301($tokuten301);
        $result->setTokuten302($tokuten302);
        $result->setTokuten303($tokuten303);
        $result->setTokuten304($tokuten304);
        $result->setTokuten305($tokuten305);
        $result->setTokuten306('');
        $result->setTokuten307('');
        $result->setTokuten308('');
        $result->setTokuten309('');
        $result->setTokuten310('');
        return $result;
    }

    function transcript2($rec){

        $result = new TranscriptRecord();

        $result->setJukenBangou($rec['examinee_id']);
        $result->setHeikinSougou(0);
        $result->setHeikin1(0);
        $result->setHeikin2(0);
        $result->setHeikin3('');
        $result->setHeikin101(0);
        $result->setHeikin102(0);
        $result->setHeikin103(0);
        $result->setHeikin104(0);
        $result->setHeikin105(0);
        $result->setHeikin106(0);
        $result->setHeikin107(0);
        $result->setHeikin108(0);
        $result->setHeikin109(0);
        $result->setHeikin110(0);
        $result->setHeikin201(0);
        $result->setHeikin202(0);
        $result->setHeikin203(0);
        $result->setHeikin204(0);
        $result->setHeikin205('');
        $result->setHeikin206('');
        $result->setHeikin207('');
        $result->setHeikin208('');
        $result->setHeikin209('');
        $result->setHeikin210('');
        $result->setHeikin301(0);
        $result->setHeikin302(0);
        $result->setHeikin303(0);
        $result->setHeikin304(0);
        $result->setHeikin305(0);
        $result->setHeikin306('');
        $result->setHeikin307('');
        $result->setHeikin308('');
        $result->setHeikin309('');
        $result->setHeikin310('');
        $result->setHaitenSougou(180);
        $result->setHaiten1(120);
        $result->setHaiten2(60);
        $result->setHaiten3('');
        $result->setHaiten101(3.5/45*60);
        $result->setHaiten102(3.5/45*60);
        $result->setHaiten103(4.0/45*60);
        $result->setHaiten104(5.0/45*60);
        $result->setHaiten105(5.0/45*60);
        $result->setHaiten106(6.0/45*60);
        $result->setHaiten107(7.0/45*60);
        $result->setHaiten108(5.0/45*60);
        $result->setHaiten109(6.0/45*60);
        $result->setHaiten110(13);
        $result->setHaiten201(18);
        $result->setHaiten202(10.5);
        $result->setHaiten203(12);
        $result->setHaiten204(6.5);
        $result->setHaiten205('');
        $result->setHaiten206('');
        $result->setHaiten207('');
        $result->setHaiten208('');
        $result->setHaiten209('');
        $result->setHaiten210('');
        $result->setHaiten301(10.0/50*60);
        $result->setHaiten302(11.5/50*60);
        $result->setHaiten303(10.0/50*60);
        $result->setHaiten304( 9.5/50*60);
        $result->setHaiten305( 9.0/50*60);
        $result->setHaiten306('');
        $result->setHaiten307('');
        $result->setHaiten308('');
        $result->setHaiten309('');
        $result->setHaiten310('');
        $result->setBunyaMei1('言語知識 (文字･語彙･文法)・読解');
        $result->setBunyaMei2('聴解');
        $result->setBunyaMei3('');
        $result->setBunyaMei4('');
        $result->setBunyaMei5('');
        $result->setMondai101('問題1');
        $result->setMondai102('問題2');
        $result->setMondai103('問題3');
        $result->setMondai104('問題4');
        $result->setMondai105('問題5');
        $result->setMondai106('問題6');
        $result->setMondai107('問題7');
        $result->setMondai108('問題8');
        $result->setMondai109('問題9');
        $result->setMondai110('問題10');
        $result->setMondai201('問題11');
        $result->setMondai202('問題12');
        $result->setMondai203('問題13');
        $result->setMondai204('問題14');
        $result->setMondai205('');
        $result->setMondai206('');
        $result->setMondai207('');
        $result->setMondai208('');
        $result->setMondai209('');
        $result->setMondai210('');
        $result->setMondai301('問題1');
        $result->setMondai302('問題2');
        $result->setMondai303('問題3');
        $result->setMondai304('問題4');
        $result->setMondai305('問題5');
        $result->setMondai306('');
        $result->setMondai307('');
        $result->setMondai308('');
        $result->setMondai309('');
        $result->setMondai310('');
        $result->setJukenBangou2($rec['examinee_id']);
        $result->setHyoukaSougou('A');  // TODO
        $result->setHyouka1('B');       // TODO
        $result->setHyouka2('C');       // TODO
        $result->setHyouka3('');
        if($rec['pass_fail'] == 1){
            $gouhiHantei = '***合格***';
        }else{
            $gouhiHantei = '********';
        }
        $result->setGouhiHantei($gouhiHantei);
        $sum = round($rec['sec1_score'])+round($rec['sec3_score']);
        if($sum > 180){
            $sum = 180;
        }
        $result->setTokutenSougou($sum);
        $result->setTokuten1(round($rec['sec1_score']));
        $result->setTokuten2(round($rec['sec3_score']));
        $result->setTokuten3('');
        $tokuten101 = 0;
        $tokuten102 = 0;
        $tokuten103 = 0;
        $tokuten104 = 0;
        $tokuten105 = 0;
        $tokuten106 = 0;
        $tokuten107 = 0;
        $tokuten108 = 0;
        $tokuten109 = 0;
        $tokuten110 = 0;
        $tokuten201 = 0;
        $tokuten202 = 0;
        $tokuten203 = 0;
        $tokuten204 = 0;
        $tokuten301 = 0;
        $tokuten302 = 0;
        $tokuten303 = 0;
        $tokuten304 = 0;
        $tokuten305 = 0;
        $scoreSummary = ScoreSummary::where('examinee_number', $rec['examinee_id'])->first();
        if($scoreSummary != null){
            $tokuten101 = $scoreSummary['s1_q1_correct'] * 3.5/45*60/5;
            $tokuten102 = $scoreSummary['s1_q2_correct'] * 3.5/45*60/5;
            $tokuten103 = $scoreSummary['s1_q3_correct'] * 4.0/45*60/5;
            $tokuten104 = $scoreSummary['s1_q4_correct'] * 5.0/45*60/7;
            $tokuten105 = $scoreSummary['s1_q5_correct'] * 5.0/45*60/5;
            $tokuten106 = $scoreSummary['s1_q6_correct'] * 6.0/45*60/5;
            $tokuten107 = $scoreSummary['s1_q7_correct'] * 7.0/45*60/12;
            $tokuten108 = $scoreSummary['s1_q8_correct'] * 5.0/45*60/5;
            $tokuten109 = $scoreSummary['s1_q9_correct'] * 6.0/45*60/5;
            $tokuten110 = $scoreSummary['s1_q10_correct']*13.0/60*60/5;
            $tokuten201 = $scoreSummary['s1_q11_correct']*18.0/60*60/9;
            $tokuten202 = $scoreSummary['s1_q12_correct']*10.5/60*60/2;
            $tokuten203 = $scoreSummary['s1_q13_correct']*12.0/60*60/3;
            $tokuten204 = $scoreSummary['s1_q14_correct']* 6.5/60*60/2;
            $tokuten301 = $scoreSummary['s3_q1_correct']*11.0/50*60/6;
            $tokuten302 = $scoreSummary['s3_q2_correct']*11.5/50*60/6;
            $tokuten303 = $scoreSummary['s3_q3_correct']*10.0/50*60/5;
            $tokuten304 = $scoreSummary['s3_q4_correct']* 9.5/50*60/12;
            $tokuten305 = $scoreSummary['s3_q5_correct']* 9.0/50*60/4;
        }
        $result->setTokuten101($tokuten101);
        $result->setTokuten102($tokuten102);
        $result->setTokuten103($tokuten103);
        $result->setTokuten104($tokuten104);
        $result->setTokuten105($tokuten105);
        $result->setTokuten106($tokuten106);
        $result->setTokuten107($tokuten107);
        $result->setTokuten108($tokuten108);
        $result->setTokuten109($tokuten109);
        $result->setTokuten110($tokuten110);
        $result->setTokuten201($tokuten201);
        $result->setTokuten202($tokuten202);
        $result->setTokuten203($tokuten203);
        $result->setTokuten204($tokuten204);
        $result->setTokuten205('');
        $result->setTokuten206('');
        $result->setTokuten207('');
        $result->setTokuten208('');
        $result->setTokuten209('');
        $result->setTokuten210('');
        $result->setTokuten301($tokuten301);
        $result->setTokuten302($tokuten302);
        $result->setTokuten303($tokuten303);
        $result->setTokuten304($tokuten304);
        $result->setTokuten305($tokuten305);
        $result->setTokuten306('');
        $result->setTokuten307('');
        $result->setTokuten308('');
        $result->setTokuten309('');
        $result->setTokuten310('');
        return $result;
    }

    function transcript1($rec){

        $result = new TranscriptRecord();

        $result->setJukenBangou($rec['examinee_id']);
        $result->setHeikinSougou(0);
        $result->setHeikin1(0);
        $result->setHeikin2(0);
        $result->setHeikin3('');
        $result->setHeikin101(0);
        $result->setHeikin102(0);
        $result->setHeikin103(0);
        $result->setHeikin104(0);
        $result->setHeikin105(0);
        $result->setHeikin106(0);
        $result->setHeikin107(0);
        $result->setHeikin108(0);
        $result->setHeikin109(0);
        $result->setHeikin110(0);
        $result->setHeikin201(0);
        $result->setHeikin202(0);
        $result->setHeikin203(0);
        $result->setHeikin204('');
        $result->setHeikin205('');
        $result->setHeikin206('');
        $result->setHeikin207('');
        $result->setHeikin208('');
        $result->setHeikin209('');
        $result->setHeikin210('');
        $result->setHeikin301(0);
        $result->setHeikin302(0);
        $result->setHeikin303(0);
        $result->setHeikin304(0);
        $result->setHeikin305(0);
        $result->setHeikin306('');
        $result->setHeikin307('');
        $result->setHeikin308('');
        $result->setHeikin309('');
        $result->setHeikin310('');
        $result->setHaitenSougou(180);
        $result->setHaiten1(120);
        $result->setHaiten2(60);
        $result->setHaiten3('');
        $result->setHaiten101(4.0/40*60);
        $result->setHaiten102(5.0/40*60);
        $result->setHaiten103(6.0/40*60);
        $result->setHaiten104(6.5/40*60);
        $result->setHaiten105(5.5/40*60);
        $result->setHaiten106(6.0/40*60);
        $result->setHaiten107(7.0/40*60);
        $result->setHaiten108(10.0/70*60);
        $result->setHaiten109(18.0/70*60);
        $result->setHaiten110(12.0/70*60);
        $result->setHaiten201(11.0/70*60);
        $result->setHaiten202(12.0/70*60);
        $result->setHaiten203( 7.0/70*60);
        $result->setHaiten204('');
        $result->setHaiten205('');
        $result->setHaiten206('');
        $result->setHaiten207('');
        $result->setHaiten208('');
        $result->setHaiten209('');
        $result->setHaiten210('');
        $result->setHaiten301(11.0/55*60);
        $result->setHaiten302(12.0/55*60);
        $result->setHaiten303(12.0/55*60);
        $result->setHaiten304( 8.5/55*60);
        $result->setHaiten305(11.5/55*60);
        $result->setHaiten306('');
        $result->setHaiten307('');
        $result->setHaiten308('');
        $result->setHaiten309('');
        $result->setHaiten310('');
        $result->setBunyaMei1('言語知識 (文字･語彙･文法)・読解');
        $result->setBunyaMei2('聴解');
        $result->setBunyaMei3('');
        $result->setBunyaMei4('');
        $result->setBunyaMei5('');
        $result->setMondai101('問題1');
        $result->setMondai102('問題2');
        $result->setMondai103('問題3');
        $result->setMondai104('問題4');
        $result->setMondai105('問題5');
        $result->setMondai106('問題6');
        $result->setMondai107('問題7');
        $result->setMondai108('問題8');
        $result->setMondai109('問題9');
        $result->setMondai110('問題10');
        $result->setMondai201('問題11');
        $result->setMondai202('問題12');
        $result->setMondai203('問題13');
        $result->setMondai204('');
        $result->setMondai205('');
        $result->setMondai206('');
        $result->setMondai207('');
        $result->setMondai208('');
        $result->setMondai209('');
        $result->setMondai210('');
        $result->setMondai301('問題1');
        $result->setMondai302('問題2');
        $result->setMondai303('問題3');
        $result->setMondai304('問題4');
        $result->setMondai305('問題5');
        $result->setMondai306('');
        $result->setMondai307('');
        $result->setMondai308('');
        $result->setMondai309('');
        $result->setMondai310('');
        $result->setJukenBangou2($rec['examinee_id']);
        $result->setHyoukaSougou('A');  // TODO
        $result->setHyouka1('B');       // TODO
        $result->setHyouka2('C');       // TODO
        $result->setHyouka3('');
        if($rec['pass_fail'] == 1){
            $gouhiHantei = '***合格***';
        }else{
            $gouhiHantei = '********';
        }
        $result->setGouhiHantei($gouhiHantei);
        $sum = round($rec['sec1_score'])+round($rec['sec3_score']);
        if($sum > 180){
            $sum = 180;
        }
        $result->setTokutenSougou($sum);
        $result->setTokuten1(round($rec['sec1_score']));
        $result->setTokuten2(round($rec['sec3_score']));
        $result->setTokuten3('');
        $tokuten101 = 0;
        $tokuten102 = 0;
        $tokuten103 = 0;
        $tokuten104 = 0;
        $tokuten105 = 0;
        $tokuten106 = 0;
        $tokuten107 = 0;
        $tokuten108 = 0;
        $tokuten109 = 0;
        $tokuten110 = 0;
        $tokuten201 = 0;
        $tokuten202 = 0;
        $tokuten203 = 0;
        $tokuten301 = 0;
        $tokuten302 = 0;
        $tokuten303 = 0;
        $tokuten304 = 0;
        $tokuten305 = 0;
        $scoreSummary = ScoreSummary::where('examinee_number', $rec['examinee_id'])->first();
        if($scoreSummary != null){
            $tokuten101 = $scoreSummary['s1_q1_correct'] * 4.0/40*60/6;
            $tokuten102 = $scoreSummary['s1_q2_correct'] * 5.0/40*60/7;
            $tokuten103 = $scoreSummary['s1_q3_correct'] * 6.0/40*60/6;
            $tokuten104 = $scoreSummary['s1_q4_correct'] * 6.5/40*60/6;
            $tokuten105 = $scoreSummary['s1_q5_correct'] * 5.5/40*60/10;
            $tokuten106 = $scoreSummary['s1_q6_correct'] * 6.0/40*60/5;
            $tokuten107 = $scoreSummary['s1_q7_correct'] * 7.0/40*60/5;
            $tokuten108 = $scoreSummary['s1_q8_correct'] *10.0/70*60/4;
            $tokuten109 = $scoreSummary['s1_q9_correct'] *18.0/70*60/9;
            $tokuten110 = $scoreSummary['s1_q10_correct']*12.0/70*60/4;
            $tokuten201 = $scoreSummary['s1_q11_correct']*11.0/70*60/3;
            $tokuten202 = $scoreSummary['s1_q12_correct']*12.0/70*60/4;
            $tokuten203 = $scoreSummary['s1_q13_correct']* 7.0/70*60/2;
            $tokuten301 = $scoreSummary['s3_q1_correct']*11.0/55*60/6;
            $tokuten302 = $scoreSummary['s3_q2_correct']*12.0/55*60/7;
            $tokuten303 = $scoreSummary['s3_q3_correct']*12.0/55*60/6;
            $tokuten304 = $scoreSummary['s3_q4_correct']* 8.5/55*60/14;
            $tokuten305 = $scoreSummary['s3_q5_correct']*11.5/55*60/4;
        }
        $result->setTokuten101($tokuten101);
        $result->setTokuten102($tokuten102);
        $result->setTokuten103($tokuten103);
        $result->setTokuten104($tokuten104);
        $result->setTokuten105($tokuten105);
        $result->setTokuten106($tokuten106);
        $result->setTokuten107($tokuten107);
        $result->setTokuten108($tokuten108);
        $result->setTokuten109($tokuten109);
        $result->setTokuten110($tokuten110);
        $result->setTokuten201($tokuten201);
        $result->setTokuten202($tokuten202);
        $result->setTokuten203($tokuten203);
        $result->setTokuten204('');
        $result->setTokuten205('');
        $result->setTokuten206('');
        $result->setTokuten207('');
        $result->setTokuten208('');
        $result->setTokuten209('');
        $result->setTokuten210('');
        $result->setTokuten301($tokuten301);
        $result->setTokuten302($tokuten302);
        $result->setTokuten303($tokuten303);
        $result->setTokuten304($tokuten304);
        $result->setTokuten305($tokuten305);
        $result->setTokuten306('');
        $result->setTokuten307('');
        $result->setTokuten308('');
        $result->setTokuten309('');
        $result->setTokuten310('');
        return $result;
    }
}

?>