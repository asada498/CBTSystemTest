<?php

namespace App\Http\Controllers\Q5;

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

class Q5ResultController extends Controller
{
    public function radarChartResult (){
        if (!(Session::has('idTester')))
                {
                    $userIDTemp = "Q21122989050101";
                    Session::put('idTester',$userIDTemp);        
                }
        $userID = Session::get('idTester');
        // $realUserId = substr($userID, 1);
        // $query = ScoreSummary::where('examinee_number',$realUserId)->first();
        // $section1And2Total = $query->s1_q1_correct /7*11 + $query->s1_q2_correct /5*9 + $query->s1_q3_correct /6*10 + $query->s1_q4_correct /3*10 + $query->s2_q1_correct /9*16 + $query->s2_q2_correct /4*11 + $query->s2_q3_correct /4*14 + $query->s2_q4_correct /2*12 + $query->s2_q5_correct /2*16 + $query->s2_q6_correct /1*11;
        // $section3Total = $query->s3_q1_correct /7*18 + $query->s3_q2_correct /6*18 + $query->s3_q3_correct /5*12 + $query->s3_q4_correct /6*12;
        // $totalScore = $section1And2Total + $section3Total;
        // // $s1Rate = ($query->s1_q1_correct + $query->s1_q2_correct + $query->s1_q3_correct + $query->s1_q4_correct)/(7+5+6+3);
        // // $s2Rate = ($query->s2_q1_correct + $query->s2_q2_correct + $query->s2_q3_correct + $query->s2_q4_correct + $query->s2_q5_correct + $query->s2_q6_correct)/(9+4+4+2+2+1);
        // $s12Rate = ($query->s1_q1_correct + $query->s1_q2_correct + $query->s1_q3_correct + $query->s1_q4_correct + $query->s2_q1_correct + $query->s2_q2_correct + $query->s2_q3_correct + $query->s2_q4_correct + $query->s2_q5_correct + $query->s2_q6_correct) / (7+5+6+3+9+4+4+2+2+1);
        // $s3Rate = ($query->s3_q1_correct + $query->s3_q2_correct + $query->s3_q3_correct + $query->s3_q4_correct) / (7+6+5+6);
        // $passFlag = 0 ;
        // // if ($s1Rate >= 0.25 && $s2Rate >= 0.25 && $s3Rate >= 0.25)
        // if ($s12Rate >= 0.25 && $s3Rate >= 0.25)
        // {
        //     if ($totalScore >= 110)
        //         $passFlag = 1;
        //     else if ($totalScore > 79 && $totalScore <110)
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
        // // dd($informationTotal);
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
        // return view('Q5\testResult',compact('query','testeeInformation','informationTotal'));
        return redirect('main');

    }

}