<?php

namespace App\QuestionDatabase\Q3\Vocabulary;

use Illuminate\Database\Eloquent\Model;

class Q3Section1Question3 extends Model
{
    protected $table = 'q_31_03';    // I must to change the table name
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}