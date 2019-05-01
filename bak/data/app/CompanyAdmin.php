<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyAdmin extends Authenticatable
{
    use Notifiable,SoftDeletes;
    public $primaryKey = "id";
    protected $softDelete = true; 
    public $table = "company_admins";
    protected $guard = 'company_admin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname','country_code','mobile_no', 'email', 'password','profile','status','created_by','updated_by','deleted_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
