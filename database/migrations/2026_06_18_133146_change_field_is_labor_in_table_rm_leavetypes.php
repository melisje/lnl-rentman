<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rm_leavetypes', function (Blueprint $table) {
            // Wijzigt de bestaande is_labor kolom naar een enum met 'leave' als standaard
            $table->enum('is_labor', ['leave', 'worked'])->default('leave')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_leavetypes', function (Blueprint $table) {
            // Zet de kolom bij een rollback netjes terug naar de oorspronkelijke situatie
            $table->integer('is_labor')->default(0)->change();
        });
    }
};