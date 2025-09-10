<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;

class Setting extends Model
{
  use HasUuids, HasFactory;

  protected $table = 'm_setting';
  protected $fillable = [
    'kta_path_before',
    'kta_file_before',
    'kta_path_now',
    'kta_file_now',
    'kta_back_path_before',
    'kta_back_file_before',
    'kta_back_path_now',
    'kta_back_file_now',
    'union_chairman',
    'union_reg_number',
  ];

  public function ktaBeforeUrl()
  {
    return $this->kta_path_before ? FileHelper::get($this->kta_path_before, $this->kta_file_before) : null;
  }

  public function ktaNowUrl()
  {
    return $this->kta_path_now ? FileHelper::get($this->kta_path_now, $this->kta_file_now) : null;
  }

  public function ktaBackBeforeUrl()
  {
    return $this->kta_back_path_before ? FileHelper::get($this->kta_back_path_before, $this->kta_back_file_before) : null;
  }

  public function ktaBackNowUrl()
  {
    return $this->kta_back_path_now ? FileHelper::get($this->kta_back_path_now, $this->kta_back_file_now) : null;
  }
}
