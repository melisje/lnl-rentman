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
        // Tabelnaam met rm_ prefix
        Schema::create('rm_timeregistration_activities', function (Blueprint $table) {
            // Primary key (autoincrement)
            $table->id();

            // Rentman ID & Account
            $table->unsignedBigInteger('rm_id')->unique();
            $table->string('account');

            $table->string('created')->nullable();
            $table->string('modified')->nullable();
            $table->string('creator')->nullable();
            $table->string('displayname')->nullable();

            // Foreign Keys (verwijzen naar tabellen met rm_ prefix)
            $table->string('time_registration')->nullable();
            $table->foreignId('time_registration_id')->nullable()->constrained('rm_timeregistrations')->onDelete('cascade');

            $table->string('project_function')->nullable();
            $table->foreignId('project_function_id')->nullable()->constrained('rm_project_functions')->onDelete('set null');

            $table->string('subproject_function')->nullable();
            // $table->foreignId('subproject_function_id')->nullable()->constrained('rm_subproject_functions')->onDelete('set null');

            // Overige velden uit de JSON
            $table->text('description')->nullable();
            $table->integer('duration')->comment('Duration in seconds');
            $table->boolean('is_activity')->default(true);

            // Datetime velden
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->string('update_hash')->nullable();

            // Timestamps
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_timeregistration_activities');
    }
};