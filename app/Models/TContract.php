<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;

class TContract extends Model
{
  use HasUuids, SoftDeletes, HasFactory;

  protected $table = 't_contract';

  protected $fillable = [
    'm_member_id',
    'm_part_id',
    'contract_number',
    'start_date',
    'end_date',
  ];

  public function member()
  {
    return $this->belongsTo(Member::class, 'm_member_id');
  }

  public function part()
  {
    return $this->belongsTo(Part::class, 'm_part_id');
  }
}
