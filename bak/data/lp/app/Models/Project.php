<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    const project_thumbs = array(
      'large' => array('width' => 200, 'height' => 249),
      'medium' => array('width' => 100, 'height' => 125),
      //'small' => array('width' => 50, 'height' => 50),
    );

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'projects';

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
    protected $fillable = ['code', 'name', 'title', 'address', 'address2', 'city', 'state', 'country', 'postal_code', 'status'];

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function setStartAtAttribute($value) {
  		$this->attributes['start_at'] = Carbon::createFromFormat('d/m/Y',$value);
      }

  	public function setEndAtAttribute($value) {
          $this->attributes['end_at'] = Carbon::createFromFormat('d/m/Y',$value);
      }

  	public function getStartAtAttribute($value) {
  		return Carbon::parse($value)->format('d/m/Y');
  	}

  	public function getEndAtAttribute($value) {
  		return Carbon::parse($value)->format('d/m/Y');
  	}

    public function getCodeAttribute($value) {
  		return ucfirst($value);
  	}

    public function getNameAttribute($value) {
  		return ucfirst($value);
  	}

  	public function getTitleAttribute($value) {
  		return ucfirst($value);
  	}

    public function photos() {
      return $this->hasMany('App\Models\Photo')->orderBy('display_order');
    }
}
