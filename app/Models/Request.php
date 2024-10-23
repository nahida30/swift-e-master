<?php

namespace App\Models;

use App\Helpers\Timezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Request extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'doc_type',
    'tag_it',
    'count',
    'state',
    'county',
    'file',
    'file_name',
    'original_name',
    'uploaded_at',
    'status',
    'payment_status',
    'payment_at',
    'completed_at',
    'file2',
    'file_name2',
    'original_name2',
    'uploaded_at2',
    'completed_file',
    'read',
    'tiff_pages',
    'completed_file_name',
    'completed_original_name',
    'tiff_pages2',
  ];

  protected $appends = [
    'invoice',
    'created_at_local',
    'updated_at_local',
    'payment_at_local',
    'completed_at_local',
    'uploaded_at_local',
    'uploaded_at2_local',
  ];

  protected $casts = [
    'read' => 'boolean',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function notes()
  {
    return $this->hasMany(RequestNote::class)->orderBy('created_at', 'desc');
  }

  public function getInvoiceAttribute()
  {
    return Invoice::where('prices', 'LIKE', '%"' . $this->id . '":%')->first();
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

  public function getPaymentAtLocalAttribute()
  {
    $authUser = Auth::user();
    return Timezone::toLocal($this->payment_at, $authUser->timezone_offset);
  }

  public function getCompletedAtLocalAttribute()
  {
    $authUser = Auth::user();
    return Timezone::toLocal($this->completed_at, $authUser->timezone_offset);
  }

  public function getUploadedAtLocalAttribute()
  {
    $authUser = Auth::user();
    return Timezone::toLocal($this->uploaded_at, $authUser->timezone_offset);
  }

  public function getUploadedAt2LocalAttribute()
  {
    $authUser = Auth::user();
    return Timezone::toLocal($this->uploaded_at2, $authUser->timezone_offset);
  }
}
