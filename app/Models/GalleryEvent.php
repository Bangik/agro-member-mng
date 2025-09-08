<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;

class GalleryEvent extends Model
{
  use HasFactory, SoftDeletes, HasUuids;
  protected $table = 'm_gallery_event';

  protected $fillable = [
    'id',
    'm_event_id',
    'path',
    'file',
    'is_primary',
    'is_banner',
  ];

  public $incrementing = false;

  public function event()
  {
    return $this->belongsTo(Event::class, 'm_event_id', 'id');
  }

  public function imageUrl()
  {
    return $this->path ? FileHelper::get($this->path, $this->file) : null;
  }
}
