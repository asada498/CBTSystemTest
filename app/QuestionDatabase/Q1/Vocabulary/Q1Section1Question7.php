<?php

namespace App\QuestionDatabase\Q1\Vocabulary;

use Illuminate\Database\Eloquent\Model;

class Q1Section1Question7 extends Model
{
    protected $table = 'q_11_07';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
