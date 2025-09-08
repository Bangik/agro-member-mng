<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;

class TEvent extends Model
{
  use HasFactory, SoftDeletes, HasUuids;

  protected $table = 't_event';
  public $incrementing = false;

  protected $fillable = [
    'id',
    'm_event_item_id',
    'm_user_id',
    'invoice_number',
    'ticket_code',
    'ticket_qr_path',
    'ticket_qr_file',
    'ticket_path',
    'ticket_file',
    'payment_method',
    'pg_reff_id',
    'pg_status',
    'pg_invoice',
    'pg_invoice_url',
    'pg_fee',
    'pg_tax',
    'amount',
    'total',
    'queue_number',
    'available_at',
    'expired_at',
    'is_checked'
  ];

  public function eventItem()
  {
    return $this->belongsTo(EventItem::class, 'm_event_item_id', 'id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'm_user_id', 'id');
  }
}
