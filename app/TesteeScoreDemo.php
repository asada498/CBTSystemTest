<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TesteeScoreDemo extends Model
{
    //
    protected $table = 'testtee_score_save_demo';
    public $incrementing = false;
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id','name','section1_part1','section1_part2','section1_part3','section1_part4'];

}
