<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Helper\Exceptions;

class Notificationlog extends Model
{
    public $table = "notification_log";
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

    public static function create($type,$id,$title,$is_delivered="yes")
    {
        try {
            if($type!="" && $id!="" && $title!=""){
                $notificationlog = New Notificationlog;
                $notificationlog->user_type = $type;
                $notificationlog->user_id = $id;
                $notificationlog->title = $title;
                $notificationlog->is_delivered = $is_delivered;
                if($notificationlog->save()){
                    return true;
                } else{
                    return false;
                }
            } else{
                return false;
            }
        } catch (Exception $e) {
            Exceptions::exception($e);
        }
    }
}
