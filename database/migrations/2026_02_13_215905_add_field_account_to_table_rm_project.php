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
        Schema::table('rm_projects', function (Blueprint $table) {
            //
            // $table->integer('id')->change();
            $table->dropPrimary('id');
            $table->renameColumn('id','rm_id');
            $table->id()->first();
            $table->string('account')->nullable()->after('id');
            $table->text('custom')->nullable()->after('equipment_period_to');
            $table->float('project_total_price')->nullable()->after('equipment_period_to');
            $table->unique(['account','rm_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_projects', function (Blueprint $table) {
            //
            $table->dropColumn('project_total_price');
            $table->dropUnique(['account','rm_id']);
            $table->dropColumn('custom');
            $table->dropColumn('account');
            $table->integer('id')->change(); // remove autoincrement
            $table->dropPrimary('id'); // drop primary
            $table->dropColumn('id'); // drop column
            $table->renameColumn('rm_id','id');
            $table->integer('id')
                // ->autoIncrement()
                ->primary()
                ->change();
        });
    }
};
