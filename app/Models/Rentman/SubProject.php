<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;


class SubProject extends Model
{
    /** @use HasFactory<\Database\Factories\Rentman\SubProjectFactory> */
    use HasFactory;

    protected $table = 'rm_subprojects';
    // protected $primaryKey = 'id';
    // public $incrementing = false;
    protected $guarded = ['id'];
    protected $appends = ['status_name'];

    /**
     * De casts die moeten worden toegepast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created' => 'datetime', // Dit cast de kolom naar Carbon
            'modified' => 'datetime', // Dit cast de kolom naar Carbon
            'usageperiod_start' => 'datetime', // Dit cast de kolom naar Carbon
            'usageperiod_end' => 'datetime', // Dit cast de kolom naar Carbon
            'planperiod_start' => 'datetime', // Dit cast de kolom naar Carbon
            'planperiod_end' => 'datetime', // Dit cast de kolom naar Carbon
            'equipment_period_from' => 'datetime', // Dit cast de kolom naar Carbon
            'equipment_period_to' => 'datetime', // Dit cast de kolom naar Carbon
            'created_at' => 'datetime:Y-m-d H:i', // Optioneel: direct formatteren
            'modified_at' => 'datetime:Y-m-d H:i', // Optioneel: direct formatteren
        ];
    }

    /**
     * Get the Project that owns the subproject.
     */
    public function parent_project(): BelongsTo
    {
        return $this->belongsTo(Project::class,'projects_id','id');
    }


    /**
     * Haalt de menselijke naam van de status op.
     */
    protected function statusName(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1. Extraheer de rm_id uit het pad (bijv. /status/12 -> 12)
                // We gebruiken hier de logica van de helper:
                // $statusId = Str::afterLast(trim($this->status, '/'), '/');
                $statusId = extract_id($this->status);

                if (!$statusId) {
                    return 'Geen status';
                }

                // 2. Zoek in de Status tabel naar de match voor dit account en rm_id
                // Cache dit resultaat eventueel voor performance bij loops
                $status = Status::where('account', $this->account)
                    ->where('rm_id', $statusId)
                    ->first();

                return $status ? $status->name : "????";
            },
        );
    }
}

