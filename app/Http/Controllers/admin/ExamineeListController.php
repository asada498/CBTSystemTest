<?php

namespace App\Http\Controllers\admin;

use Auth;
use Session;
use Validator;
use DB;
use App\ExamineeList;
use App\ExamineeRecord;
use App\SearchCondition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\User;

class ExamineeListController extends Controller
{
    function search(Request $request)
    {
        $password = Session::get('password');
        if($password=="aikkamata2255" or $password=="tokyo")
        {
            $city = $request->get('city');
        }
        else
        {
            $cityTable = User::select('city')->where('email',$password)->first();
            $city=$cityTable->city;
        }

        $examineeId = $request->get('examineeId');
        $country = $request->get('country');
       
        $testDay = $request->get('testDay');
        $name = $request->get('name');
        $paymentNotYet = $request->get('paymentNotYet');
        $paymentDone = $request->get('paymentDone');
        $photoNotYet = $request->get('photoNotYet');
        $photoDone = $request->get('photoDone');
        $op = $request->get('op');
        $level = $request->get('level');

        $condition = new SearchCondition($examineeId, $name, $country, $city, $testDay, $level, $paymentDone, $paymentNotYet, $photoDone, $photoNotYet, $op, '0');
        Session::put('ExamineeListCondition', $condition);

        if($paymentNotYet != null && $paymentDone == null){
            $query = ExamineeList::where('payment_done', '0');
        }else if($paymentNotYet == null && $paymentDone != null){
            $query = ExamineeList::where('payment_done', '1');
        }else{
            $query = ExamineeList::where(function($query) {
                $query->orwhere('payment_done', '=', '0')
                    ->orWhere('payment_done', '=', '1');
            });
        }

        if($photoNotYet != null && $photoDone == null){
            $query = ExamineeList::whereNull('picture_source');
        }else if($photoNotYet == null && $photoDone != null){
            $query = ExamineeList::whereNotNull('picture_source');
        }
        
        if($examineeId != null){
            $query->where('examinee_id', 'like', "{$examineeId}%");
        }

        if($level){
            $query->where('level', $level.'Q');
        }
        
        if($testDay != null){
            $query->where('test_day', $testDay);
        }
        
        if($name != null){
            $query->where('name', 'like', "%{$name}%");
        }

        if($country != null){
            $query->where('country', $country);
        }
        if($city != null){
            $query->where('city', $city);
        }

        $query->orderBy('examinee_id');
        $list = $query->get();

        if($op == 'search'){
            $ret = [];
            foreach($list as $ele){
                if($ele['picture_source'] == null){
                    $photoDone = '0';
                }else{
                    $photoDone = '1';
                }
                $rec = new ExamineeRecord($ele['examinee_id'], $ele['name'], $ele['payment_done'], $photoDone, $ele['country'], $ele['city'], $ele['test_day'], $ele['level']);
                array_push($ret, $rec);
            }
            return view('admin/examineeList', ['data' => $ret, 'condition' => $condition]);
        }

        //csv
        $headers = [
            'Content-type' => 'text/csv;',
            'Content-Disposition' => 'attachment; filename=examinee_list.csv'
        ];

        $callback = function() use($list) {
            ob_clean();
            $handle = fopen('php://output', 'w');
            // $columns = [
            //     '受験番号',
            //     '姓名',
            //     '生年月日'
            // ];
            // mb_convert_variables('SJIS-win', 'UTF-8', $columns);
            // fputcsv($handle, $columns);
            foreach($list as $rec) {
                // $level = substr($rec['examinee_id'], 9, 1);
                // if($level == 1 or $level == 2){
                //     $rec['sec2_score'] = $rec['sec3_score'];
                //     $rec['sec3_score'] = 0;
                // }
                $csv = [
                    $rec['examinee_id'],    // ID
                    $rec['examinee_id'],    // RegNumber
                    $rec['name'],           // Name
                    '',                     // Gender
                    $rec['birthDay'],       // BirthDate
                    '',                     // Citizenship
                    '',                     // CitizenshipEN
                    0,                      // 発行不可
                    0,                      // 成績表発行済
                    0,                      // 合格証発行済
                    ''                      // 備考
                ];
                mb_convert_variables('SJIS-win', 'UTF-8', $csv);
                fputcsv($handle, $csv);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);

    }
}

?>