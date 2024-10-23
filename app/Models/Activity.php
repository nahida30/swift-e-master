<?php

namespace App\Models;

use App\Helpers\Timezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Activity extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'type',
    'reference_id',
    'description',
  ];

  protected $appends = [
    'created_at_local',
    'updated_at_local',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function reference()
  {
    if ($this->type == 'request') {
      return $this->belongsTo(Request::class);
    }
    if ($this->type == 'note') {
      return $this->belongsTo(RequestNote::class);
    }
    if ($this->type == 'invoice') {
      return $this->belongsTo(Invoice::class);
    }
  }

  public function getCreatedAtLocalAttribute()
  {
    $authUser = Auth::user();
    return Timezone::toLocal($this->created_at, $authUser->timezone_offset);
  }

  public function getUpdatedAtLocalAttribute()
  {
    $authUser = Auth::user();
    return Timezone::toLocal($this->updated_at, $authUser->timezone_offset);
  }
}
