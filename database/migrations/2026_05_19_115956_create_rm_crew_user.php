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
        Schema::create('rm_crew_user', function (Blueprint $table) {
            $table->id();

            // Het account waartoe deze koppeling behoort
            $table->string('account');

            // Foreign key naar de users tabel
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Foreign key naar de rm_crew tabel
            // (Ik ga er hier vanuit dat de tabel 'rm_crews' heet en de PK 'id' is)
            $table->foreignId('rm_crew_id')
                ->constrained('rm_crew')
                ->onDelete('cascade');

            $table->dbTimestamps();

            /**
             * COMPOSITE UNIQUE INDEX
             * Dit zorgt ervoor dat de combinatie van account + user + rm_crew uniek is.
             * Een user kan binnen hetzelfde account dus niet twee keer aan dezelfde crew-member gelinkt worden.
             */
            $table->unique(['account', 'user_id', 'rm_crew_id'], 'rm_crew_user_account_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_crew_user');
    }
};
