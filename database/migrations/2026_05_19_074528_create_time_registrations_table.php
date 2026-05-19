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
        Schema::create('rm_timeregistrations', function (Blueprint $table) {
            $table->id(); // De extra auto-increment primary key voor Laravel zelf

            // Rentman ID & Account
            $table->unsignedBigInteger('rm_id')->nullable(); // Het ID veld uit Rentman
            $table->string('account')->index()->nullable(); // Het extra string veld voor het account
            $table->unique(['account', 'rm_id']); // Unieke combinatie van account en rm_id

            // Datums & Tijden
            $table->string('created')->nullable();
            $table->string('modified')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();

            // Relaties / Paden (opgeslagen als strings op basis van je Rentman data)
            $table->string('creator')->nullable();

            $table->string('crewmember')->nullable();
            $table->foreignId('crewmember_id')->nullable()->constrained('rm_crew', 'id')->nullOnDelete(); // Relatie naar equipment via rm_id

            $table->string('leavetype')->nullable();
            $table->foreignId('leavetype_id')->nullable()->constrained('rm_leavetypes', 'id')->nullOnDelete(); // Relatie naar equipment via rm_id

            $table->string('leaverequest')->nullable();

            // Algemene info
            $table->string('displayname')->nullable();
            $table->string('status')->default('pending');
            $table->text('remark')->nullable();

            // Getallen & Afstanden
            $table->integer('distance')->default(0);
            $table->boolean('is_lunch_included')->default(false);

            // Durations
            $table->integer('duration')->default(0);
            $table->integer('break_duration')->default(0);
            $table->integer('break_duration_with_start_end')->default(0);
            $table->integer('travel_time')->default(0);
            $table->integer('correction_duration')->default(0);

            // Custom velden (JSON opslag)
            $table->json('custom')->nullable();
            $table->string('type_activiteit')->nullable();  // L&L
            $table->boolean('approval')->default(false);    // L&L
            $table->boolean('factuur_ontvangen')->default(false); // L&L

            // Created_at en updated_at timestamps
            $table->dbTimestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_timeregistrations');
    }
};
