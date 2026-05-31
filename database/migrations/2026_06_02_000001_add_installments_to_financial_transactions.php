<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->uuid('installment_group_id')->nullable()->after('notes');
            $table->unsignedSmallInteger('installment_number')->nullable()->after('installment_group_id');
            $table->unsignedSmallInteger('installment_count')->nullable()->after('installment_number');

            $table->index('installment_group_id');
        });
    }

    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->dropIndex(['installment_group_id']);
            $table->dropColumn(['installment_group_id', 'installment_number', 'installment_count']);
        });
    }
};
