<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actie 1: Probeer de oude index te droppen in een eigen schema block
        try {
            Schema::table('rm_customfields', function (Blueprint $table) {
                $table->dropUnique(['name']);
            });
        } catch (QueryException $e) {
            dump("Informatie: Index 'name' bestond niet, dus hoefde niet gedropt te worden.");
        }

        // Actie 2: Voeg de nieuwe unieke index toe
        Schema::table('rm_customfields', function (Blueprint $table) {
            $table->unique(['account', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Actie 1: Probeer de gecombineerde index te droppen
        try {
            Schema::table('rm_customfields', function (Blueprint $table) {
                $table->dropUnique(['account', 'name']);
            });
        } catch (QueryException $e) {
            dump("Informatie: Gecombineerde index bestond niet.");
        }

        // Actie 2: Probeer de oude index te herstellen
        try {
            Schema::table('rm_customfields', function (Blueprint $table) {
                $table->unique(['name']);
            });
        } catch (QueryException $e) {
            dump("Informatie: Index 'name' kon niet worden hersteld (bestaat mogelijk al).");
        }
    }
};
