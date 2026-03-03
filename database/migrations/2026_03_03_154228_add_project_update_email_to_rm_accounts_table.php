<?php

use App\Models\Rentman\Account;
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
        Schema::table('rm_accounts', function (Blueprint $table) {
            $table->string('project_update_email')->nullable()->after('url');
        });

        Account::updateOrCreate(
            ['account' => 'llstageservice'], // De zoekcriteria
            ['project_update_email' => 'project_updates@llstageservice.be'] // De data
        );

        // Account::where('account', 'llstageservice')->update([
        //     'project_update_email' => 'project_updates@llstageservice.be'
        // ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_accounts', function (Blueprint $table) {
            $table->dropColumn('project_update_email');
        });
    }
};
