<?php

namespace App\QuestionDatabase\Q4\Vocabulary;

use Illuminate\Database\Eloquent\Model;

class Q4Section1Question1 extends Model
{
    protected $table = 'q_41_01';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
