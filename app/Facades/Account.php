<?php

namespace App\Facades;

use Error;
use Illuminate\Support\Facades\Auth;

class Account extends Auth
{
  static public function isCompany()
  {
    return Auth::user()->account_type === 'COMPANY';
  }

  static public function isPersonal()
  {
    return Auth::user()->account_type === 'PERSONAL';
  }
}
