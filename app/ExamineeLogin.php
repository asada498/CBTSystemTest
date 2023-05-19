<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamineeLogin extends Model
{
    //
    protected $table = 'examinee_login';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'examinee_id';
}