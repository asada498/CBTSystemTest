<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
// use DB;
use App\TestInformation;
use App\ExamineeInformation;
use App\ScoreSummary;
use Illuminate\Support\Facades\Storage;
use Session;
use Illuminate\Support\Facades\Config;

class testController extends Controller
{
    public function showQuestion(Request $request){
        return view('Q5\testAutoPlay');
}
}
    
    

