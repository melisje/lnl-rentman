<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Production\Checklist;

class ChecklistItem extends Model
{
    protected $table = 'prod_checklist_items';
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
     * Get the subprojects for the project.
     */
    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class,'checklist_id','id');
    }   //
}
