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
        Schema::create('rm_project_functions', function (Blueprint $table)
        {
            $table->id(); // Interne autonumbering ID
            $table->string('account')->index(); // Voor multi-tenant filtering
            $table->unsignedBigInteger('rm_id')->unique(); // Rentman ID

            // Basis informatie
            $table->string('displayname')->nullable();
            $table->string('name')->nullable();
            $table->string('name_external')->nullable();
            $table->string('type')->nullable(); // bijv. crew_function

            // Relaties (als strings omdat Rentman paden stuurt, of we halen de ID eruit)
            $table->string('creator')->nullable();
            $table->string('project')->nullable();
            $table->string('subproject')->nullable();
            $table->string('group')->nullable();
            $table->string('taxclass')->nullable();
            $table->string('ledger')->nullable();

            // Tijden en Periodes
            $table->dateTime('usageperiod_start')->nullable();
            $table->dateTime('usageperiod_end')->nullable();
            $table->dateTime('planperiod_start')->nullable();
            $table->dateTime('planperiod_end')->nullable();
            $table->integer('duration')->default(0);
            $table->integer('break')->default(0);
            $table->integer('travel_time_before')->default(0);
            $table->integer('travel_time_after')->default(0);

            // Financiële data
            $table->decimal('price_fixed', 15, 2)->default(0);
            $table->decimal('price_variable', 15, 2)->default(0);
            $table->decimal('price_total', 15, 2)->default(0);
            $table->decimal('costs_fixed', 15, 2)->default(0);
            $table->decimal('costs_variable', 15, 2)->default(0);
            $table->decimal('costs_total', 15, 2)->default(0);

            // Booleans en overig
            $table->integer('amount')->default(1);
            $table->boolean('is_template')->default(false);
            $table->boolean('in_financial')->default(true);
            $table->boolean('in_planning')->default(false);
            $table->boolean('is_plannable')->default(false);
            $table->text('remark_crew')->nullable();
            $table->string('update_hash')->nullable();

            // De gevraagde timestamps
            $table->dateTime('rm_created')->nullable();
            $table->dateTime('rm_modified')->nullable();

            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_project_functions');
    }
};
