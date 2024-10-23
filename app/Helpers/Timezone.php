<?php

namespace App\Helpers;

use App\Models\User;
use Exception;

class Timezone
{

  public static function name($offset)
  {
    try {
      if (is_string($offset)) {
        $offset = (float)$offset;
      }
      return timezone_name_from_abbr("", -$offset, 0);
    } catch (Exception $e) {
      return 'UTC';
    }
  }

  public static function toUtc($time, $offset, $format = 'Y-m-d H:i:s')
  {
    return date($format, strtotime($time) + ($offset));
  }

  public static function toLocal($time, $offset, $format = 'Y-m-d H:i:s')
  {
    return date($format, strtotime($time) - ($offset));
  }

  public static function getOffset($tzName)
  {
    $timezone = timezone_open($tzName);
    $nowUtc = date_create('now', timezone_open('UTC'));
    return -timezone_offset_get($timezone, $nowUtc);
  }

  public static function updateTzOffset($offset, $email)
  {
    $update = [
      'timezone_offset' => $offset,
    ];
    return User::where('email', $email)->update($update);
  }
}
