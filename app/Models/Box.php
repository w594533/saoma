<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $appends = ['img_path'];
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function getImgPathAttribute()
    {
      if ($this->attributes['image']) {
        return array_map(function($value) {
          return config('app.url').'/storage/'.$value;
        }, json_decode($this->attributes['image'], true));
      } else {
        return array();
      }
    }
}
