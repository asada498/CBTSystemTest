<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamineeList extends Model
{
    //
    protected $table = 'examinee_list';
    public $incrementing = false;
    protected $primaryKey = 'examinee_id';
}