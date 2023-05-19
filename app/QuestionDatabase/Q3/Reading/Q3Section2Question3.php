<?php

namespace App\QuestionDatabase\Q3\Reading;

use Illuminate\Database\Eloquent\Model;

class Q3Section2Question3 extends Model
{
    protected $table = 'q_32_03';
    protected $primaryKey = 'id';
    protected $fillable = ['correct_answer_rate','past_testee_number','correct_testee_number'];
    public $timestamps = false;
}
