<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScoreSummary extends Model
{
    protected $table = 'score_summary';
    public $incrementing = false;
    // protected $fillable = ['examinee_number','level'];
    protected $primaryKey = ['examinee_number','level'];
    public $timestamps = false;

}
