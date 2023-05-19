<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    //
    protected $table = 'test_result';
    public $incrementing = false;
    protected $primaryKey = 'examinee_id';

}
