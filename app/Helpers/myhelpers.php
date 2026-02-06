<?php

if (!function_exists('formatDateForUser')) {
  /**
   * Format a date string for user display.
   * * @param string|\DateTimeInterface $date
   * @return string
   */
  function formatDateForUser($date)
  {
    // Je kunt hier de Carbon library gebruiken
    return \Carbon\Carbon::parse($date)->format('d-m-Y H:i');
  }
}

if (!function_exists('sortableLink'))
  {
    /**
     * Generate the URL for sorting on a given column.
     * The direction will be reversed if the sorting column is the current sorted column.
     */
    function sortableLink($column, $currentSortBy, $currentDirection, $routeName): string
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