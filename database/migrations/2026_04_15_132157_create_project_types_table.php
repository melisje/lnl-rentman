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
        Schema::create('rm_project_types', function (Blueprint $table) {
            $table->id(); // Dit dekt de "id": 0

            // Account veld voor de Global Scope filtering
            $table->string('account')->index();

            // API Data velden
            $table->string('rm_id'); // Het ID vanuit de externe API
            $table->string('displayname')->nullable();
            $table->string('name');
            $table->string('color', 7)->nullable(); // Bijv. #FFFFFF
            // $table->enum('type2', ['regular', 'supplier', 'transfer', 'shifts'])->default('regular'); // Type veld
            $table->string('type')->default('regular'); // "regular" "supplier" "transfer" "shifts"
            $table->string('creator_path')->nullable(); // Voor de "/crew/0" string

            // Hash voor update checks
            $table->string('updateHash')->nullable();

            // Timestamps
            // We gebruiken de standaard Laravel timestamps,
            // maar je kunt ze vullen met de 'created'/'modified' uit de JSON.
            $table->dbTimestamps();

            $table->unique(['account', 'rm_id']); // Unieke combinatie van account en rm_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('rm_project_types');
        Schema::enableForeignKeyConstraints();
    }
};