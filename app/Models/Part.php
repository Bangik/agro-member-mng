<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;

class Part extends Model
{
  use HasUuids, SoftDeletes, HasFactory;

  protected $table = 'm_part';
  protected $fillable = [
    'name',
  ];

  public function contracts()
  {
    return $this->hasMany(TContract::class, 'm_part_id', 'id');
  }
}
