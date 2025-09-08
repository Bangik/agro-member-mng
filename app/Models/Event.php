<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
  use SoftDeletes, HasUuids, HasFactory;
  protected $table = 'm_event';
  protected $fillable = [
    'id',
    'title',
    'slug',
    'description',
    'type',
    'venue',
    'venue_maps_url',
    'schedule',
    'start_time',
    'end_time',
    'total_quota',
    'cp_email',
    'cp_phone'
  ];
  // protected $casts = [
  //   'id' => 'string',
  //   'schedule' => 'date',
  //   'start_time' => 'time',
  //   'end_time' => 'time',
  //   'total_quota' => 'integer'
  // ];
  protected $dates = [
    'deleted_at'
  ];
  public $incrementing = false;

  public function galleries()
  {
    return $this->hasMany(GalleryEvent::class, 'm_event_id', 'id');
  }

  public function thumbnail()
  {
    return $this->hasOne(GalleryEvent::class, 'm_event_id', 'id')->where('is_primary', 1);
  }

  public function banner()
  {
    return $this->hasOne(GalleryEvent::class, 'm_event_id', 'id')->where('is_banner', 1);
  }

  public function eventItems()
  {
    return $this->hasMany(EventItem::class, 'm_event_id', 'id');
  }

  public function thumbnailUrl()
  {
    return $this->thumbnail ? FileHelper::get($this->thumbnail->path, $this->thumbnail->file) : null;
  }

  public function bannerUrl()
  {
    return $this->banner ? FileHelper::get($this->banner->path, $this->banner->file) : null;
  }
}
