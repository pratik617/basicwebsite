<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Changecontact extends Model
{
    public $table = "change_contact_log";
    public $primaryKey = false;
    public $timestamps = false;
    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
