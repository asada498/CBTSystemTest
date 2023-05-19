<?php

namespace App\QuestionDatabase\Q5\Reading;

use Illuminate\Database\Eloquent\Model;

class Q5Section2Question4 extends Model
{
    //
    protected $table = 'q_52_04';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
