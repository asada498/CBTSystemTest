<?php

namespace App\QuestionDatabase\Q4\Listening;

use Illuminate\Database\Eloquent\Model;

class Q4Section3Question1 extends Model
{
    //
    protected $table = 'q_43_01';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
