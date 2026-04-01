<?php

use App\Http\Controllers\Production\ChecklistController;
use App\Http\Controllers\Production\ChecklistItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Production\ChecklistTemplateController;
use App\Http\Controllers\Production\ChecklistTemplateItemController;


Route::middleware(['can:access-checklists'])
  ->prefix('production')
  ->name('production.')
  ->group(function () {

    // Items beheer (AJAX)
    // We zetten deze bovenaan of gebruiken een duidelijk ander pad om conflicten met de resource ID te voorkomen
    Route::post('/checklist-items/reorder', [ChecklistItemController::class, 'reorder'])->name('checklist.item.reorder');
    Route::patch('/checklist-items/{item}', [ChecklistItemController::class, 'update'])->name('checklist.item.update');
    Route::delete('/checklist-items/{item}', [ChecklistItemController::class, 'destroy'])->name('checklist.item.destroy');
    Route::post('/checklist/{checklist}/items', [ChecklistItemController::class, 'store'])->name('checklist.item.store');

    // Hoofdresource
    Route::resource('checklist', ChecklistController::class);


  /*
  * Checklist template routes
  */
  Route::prefix('checklist')
  ->name('checklist.')
  ->group(function(){
    Route::resource('template', ChecklistTemplateController::class);

    // Nested Template Item routes with unique names
    Route::resource('template.items', ChecklistTemplateItemController::class)
      ->shallow()
      ->names([
        'index'   => 'template.items.index',
        'store'   => 'template.items.store',
        'create'  => 'template.items.create',
        'show'    => 'template-items.show',
        'edit'    => 'template-items.edit',
        'update'  => 'template-items.update',
        'destroy' => 'template-items.destroy',
      ]);
  });


});
