<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    //
    protected $table = 'administrator';
    public $incrementing = false;
    protected $primaryKey = 'id';
}
