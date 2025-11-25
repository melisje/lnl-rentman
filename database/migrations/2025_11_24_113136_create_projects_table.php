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

        $fields = [
            'id',
            'created',
            'modified',
            'displayname',
            'name',
            'planperiod_start',
            'planperiod_end',
            'usageperiod_start',
            'usageperiod_end',
            'equipment_period_from',
            'equipment_period_to',
            'custom_1',
            'custom_2',
            'custom_32',
            'custom_33',
            'custom_38',
            'updateHash',
            'creator',

            'number',
            'reference',
            'customer',
            'project_type',
            'color',
            'cust_contact',
            'loc_contact',
            'account_manager',
        ];

        Schema::create('rm_projects', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->string('created',255);
            $table->string('displayname',255);
            $table->string('name',255);
            $table->string('reference')->nullable();
            $table->string('planperiod_start',255)->nullable();
            // $table->timestamp('planperiod_start2')->nullable();
            $table->string('planperiod_end',255)->nullable();
            $table->string('usageperiod_start',255)->nullable();
            $table->string('usageperiod_end',255)->nullable();
            $table->string('equipment_period_from',255)->nullable();
            $table->string('equipment_period_to',255)->nullable();
            $table->integer('custom_1')->nullable();
            $table->string('custom_2')->nullable();
            $table->string('custom_32')->nullable();
            $table->string('custom_33')->nullable();
            $table->string('custom_38')->nullable();
            $table->string('updateHash')->nullable();
            $table->string('creator')->nullable();
            $table->string('account_manager')->nullable();
            $table->string('customer')->nullable();
            $table->string('cust_contact')->nullable();
            $table->string('loc_contact')->nullable();
            $table->string('project_type')->nullable();
            $table->string('color')->nullable();
            $table->string('modified',255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_projects');
    }
};
