<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Players extends Model
{
    protected $connection = "DB_CONNECTION_CARCOOL";
    protected $table = "players";
    public $timestamps = false;
}
