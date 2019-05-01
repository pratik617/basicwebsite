<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'photos';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['area', 'floor', 'building', 'unit', 'probe_location', 'probe_number', 'status'];

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function project() {
      return $this->belongsTo('App\Models\Project');
    }

    public function setCapturedAtAttribute($value) {
      if ($value != null) {
        $this->attributes['captured_at'] = Carbon::createFromFormat('Y:m:d H:i:s',$value);
      }
    }

    public function getCreatedAtAttribute($value) {
  		return Carbon::parse($value)->format('d/m/Y');
  	}

    public function getCapturedAtAttribute($value) {
  		return Carbon::parse($value)->format('d/m/Y');
  	}

    public function getPhotoUrl($photo_name, $photo_type) {
      $filename = pathinfo($photo_name, PATHINFO_FILENAME);
      $extension = pathinfo($photo_name, PATHINFO_EXTENSION);
      $thumb_name = $filename.'-'.$photo_type.'.'.$extension;
      //$thumb_directory = '/'.env('STORAGE_DIR').'/'.env('PROJECT_PHOTOS_DIR').'/'.$this->project->id.'/'.env('THUMB_DIR');
      $thumb_directory = 'uploads/'.env('PROJECT_PHOTOS_DIR').'/'.$this->project->id.'/'.env('THUMB_DIR');
      $thumb_url = $thumb_directory.'/'.$thumb_name;
      return $thumb_url;
    }
}
