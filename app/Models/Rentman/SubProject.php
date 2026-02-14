<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProject extends Model
{
    /** @use HasFactory<\Database\Factories\Rentman\SubProjectFactory> */
    use HasFactory;

    protected $table = 'rm_subprojects';
    // protected $primaryKey = 'id';
    // public $incrementing = false;
    protected $guarded = ['id'];

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
}

