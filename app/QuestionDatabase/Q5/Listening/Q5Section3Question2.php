<?php

namespace App\QuestionDatabase\Q5\Listening;

use Illuminate\Database\Eloquent\Model;

class Q5Section3Question2 extends Model
{
    //
    protected $table = 'q_53_02';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
