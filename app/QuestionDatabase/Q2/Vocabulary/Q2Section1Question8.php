<?php

namespace App\QuestionDatabase\Q2\Vocabulary;

use Illuminate\Database\Eloquent\Model;

class Q2Section1Question8 extends Model
{
    protected $table = 'q_21_08';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
