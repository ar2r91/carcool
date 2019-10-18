<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    protected $connection = "DB_CONNECTION_CARCOOL";
    protected $table = "teams";
    public $timestamps = false;
}
