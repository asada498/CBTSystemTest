<?php

namespace App\QuestionDatabase\Q3\Vocabulary;

use Illuminate\Database\Eloquent\Model;

class Q3Section1Question5 extends Model
{
    protected $table = 'q_31_05';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
