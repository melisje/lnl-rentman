<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prod_time_registrations', function (Blueprint $table) {
            $table->id();

            // Foreign keys (zorg dat de datatypes matchen met rm_projects en rm_crew)
            $table->foreignId('project_id')->constrained('rm_projects')->onDelete('cascade');
            $table->foreignId('crewmember_id')->constrained('rm_crew')->onDelete('cascade');

            // Budget type met een ENUM
            $table->enum('budget_type', ['PM', 'light', 'sound', 'rigging']);

            // Timestamps voor start en eind (optioneel)
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();

            // Duration als float (bijv. voor directe invoer of berekende uren)
            $table->float('duration')->nullable();

            // Opmerkingen
            $table->text('remarks')->nullable();

            // Standaard timestamps, maar updated_at hernoemd naar modified_at
            // $table->timestamp('created_at')->nullable();
            // $table->timestamp('modified_at')->nullable();
            $table->dbTimestamps(); // Gebruik de macro voor timestamps
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prod_time_registrations');
    }
};
