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
        Schema::table('rm_customfields', function (Blueprint $table) {
            $table->dropPrimary('id');
            $table->renameColumn('id', 'rm_id');
            $table->id()->first();
            $table->string('account')->nullable()->after('id');
            $table->unique(['account', 'rm_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_customfields', function (Blueprint $table) {
            $table->dropUnique(['account', 'rm_id']);
            $table->dropColumn('account');
            $table->integer('id')->change(); // remove autoincrement
            $table->dropPrimary('id'); // drop primary
            $table->dropColumn('id'); // drop column
            $table->renameColumn('rm_id', 'id');
            $table->integer('id')
                // ->autoIncrement()
                ->primary()
                ->change();
        });
    }
};
