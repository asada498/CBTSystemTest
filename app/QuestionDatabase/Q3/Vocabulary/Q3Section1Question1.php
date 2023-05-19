<?php

namespace App\QuestionDatabase\Q3\Vocabulary;

use Illuminate\Database\Eloquent\Model;

class Q3Section1Question1 extends Model
{
    protected $table = 'q_31_01';   //we should to change the table for Q3S1Q1
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
