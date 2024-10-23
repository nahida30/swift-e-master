<?php

namespace App\Models;

use App\Helpers\Timezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'prices',
    'amount',
    'due_date',
    'description',
    'status',
    'details',
  ];

  protected $appends = [
    'requests',
    'due_date_local',
    'user_due_date_local',
    'created_at_local',
    'user_created_at_local',
    'updated_at_local',
    'user_updated_at_local',
  ];

  protected $casts = [
    'details' => 'array',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function getPricesAttribute($value)
  {
    return json_decode($value, true);
  }

  public function setPricesAttribute($value)
  {
    $this->attributes['prices'] = json_encode($value);
  }

  public function getRequestsAttribute()
  {
    $requestIds = [];
    foreach ($this->prices as $requestId => $price) {
      $requestIds[] = $requestId;
    }
    return Request::whereIn('id', $requestIds)->get();
  }

  public function getDueDateLocalAttribute()
  {
    $authUser = Auth::user();
    return Timezone::toLocal($this->due_date, $authUser->timezone_offset);
  }

  public function getUserDueDateLocalAttribute()
  {
    return Timezone::toLocal($this->due_date, $this->user->timezone_offset);
  }

  public function getCreatedAtLocalAttribute()
  {
    $authUser = Auth::user();
    return Timezone::toLocal($this->created_at, $authUser->timezone_offset);
  }

  public function getUserCreatedAtLocalAttribute()
  {
    return Timezone::toLocal($this->created_at, $this->user->timezone_offset);
  }

  public function getUpdatedAtLocalAttribute()
  {
    $authUser = Auth::user();
    return Timezone::toLocal($this->updated_at, $authUser->timezone_offset);
  }

  public function getUserUpdatedAtLocalAttribute()
  {
    return Timezone::toLocal($this->updated_at, $this->user->timezone_offset);
  }
}
