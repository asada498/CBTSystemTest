<?php

namespace App\QuestionDatabase\Q1\Vocabulary;

use Illuminate\Database\Eloquent\Model;

class Q1Section1Question2 extends Model
{
    protected $table = 'q_11_02';   //we should to change the table for Q3S1Q1
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
