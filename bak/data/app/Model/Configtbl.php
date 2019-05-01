<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Configtbl extends Model
{
    public $table = "config_tbl";
    public $primaryKey = "id";
    public $timestamps = false;
}
