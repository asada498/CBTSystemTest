<?php

namespace App\QuestionDatabase\Q2\Listening;

use Illuminate\Database\Eloquent\Model;

class Q2Section3Question3 extends Model
{
    //
    protected $table = 'q_23_03';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
