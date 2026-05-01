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
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('reference_month');
            $table->integer('contract_minutes');
            $table->integer('normal_minutes');
            $table->integer('extra_minutes');
            $table->integer('total_minutes');
            $table->integer('balance_minutes');
            $table->string('status')->default('draft');
            $table->string('approver_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'reference_month']);
            $table->index(['status', 'reference_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
    }
};
