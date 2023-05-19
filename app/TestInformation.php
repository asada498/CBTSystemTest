<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestInformation extends Model
{
    //
    protected $table = 'test_information';
    public $incrementing = false;
    protected $primaryKey = 'examinee_id';

}
