<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tripcancel extends Model
{
    public $table = "trip_cancel_log";
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
