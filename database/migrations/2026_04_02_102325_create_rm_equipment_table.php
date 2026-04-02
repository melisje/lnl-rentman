<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rm_equipment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account')->default('unknown');
            $table->integer('rm_id');

            // Rentman Systeemvelden
            $table->timestamp('created')->nullable(); // created
            $table->timestamp('modified')->nullable(); // modified
            $table->string('creator')->nullable();
            $table->string('folder')->nullable();

            // Algemene info
            $table->string('displayname')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('factor_group')->nullable();
            $table->text('internal_remark')->nullable();
            $table->text('external_remark')->nullable();
            $table->string('unit')->nullable();

            // Shop & Marketing
            $table->boolean('in_shop')->default(false);
            $table->boolean('surface_article')->default(false);
            $table->text('shop_description_short')->nullable();
            $table->text('shop_description_long')->nullable();
            $table->string('shop_seo_title')->nullable();
            $table->string('shop_seo_keyword')->nullable();
            $table->text('shop_seo_description')->nullable();
            $table->boolean('shop_featured')->default(false);

            // Financieel & Voorraad
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('subrental_costs', 15, 2)->default(0);
            $table->decimal('list_price', 15, 2)->default(0);
            $table->integer('critical_stock_level')->default(0);
            $table->string('taxclass')->nullable();
            $table->string('ledger')->nullable();
            $table->string('ledger_debit')->nullable();

            // Status & Type
            $table->string('type')->default('set'); // bijv. set
            $table->string('rental_sales')->nullable(); // bijv. Sale
            $table->boolean('temporary')->default(false);
            $table->boolean('in_planner')->default(true);
            $table->boolean('in_archive')->default(false);
            $table->string('stock_management')->nullable();

            // Fysieke eigenschappen
            $table->decimal('volume', 15, 4)->default(0);
            $table->integer('packed_per')->default(1);
            $table->decimal('height', 10, 2)->default(0);
            $table->decimal('width', 10, 2)->default(0);
            $table->decimal('length', 10, 2)->default(0);
            $table->decimal('weight', 10, 2)->default(0);
            $table->decimal('empty_weight', 10, 2)->default(0);
            $table->decimal('power', 10, 2)->default(0);
            $table->decimal('current', 10, 2)->default(0);
            $table->string('country_of_origin', 5)->nullable();

            // Media & Identificatie
            $table->string('image')->nullable();
            $table->string('defaultgroup')->nullable();
            $table->boolean('is_combination')->default(false);
            $table->string('is_physical')->nullable();
            $table->boolean('can_edit_content_during_planning')->default(true);
            $table->string('strict_container_content')->nullable();
            $table->text('qrcodes')->nullable();
            $table->text('qrcodes_of_serial_numbers')->nullable();
            $table->text('tags')->nullable();

            // Actuele voorraad (berekende velden uit Rentman)
            $table->integer('current_quantity_excl_cases')->default(0);
            $table->integer('current_quantity')->default(0);
            $table->integer('quantity_in_cases')->default(0);
            $table->string('location_in_warehouse')->nullable();

            // Custom velden (JSON)
            $table->json('custom')->nullable();
            $table->decimal('avg_check_time_min', 15, 2)->nullable();
            $table->decimal('avg_inbound_time_min', 15, 2)->nullable();
            $table->decimal('avg_outbound_time_min', 15, 2)->nullable();
            $table->string('default_checkzone')->nullable();
            $table->string('inbound_type')->nullable();
            $table->boolean('logistic_item')->default(false);
            $table->string('polaris')->nullable();
            $table->text('short_name')->nullable();

            $table->string('updateHash')->nullable();

            // Laravel Timestamps (voor de DB record zelf)
            $table->dbTimestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rm_equipment');
    }
};