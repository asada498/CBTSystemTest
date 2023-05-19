<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    protected $table = 'question_type';
    public $incrementing = false;
    // protected $fillable = ['examinee_number','level'];
    protected $primaryKey = ['code'];
}
