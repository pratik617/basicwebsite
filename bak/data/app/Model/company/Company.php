<?php

namespace App\Model\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
	use SoftDeletes;
    public $table = "companys";
    public $primaryKey = "id";
    protected $softDelete = true; 
}