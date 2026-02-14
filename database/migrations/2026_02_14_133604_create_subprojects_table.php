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
        Schema::create('rm_subprojects', function (Blueprint $table)
        {
            $table->id(); //PK
            $table->string('account');
            $table->string('rm_id');
            $table->bigInteger('projects_id')->unsigned(); //FK to rm_projects table

            // Rentman fields
            $table->timestamp('created')->nullable();
            $table->timestamp('modified')->nullable();
            $table->string('creator')->nullable();
            $table->string('displayname')->nullable();
            $table->string('project')->nullable();
            $table->string('order')->nullable();
            $table->string('name')->nullable();
            $table->string('status')->nullable();
            $table->string('is_template')->nullable();
            $table->string('location')->nullable();
            $table->string('loc_contact')->nullable();
            $table->string('insurance_rate')->nullable();
            $table->string('discount_rental')->nullable();
            $table->string('discount_sale')->nullable();
            $table->string('discount_crew')->nullable();
            $table->string('discount_transport')->nullable();
            $table->string('discount_additional_costs')->nullable();
            $table->string('discount_subproject')->nullable();
            $table->string('discount_fixed')->nullable();
            $table->string('discount_fixed_amount')->nullable();
            $table->string('fixed_price')->nullable();
            $table->string('in_planning')->nullable();
            $table->string('in_financial')->nullable();
            $table->string('asset_location_from')->nullable();
            $table->string('already_invoiced')->nullable();
            $table->timestamp('usageperiod_start')->nullable();
            $table->timestamp('usageperiod_end')->nullable();
            $table->timestamp('planperiod_start')->nullable();
            $table->timestamp('planperiod_end')->nullable();
            $table->timestamp('equipment_period_from')->nullable();
            $table->timestamp('equipment_period_to')->nullable();
            $table->float('weight')->nullable();
            $table->float('power')->nullable();
            $table->float('current')->nullable();
            $table->float('purchasecosts')->nullable();
            $table->float('volume')->nullable();
            $table->string('updateHash')->nullable();
            $table->text('custom')->nullable();

            // add timestamp fields
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            // indices
            $table->unique(['account', 'rm_id']);

            // Foreign Keys
            $table->foreign('projects_id')->references('id')->on('rm_projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_subprojects');
    }
};
