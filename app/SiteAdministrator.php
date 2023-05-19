<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteAdministrator extends Model
{
    //
    protected $table = 'site_administrator';
    public $incrementing = false;
    protected $primaryKey = 'test_site';
}