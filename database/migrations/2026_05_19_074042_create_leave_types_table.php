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
        Schema::create('rm_leavetypes', function (Blueprint $table) {
            $table->id(); // Extra autonumber primary key voor Laravel

            // Rentman ID & Account
            $table->unsignedBigInteger('rm_id')->nullable(); // Het ID veld uit Rentman
            $table->string('account')->index()->nullable(); // Het extra string veld voor het account
            $table->unique(['account', 'rm_id']); // Unieke combinatie van account en rm_id


            // Datums & Tijden
            $table->string('created')->nullable();
            $table->string('modified')->nullable();

            // Algemene info
            $table->string('displayname')->nullable();
            $table->string('name')->nullable();
            $table->string('type', 10)->nullable(); // Bijv. "C"
            $table->string('payroll_code')->nullable();
            $table->string('color', 30)->nullable(); // Voor hex-kleuren of kleurnamen
            $table->string('creator')->nullable();

            // Booleans (standaard op false gezet voor de veiligheid)
            $table->boolean('requires_approval')->default(false);
            $table->boolean('affects_availability')->default(false);
            $table->boolean('has_balance')->default(false);
            $table->boolean('has_calculated_duration')->default(false);
            $table->boolean('can_have_activities')->default(false);
            $table->boolean('counts_in_totals')->default(false);

            // Is_labor was een 0 in de JSON, slaan we op als integer of boolean (hier integer)
            $table->integer('is_labor')->default(0);

            // Datum (aangezien het 'balance_start_date' is, opgeslagen als date of string)
            $table->string('balance_start_date')->nullable();

            // Het JSON veld
            $table->json('custom')->nullable();

            // Created_at en updated_at (vervangen 'created' en 'modified')
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_leavetypes');
    }
};
