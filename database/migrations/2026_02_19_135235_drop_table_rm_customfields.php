<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('rm_customfields');
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('rm_customfields', function (Blueprint $table)
        {
            $table->id();
            $table->string('account')->nullable();
            $table->integer('rm_id');
            $table->string('name')->unique();
            $table->string('belongs_to')->nullable();
            $table->string('type')->nullable();
            $table->string('hidden')->nullable();
            $table->string('private')->nullable();
            $table->boolean('mandatory')->nullable();
            $table->dbTimestamps();

            $table->unique(['account', 'rm_id']);
        });

        // Actie 1: Probeer de oude index te droppen in een eigen schema block
        try
        {
            Schema::table('rm_customfields', function (Blueprint $table)
            {
                $table->dropUnique(['name']);
            });
        } catch (QueryException $e)
        {
            dump("Informatie: Index 'name' bestond niet, dus hoefde niet gedropt te worden.");
        }

        // Actie 2: Voeg de nieuwe unieke index toe
        Schema::table('rm_customfields', function (Blueprint $table)
        {
            $table->unique(['account', 'name']);
        });

    }
};
