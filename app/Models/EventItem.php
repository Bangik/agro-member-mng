<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventItem extends Model
{
  use SoftDeletes, HasUuids, HasFactory;

  protected $table = 'm_event_item';

  public $incrementing = false;

  protected $fillable = [
    'id',
    'm_event_id',
    'title',
    'slug',
    'quota',
    'price',
  ];

  public function event()
  {
    return $this->belongsTo(Event::class, 'm_event_id');
  }

  public function tEvent()
  {
    return $this->hasMany(TEvent::class, 'm_event_item_id', 'id');
  }
}
