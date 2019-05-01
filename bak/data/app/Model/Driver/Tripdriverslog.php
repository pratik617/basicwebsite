<?php

namespace App\Model\Driver;

use Illuminate\Database\Eloquent\Model;

class Tripdriverslog extends Model
{
    public $table = "trip_drivers_log";
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
