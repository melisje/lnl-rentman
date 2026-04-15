<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AccountScope implements Scope
{
  /**
   * Pas de scope toe op een gegeven Eloquent query builder.
   */
  public function apply(Builder $builder, Model $model): void
  {
    if (session()->has('current_account')) {
      $builder->where('account', session('current_account'));
    }
  }
}
