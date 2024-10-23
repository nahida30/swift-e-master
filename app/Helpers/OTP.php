<?php

namespace App\Helpers;

class OTP
{
  public static function generateOTP($length = 6)
  {
    $characters = '0123456789';
    $otp = '';

    for ($i = 0; $i < $length; $i++) {
      $otp .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $otp;
  }
}
