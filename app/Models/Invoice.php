<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
  use HasFactory;

  public const STATUS_PAID = 'paid';
  public const STATUS_UNPAID = 'unpaid';

  public const STATUSES = [
    self::STATUS_PAID => ['label' => 'Paid'],
    self::STATUS_UNPAID => ['label' => 'Unpaid']
  ];

  protected $fillable = [
    'order_id',
    'number',
    'amount',
    'status',
    'due_date',
  ];

  public function payment(): HasOne
  {
    return $this->hasOne(Payment::class);
  }

  public function order(): BelongsTo
  {
    return $this->belongsTo(Order::class);
  }
}
