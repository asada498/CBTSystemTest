<?php

namespace App\QuestionDatabase\Q5\Listening;

use Illuminate\Database\Eloquent\Model;

class Q5Section3Question1 extends Model
{
    //
    protected $table = 'q_53_01';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
