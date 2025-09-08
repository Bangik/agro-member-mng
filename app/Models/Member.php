<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;

class Member extends Model
{
  use HasUuids, SoftDeletes, HasFactory;

  protected $table = 'm_member';
  protected $fillable = [
    'm_user_id',
    'reg_number',
    'national_id_number',
    'name',
    'birth_place',
    'birth_date',
    'gender',
    'address',
    'rt',
    'rw',
    'village',
    'district',
    'city',
    'state',
    'post_code',
    'phone',
    'email',
    'religion',
    'blood_type',
    'is_married',
    'hobbies',
    'pp_path',
    'pp_file',
  ];

  public function photoUrl()
  {
    return $this->pp_path ? FileHelper::get($this->pp_path, $this->pp_file) : null;
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'm_user_id', 'id');
  }

  public function contracts()
  {
    return $this->hasMany(TContract::class, 'm_member_id', 'id');
  }

  public function complaints()
  {
    return $this->hasMany(TComplaint::class, 'm_member_id', 'id');
  }
}
