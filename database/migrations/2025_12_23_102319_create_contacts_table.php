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
        Schema::create('rm_contacts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->timestamp('created')->nullable();
            $table->timestamp('modified')->nullable();
            $table->string('creator')->nullable();
            $table->string('displayname')->nullable();
            $table->string('folder')->nullable();
            $table->string('type')->nullable();
            $table->string('ext_name_line')->nullable();
            $table->string('firstname')->nullable();
            $table->string('surfix')->nullable();
            $table->string('surname')->nullable();
            $table->string('accounting_code')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_street')->nullable();
            $table->string('mailing_number')->nullable();
            $table->string('mailing_postalcode')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_country')->nullable();
            $table->string('visit_city')->nullable();
            $table->string('visit_street')->nullable();
            $table->string('visit_number')->nullable();
            $table->string('visit_postalcode')->nullable();
            $table->string('visit_state')->nullable();
            $table->string('country')->nullable();
            $table->string('invoice_city')->nullable();
            $table->string('invoice_street')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_postalcode')->nullable();
            $table->string('invoice_state')->nullable();
            $table->string('invoice_country')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('email_1')->nullable();
            $table->string('email_2')->nullable();
            $table->string('website')->nullable();
            $table->string('VAT_code')->nullable();
            $table->string('fiscal_code')->nullable();
            $table->string('commerce_code')->nullable();
            $table->string('contact_warning')->nullable();
            $table->string('tags')->nullable();
            $table->string('updateHash')->nullable();
            $table->float('distance')->nullable();
            $table->float('travel_time')->nullable();
            $table->float('purchase_number')->nullable();
            $table->float('bic')->nullable();
            $table->float('bank_account')->nullable();
            $table->float('default_person')->nullable();
            $table->float('admin_contactperson')->nullable();
            $table->float('discount_crew')->nullable();
            $table->float(' discount_transport')->nullable();
            $table->float('discount_rental')->nullable();
            $table->float('discount_sale')->nullable();
            $table->float('discount_total')->nullable();
            $table->float('discount_subrent')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->longText('image')->nullable();
            $table->string('projectnote_title')->nullable();
            $table->longText('projectnote')->nullable();
            $table->string('custom_9')->nullable();  // verzendmethode

            // We want to set CURRENT_TIMESTAMP as default value for both time fields
            // and onupdate for the updated_at field
            $table->timestamp('created_at')
                ->useCurrent();
            $table->timestamp('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_contacts');
    }
};
