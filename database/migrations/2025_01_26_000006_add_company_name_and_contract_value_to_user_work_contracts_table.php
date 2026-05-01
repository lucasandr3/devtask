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
        Schema::table('user_work_contracts', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('user_id');
            $table->decimal('contract_value', 10, 2)->nullable()->after('company_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_work_contracts', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'contract_value']);
        });
    }
};
