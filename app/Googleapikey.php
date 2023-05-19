<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Googleapikey extends Model
{
    //
    protected $table = 'googleapikey';
    public $incrementing = false;
    protected $fillable = ['access_token','date_issue'];
    protected $primaryKey = 'refresh_token';
}
