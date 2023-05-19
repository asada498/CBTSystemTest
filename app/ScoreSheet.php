<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScoreSheet extends Model
{
    protected $table = 'score_sheet';
    public $incrementing = false;
    // protected $fillable = ['examinee_number','level'];
    protected $primaryKey = ['level','section','question','number_of_correct'];
}
