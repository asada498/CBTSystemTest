<?php

namespace App\Http\Controllers\Q3;

use App\Http\Controllers\Controller;

use App\TestInformation;
use App\ExamineeInformation;
use App\TestSiteInformation;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Session;
use Illuminate\Support\Facades\Config;
use App\Grades;

class Q3ResultController extends Controller
{
    public function radarChartResult (){
        // if (!(Session::has('idTester')))
        //         {
        //             $userIDTemp = "Q21092689040201";
        //             Session::put('idTester',$userIDTemp);        
        //         }
        // $userID = Session::get('idTester');
        // $realUserId = substr($userID, 1);
        // $query = ScoreSummary::where('examinee_number',$realUserId)->first();
        // $section1And2Total = $query->s1_q1_correct /7*7.88 + $query->s1_q2_correct /5*5.63 + $query->s1_q3_correct /8*9 + $query->s1_q4_correct /4*7.5 + $query->s1_q5_correct /4*7.5 + $query->s2_q1_correct /13*20.25 + $query->s2_q2_correct /4*8.25 + $query->s2_q3_correct /4*12 + $query->s2_q4_correct /3*15.75 + $query->s2_q5_correct /3*17.25 + $query->s2_q6_correct /2*9;
        // $section3Total = $query->s3_q1_correct /8*18 + $query->s3_q2_correct /7*18 + $query->s3_q3_correct /5*11.14 + $query->s3_q4_correct /8*12.86;
        // $totalScore = $section1And2Total + $section3Total;
        // // $s1Rate = ($query->s1_q1_correct + $query->s1_q2_correct + $query->s1_q3_correct + $query->s1_q4_correct + $query->s1_q5_correct)/(7+5+8+4+4);
        // // $s2Rate = ($query->s2_q1_correct + $query->s2_q2_correct + $query->s2_q3_correct + $query->s2_q4_correct + $query->s2_q5_correct + $query->s2_q6_correct)/(13+4+4+3+3+2);
        // $s12Rate = ($query->s1_q1_correct + $query->s1_q2_correct + $query->s1_q3_correct + $query->s1_q4_correct + $query->s1_q5_correct + $query->s2_q1_correct + $query->s2_q2_correct + $query->s2_q3_correct + $query->s2_q4_correct + $query->s2_q5_correct + $query->s2_q6_correct) / (7+5+8+4+4+13+4+4+3+3+2);
        // $s3Rate = ($query->s3_q1_correct + $query->s3_q2_correct + $query->s3_q3_correct + $query->s3_q4_correct) / (8+7+5+8);
        // $passFlag = 0 ;
        // // if ($s1Rate >= 0.25 && $s2Rate >= 0.25 && $s3Rate >= 0.25)
        // if ($s12Rate >= 0.25 && $s3Rate >= 0.25)
        // {
        //     if ($totalScore >= 110)
        //         $passFlag = 1;
        //     else if ($totalScore > 84 && $totalScore <110)
        //     {
        //         $passRateAnchor = Grades::where('examinee_number',$realUserId)->first()->anchor_pass_rate;
        //         if ($passRateAnchor >= 60)
        //             $passFlag = 1;
        //     }
        // }
        // $informationTotal = [
        //     's3Total' => $section3Total,
        //     'totalScore' => $totalScore,
        //     's3Rate' => $s3Rate
        // ];
        // $testInformationQuery = TestInformation::where('examinee_id',$realUserId)->first();
        // $userInformationQuery = ExamineeInformation::where('examinee_id',$realUserId)->first();
        // $testDay = $testInformationQuery->test_day;
        // $testDayString = explode("-",$testDay);
        // $testDayModified = $testDayString[0]."年　".$testDayString[1]."月　".$testDayString[2]."日";
        // $testSiteCode = $testInformationQuery->test_site;
        // $testSiteCity = TestSiteInformation::where('test_site',$testSiteCode)->first()->city;
        // ScoreSummary::where('examinee_number',$realUserId)->update([
        //     's3_score' => $section3Total,
        //     'score' => $totalScore,
        //     's3_rate'=>$s3Rate
        // ]);
        // Grades::where('examinee_number',$realUserId)->update([
        //     'pass_fail' => $passFlag
        // ]);
        // $testeeInformation = [
        //     "id" => $realUserId,
        //     "name"=> $userInformationQuery->name,
        //     "date" => $testDayModified,
        //     "place"=> $testSiteCity
        // ];
        // return view('Q4\testResult',compact('query','testeeInformation','informationTotal'));
        return redirect('main');
    }   

}