<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rm_functions', function (Blueprint $table) {
            $table->id(); // Interne PK
            $table->string('account');
            $table->unsignedBigInteger('rm_id');

            // Externe meta-data
            $table->dateTime('created')->nullable();
            $table->dateTime('modified')->nullable();
            $table->string('creator')->nullable();
            $table->string('displayname')->nullable();
            $table->string('name')->nullable();
            $table->string('name_external')->nullable();
            $table->string('type')->nullable(); // crew_function, transport_function, etc.

            // Relaties (als strings ivm API paden)
            $table->string('project')->nullable();
            $table->foreignId('project_id')->nullable()->constrained('rm_projects')->onDelete('cascade');
            $table->string('subproject')->nullable();
            $table->foreignId('subproject_id')->nullable()->constrained('rm_subprojects')->onDelete('cascade');
            $table->string('group')->nullable();
            $table->string('cost_rate')->nullable();
            $table->string('price_rate')->nullable();
            $table->string('taxclass')->nullable();
            $table->string('ledger')->nullable();

            // Financiën (Kosten)
            $table->decimal('cost_accommodation', 12, 2)->default(0);
            $table->decimal('cost_catering', 12, 2)->default(0);
            $table->decimal('cost_travel', 12, 2)->default(0);
            $table->decimal('cost_other', 12, 2)->default(0);
            $table->decimal('costs_fixed', 12, 2)->default(0);
            $table->decimal('costs_variable', 12, 2)->default(0);
            $table->decimal('costs_total', 12, 2)->default(0);

            // Financiën (Prijzen)
            $table->decimal('price_accommodation', 12, 2)->default(0);
            $table->decimal('price_catering', 12, 2)->default(0);
            $table->decimal('price_travel', 12, 2)->default(0);
            $table->decimal('price_other', 12, 2)->default(0);
            $table->decimal('price_fixed', 12, 2)->default(0);
            $table->decimal('price_variable', 12, 2)->default(0);
            $table->decimal('price_total', 12, 2)->default(0);

            // Tijden & Periodes
            $table->dateTime('usageperiod_start')->nullable();
            $table->dateTime('usageperiod_end')->nullable();
            $table->dateTime('planperiod_start')->nullable();
            $table->dateTime('planperiod_end')->nullable();
            $table->integer('travel_time_before')->default(0);
            $table->integer('travel_time_after')->default(0);
            $table->integer('duration')->default(0);
            $table->integer('break')->default(0);

            // Planning logica strings
            $table->string('planperiod_start_schedule_is_start')->nullable();
            $table->string('usageperiod_start_schedule_is_start')->nullable();
            $table->string('planperiod_end_schedule_is_start')->nullable();
            $table->string('usageperiod_end_schedule_is_start')->nullable();

            // Hoeveelheden & Afstand
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('distance', 10, 2)->default(0);
            $table->boolean('twoway')->default(true);

            // Booleans
            $table->boolean('is_template')->default(false);
            $table->boolean('in_financial')->default(true);
            $table->boolean('in_planning')->default(true);
            $table->boolean('is_plannable')->default(true);

            // Recurrence (Herhaling)
            $table->integer('recurrence_group')->default(0);
            $table->string('recurrence_enddate')->nullable();
            $table->string('recurrence_interval_unit')->nullable();
            $table->integer('recurrence_interval')->default(0);
            $table->string('recurrence_weekdays')->nullable();

            // Tekst & Tags
            $table->string('order')->nullable();
            $table->text('remark_client')->nullable();
            $table->text('remark_planner')->nullable();
            $table->text('remark_crew')->nullable();
            $table->text('tags')->nullable();
            $table->json('custom')->nullable();

            // Interne Timestamps
            $table->dbTimestamps();

            // Unieke index
            $table->unique(['account', 'rm_id'], 'uidx_func_account_rmid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rm_functions');
    }
};