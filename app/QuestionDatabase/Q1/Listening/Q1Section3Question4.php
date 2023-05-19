<?php

namespace App\QuestionDatabase\Q1\Listening;

use Illuminate\Database\Eloquent\Model;

class Q1Section3Question4 extends Model
{
    //
    protected $table = 'q_13_04';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
