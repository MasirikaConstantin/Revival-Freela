<?php

namespace App\Rules;

use App\Models\Admin;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class MatchEmailRule implements Rule
{
  public $personType;

  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct($role)
  {
    // here, $role variable defines whether it is admin or user
    $this->personType = $role;
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    if ($this->personType == 'admin') {
      $admin = Admin::query()->where('email', '=', $value)->first();

      if (is_null($admin)) {
        return false;
      } else {
        return true;
      }
    } else if ($this->personType == 'user') {
      $user = User::query()->where('email_address', '=', $value)->first();

      if (is_null($user)) {
        return false;
      } else {
        return true;
      }
    } else if ($this->personType == 'seller') {
      $seller = Seller::query()->where('email', '=', $value)->first();
      if (is_null($seller)) {
        return false;
      } else {
        return true;
      }
    }
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return 'This email does not exist!';
  }
}
