<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PricesRequired implements Rule
{
  public function passes($attribute, $value)
  {
    if (!is_array($value)) {
      return false;
    }

    foreach ($value as $key => $val) {
      if (empty($val)) {
        return false;
      }
    }
    return true;
  }

  public function message()
  {
    return 'Please enter a price';
  }
}
