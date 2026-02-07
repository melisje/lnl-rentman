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
        Schema::create('rm_crew', function (Blueprint $table) {
            $table->id();
            $table->string('account')->default('unknown');
            $table->integer('rm_id');
            $table->string('created')->nullable();
            $table->string('modified')->nullable();
            $table->string('creator')->nullable();
            $table->string('displayname')->nullable();
            $table->string('folder')->nullable();
            $table->string('street')->nullable();
            $table->string('housenumber')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('addressline2')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->longText('remark')->nullable();
            $table->string('driving_license')->nullable();
            $table->string('contract')->nullable();
            $table->string('bank')->nullable();
            $table->string('contract_date')->nullable();
            $table->string('company_name')->nullable();
            $table->string('vat_code')->nullable();
            $table->string('coc_code')->nullable();
            $table->string('firstname')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->string('avatar')->nullable();
            $table->string('vt_fullname')->nullable();
            $table->string('default_warehouse')->nullable();
            $table->string('external_reference')->nullable();
            $table->string('tags')->nullable();
            $table->string('custom_29')->nullable();
            $table->string('custom_30')->nullable();
            $table->string('custom_34')->nullable();
            $table->string('custom_36')->nullable();
            $table->string('updateHash')->nullable();

            // add timestamp fields
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->unique(['account','rm_id']);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_crew');
    }
};


