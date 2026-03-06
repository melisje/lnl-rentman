<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rm_projectcrew', function (Blueprint $table) {
            $table->id();

            $table->string('account'); // llstageservice | ledvisions
            $table->string('rm_id'); // rm_id: 19855
            $table->unique(['account','rm_id' ]);

            // Timestamps
            $table->dateTime('created')->nullable();
            $table->dateTime('modified')->nullable();
            $table->string('creator')->nullable();

            // Display & Identificatie
            $table->string('displayname')->nullable();
            $table->string('updateHash')->nullable();

            // Relaties (gebaseerd op de URL-strings in de JSON)
            $table->string('cost_rate')->nullable(); // Slaat bijv. "/rates/66083" op
            $table->string('function')->nullable();  // Slaat bijv. "/projectfunctions/27684" op
            $table->foreignId('function_id')->nullable()->constrained('rm_functions');
            $table->string('crewmember')->nullable(); // Slaat bijv. "/crew/257" op
            $table->foreignId('crew_id')->constrained('rm_crew')->onUpdate('cascade'); //FK naar rm_crew
            $table->foreignId('subproject_id')->constrained('rm_subprojects')->onUpdate('cascade')->onDelete('cascade'); //FK naar rm_subprojects

            // Financiële data (Decimals voor precisie bij geldzaken)
            $table->decimal('cost_accommodation', 10, 2)->default(0);
            $table->decimal('cost_catering', 10, 2)->default(0);
            $table->decimal('cost_travel', 10, 2)->default(0);
            $table->decimal('cost_other', 10, 2)->default(0);
            $table->decimal('costs', 10, 2)->default(0);
            $table->decimal('cost_actual', 10, 2)->default(0);
            $table->decimal('cost_planned', 10, 2)->default(0);
            $table->decimal('diff_cost', 10, 2)->default(0);

            // Planning & Tijden
            $table->dateTime('planperiod_start')->nullable();
            $table->dateTime('planperiod_end')->nullable();
            $table->integer('hours_registered')->default(0); // In seconden op basis van JSON
            $table->integer('hours_planned')->default(0);    // In seconden op basis van JSON
            $table->integer('diff_hours')->default(0);

            // Status & Booleans
            $table->boolean('visible')->default(true);
            $table->boolean('project_leader')->default(false);
            $table->boolean('is_visible_on_dashboard')->default(true);
            $table->string('transport')->nullable();
            $table->string('activity_status')->nullable(); // e.g., "under_time"

            // Tekstvelden
            $table->text('remark')->nullable();
            $table->text('remark_planner')->nullable();
            $table->string('invoice_reference')->nullable();

            // JSON veld voor custom data
            $table->json('custom')->nullable();

            // Indexen voor sneller zoeken
            $table->index('crewmember');
            $table->index('planperiod_start');

            $table->dbTimestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rm_projectcrew');
    }
};