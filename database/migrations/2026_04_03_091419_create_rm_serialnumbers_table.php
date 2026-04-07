<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rm_serialnumbers', function (Blueprint $table) {
            $table->id(); // Lokale ID
            $table->string('account')->index();
            $table->unsignedBigInteger('rm_id'); // ID vanuit Rentman API
            $table->unique(['account', 'rm_id']); // Unieke combinatie van account en rm_id
            $table->timestamp('created')->nullable();
            $table->timestamp('modified')->nullable();
            $table->string('creator')->nullable();
            $table->string('displayname')->nullable();
            $table->string('equipment')->nullable()->index(); // Pad naar equipment (bv. /equipment/123)
            $table->foreignId('equipment_id')->nullable()->constrained('rm_equipment', 'id')->nullOnDelete(); // Relatie naar equipment via rm_id
            $table->string('serial')->nullable();
            $table->string('purchasedate')->nullable();

            // Financiële velden
            $table->decimal('depreciation_monthly', 12, 2)->default(0);
            $table->decimal('book_value', 12, 2)->default(0);
            $table->decimal('residual_value', 12, 2)->default(0);
            $table->decimal('purchase_costs', 12, 2)->default(0);
            $table->decimal('current_book_value', 12, 2)->default(0);

            $table->boolean('active')->default(true);
            $table->text('remark')->nullable();
            $table->string('ref')->nullable();
            $table->string('asset_location')->nullable();
            $table->foreignId('asset_location_id')->nullable()->constrained('rm_stocklocations', 'id')->nullOnDelete(); // Relatie naar stocklocation via lokale ID
            $table->string('image')->nullable();
            $table->string('next_inspection')->nullable();
            $table->text('qrcodes')->nullable();
            $table->text('tags')->nullable();
            $table->string('last_subproject')->nullable();
            $table->boolean('sealed')->default(false);

            // Custom velden (JSON opslag)
            $table->json('custom')->nullable();

            // Hash voor update checks
            $table->string('updateHash')->nullable();

            // timestamps
            $table->dbTimestamps(); // created_at en updated_at

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rm_serialnumbers');
    }
};