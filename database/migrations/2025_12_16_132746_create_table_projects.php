<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_projects', function (Blueprint $table) {
            // $table->id();
            $table->integer('id')->primary();
            $table->string('created')->nullable();
            $table->string('modified')->nullable();
            $table->string('updateHash')->nullable();
            $table->string('number')->nullable();
            $table->string('displayname')->nullable();
            $table->string('name')->nullable();
            $table->string('reference')->nullable();
            $table->string('location')->nullable();
            $table->string('project_type')->nullable();
            $table->string('creator')->nullable();
            $table->string('deposit_status')->nullable();
            $table->string('customer')->nullable();
            $table->string('loc_contact')->nullable();
            $table->string('cust_contact')->nullable();
            $table->string('account_manager')->nullable();
            $table->string('color')->nullable();
            $table->string('tags')->nullable();
            $table->string('usageperiod_start')->nullable();
            $table->string('usageperiod_end')->nullable();
            $table->string('planperiod_start')->nullable();
            $table->string('planperiod_end')->nullable();
            $table->string('equipment_period_from')->nullable();
            $table->string('equipment_period_to')->nullable();
            $table->string('custom_2')->nullable();
            $table->string('custom_32')->nullable();
            $table->string('custom_33')->nullable();
            $table->longText('conditions')->nullable();
            $table->integer('custom_1')->nullable();
            $table->integer('custom_38')->nullable();
            $table->decimal('refundabledeposit',10,4)->nullable();
            $table->decimal('already_invoiced',10,4)->nullable();
            $table->decimal('weight',10,4)->nullable();
            $table->decimal('power',10,4)->nullable();
            $table->decimal('current',10,4)->nullable();
            $table->decimal('purchasecosts',10,4)->nullable();
            $table->decimal('volume',10,4)->nullable();

            $table->timestamp('_created')->nullable()->useCurrent();
            $table->timestamp('_updated')->nullable()->useCurrentOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_projects');
    }
};
