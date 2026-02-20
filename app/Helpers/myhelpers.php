<?php

use Illuminate\Support\Str;

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


if (!function_exists('extract_id'))
{
  /**
   * Zet een pad zoals /crew/33 om naar 33.
   */
  function extract_id(?string $path): ?string
  {
    if (!$path) return null;

    // Trim slashes en pak alles na de laatste slash
    return Str::afterLast(trim($path, '/'), '/');
  }
}