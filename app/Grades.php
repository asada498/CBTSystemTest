<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    //
    protected $table = 'grades';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'examinee_number';
}