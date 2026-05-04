<?php

namespace App\Models\Production;

use App\Models\Rentman\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checklist extends Model
{
    protected $table = 'prod_checklists';
    protected $primaryKey = 'id';
    public $incrementing = true;

    /**
     * The attributes that aren't mass assignable.
     * An empty list means that all fields are mass assignable
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id'];

    /**
     * De casts die moeten worden toegepast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime', // Optioneel: direct formatteren
            'modified_at' => 'datetime', // Optioneel: direct formatteren
        ];
    }

    /**
     * Get the Checklist items for the Checklist.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ChecklistItem::class, 'checklist_id', 'id');
    }

    public function countCompletedItems() : int
    {
        return $this->items()->where('is_completed', true)->count();
    }

    /**
     * Accessor voor een kortere syntax: $checklist->completed_items_count
     */
    public function getCompletedItemsCountAttribute(): int
    {
        return $this->countCompletedItems();
    }

    public function getStatusColorAttribute(): string
    {
        $total = $this->items_count; // Let op: zorg dat withCount('items') in je controller staat
        $completed = $this->countCompletedItems();

        if ($completed === 0) {
            return 'danger'; // Rood
        }

        if ($completed < $total) {
            return 'warning'; // Oranje
        }

        return 'success'; // Groen
    }

    public function getStatusPercentageAttribute(): int|null
    {
        $total = $this->items_count; // Let op: zorg dat withCount('items') in je controller staat
        $completed = $this->countCompletedItems();

        if ($total)
        {
            return 100 * $completed / $total;
        }

        return null;
    }

    /**
     * Get the project this checklist belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    /**
     * Add items to the checklist based on a template. The template is an array
     * of item data, where each item data is an associative array with a
     * 'description' key.
     */
    public function addTemplateItems(ChecklistTemplate $template): Checklist
    {
        $templateItems = $template->items()->get();

        foreach ($templateItems as $itemData)
        {
            $this->items()->create([
                'name' => $itemData['name'],
                'sequence' => $itemData['sequence'],
                'is_completed' => false,
            ]);
        }

        return $this;
    }
}
