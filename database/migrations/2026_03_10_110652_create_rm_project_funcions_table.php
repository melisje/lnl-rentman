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
            $table->unsignedBigInteger('rm_id'); // Rentman ID

            // Basis informatie
            $table->string('displayname')->nullable();
            $table->string('name')->nullable();
            $table->string('name_external')->nullable();
            $table->string('type')->nullable(); // bijv. crew_function

            // Relaties (als strings omdat Rentman API paden stuurt, of we halen de ID eruit)
            $table->string('creator')->nullable();
            $table->string('project')->nullable();
            $table->foreignId('project_id')->nullable()->constrained('rm_projects')->onDelete('cascade');
            $table->string('subproject')->nullable();
            $table->foreignId('subproject_id')->nullable()->constrained('rm_subprojects')->onDelete('cascade');
            $table->string('group')->nullable();
            $table->string('taxclass')->nullable();
            $table->string('ledger')->nullable();


            // Tijden en Periodes
            $table->dateTime('usageperiod_start')->nullable();
            $table->dateTime('usageperiod_end')->nullable();
            $table->dateTime('planperiod_start')->nullable();
            $table->dateTime('planperiod_end')->nullable();
            $table->integer('travel_time_before')->default(0);
            $table->integer('travel_time_after')->default(0);
            $table->integer('duration')->default(0);
            $table->integer('break')->default(0);

            // Financiën (Prijzen)
            $table->string('price_rate')->nullable();
            $table->decimal('price_fixed', 15, 2)->default(0);
            $table->decimal('price_variable', 15, 2)->default(0);
            $table->decimal('price_accommodation', 12, 2)->default(0);
            $table->decimal('price_catering', 12, 2)->default(0);
            $table->decimal('price_travel', 12, 2)->default(0);
            $table->decimal('price_other', 12, 2)->default(0);
            $table->decimal('price_total', 15, 2)->default(0);

            // Financiën (Kosten)
            $table->string('cost_rate')->nullable();
            $table->decimal('costs_fixed', 15, 2)->default(0);
            $table->decimal('costs_variable', 15, 2)->default(0);
            $table->decimal('cost_accommodation', 12, 2)->default(0);
            $table->decimal('cost_catering', 12, 2)->default(0);
            $table->decimal('cost_travel', 12, 2)->default(0);
            $table->decimal('cost_other', 12, 2)->default(0);
            $table->decimal('costs_total', 15, 2)->default(0);

            // Planning logica strings
            $table->string('planperiod_start_schedule_is_start')->nullable();
            $table->string('usageperiod_start_schedule_is_start')->nullable();
            $table->string('planperiod_end_schedule_is_start')->nullable();
            $table->string('usageperiod_end_schedule_is_start')->nullable();


            // Hoeveelheden & Afstand
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('distance', 10, 2)->default(0);
            $table->boolean('twoway')->default(true);

            // Booleans en overig
            $table->boolean('is_template')->default(false);
            $table->boolean('in_financial')->default(true);
            $table->boolean('in_planning')->default(false);
            $table->boolean('is_plannable')->default(false);

            // Recurrence (Herhaling)
            $table->integer('recurrence_group')->default(0);
            $table->string('recurrence_enddate')->nullable();
            $table->string('recurrence_interval_unit')->nullable();
            $table->integer('recurrence_interval')->default(0);
            $table->string('recurrence_weekdays')->nullable();


            // Tekst & Tags
            $table->string('order')->nullable();
            $table->text('remark_crew')->nullable();
            $table->text('remark_planner')->nullable();
            $table->text('remark_client')->nullable();
            $table->text('tags')->nullable();

            // Custom fields
            $table->json('custom')->nullable();

            // De gevraagde timestamps
            $table->dateTime('created')->nullable();
            $table->dateTime('modified')->nullable();

            $table->string('updateHash')->nullable();
            $table->dbTimestamps();

            // Indices
            $table->unique(['account', 'rm_id'], 'uidx_func_account_rmid');
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
