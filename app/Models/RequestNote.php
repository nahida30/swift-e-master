<?php

namespace App\Models;

use App\Helpers\Timezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RequestNote extends Model
{
  use HasFactory;

  protected $table = 'requests_notes';

  protected $fillable = [
    'request_id',
    'receiver_id',
    'sender_id',
    'note',
    'status',
    'issue',
  ];

  protected $appends = [
    'created_at_local',
  ];

  public function request()
  {
    return $this->belongsTo(Request::class);
  }

  public function receiver()
  {
    return $this->belongsTo(User::class);
  }

  public function sender()
  {
    return $this->belongsTo(User::class);
  }

  public function getCreatedAtLocalAttribute()
  {
    $authUser = Auth::user();
    return Timezone::toLocal($this->created_at, $authUser->timezone_offset);
  }
}
