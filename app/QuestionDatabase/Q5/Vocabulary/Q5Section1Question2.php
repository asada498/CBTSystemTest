<?php

namespace App\QuestionDatabase\Q5\Vocabulary;

use Illuminate\Database\Eloquent\Model;

class Q5Section1Question2 extends Model
{
    //
    protected $table = 'q_51_02';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
