<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerRecord extends Model
{
    protected $table = 'answer';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['examinee_number','level','section','question','number'];
}
