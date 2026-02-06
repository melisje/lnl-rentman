<?php

namespace App\Helpers;

class Helper
{
  /**
   * Generate the URL for sorting on a given column.
   * The direction will be reversed if the sorting column is the current sorted column.
   */
  public static function sortableLink($column, $currentSortBy, $currentDirection, $routeName): string
  {
    // Determine the new direction: if we are sorting on the current column, reverse the sorting, else sort ascending.
    $newDirection = ($column === $currentSortBy && $currentDirection === 'asc') ? 'desc' : 'asc';

    // Use the route and add the query parameters
    return route($routeName, [
      'sort' => $column,
      'direction' => $newDirection
    ]);
  }
}
