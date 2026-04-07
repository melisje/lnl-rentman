<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rm_stocklocations', function (Blueprint $table) {
            $table->id(); // Interne database ID
            $table->string('account')->nullable(); // bijv. "llstageservice"
            $table->unsignedBigInteger('rm_id')->index(); // Rentman's interne ID

            // Rentman Timestamps
            $table->timestamp('created')->nullable();
            $table->timestamp('modified')->nullable();

            // Relatie/Creator string
            $table->string('creator')->nullable();
            $table->foreignId('creator_id')->nullable()->constrained('rm_crew', 'id')->nullOnDelete(); // Optionele relatie naar crew

            // Adresgegevens
            $table->string('displayname')->nullable();
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('state_province')->nullable();
            $table->char('country', 2)->nullable();

            // Status en Visueel
            $table->boolean('active')->default(true);
            $table->string('type')->default('plannable');
            $table->string('color')->nullable();
            $table->boolean('in_archive')->default(false);

            $table->string('updateHash')->nullable(); // Voor optimalisatie: hash van de belangrijkste velden om snel te kunnen checken of er iets is veranderd

            // Laravel Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rm_stocklocations');
    }
};