<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamineeInformation extends Model
{
    //
    protected $table = 'examinee_information';
    public $incrementing = false;
    protected $primaryKey = 'examinee_id';
}
