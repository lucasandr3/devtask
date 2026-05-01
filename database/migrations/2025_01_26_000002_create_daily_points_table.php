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
        Schema::create('daily_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('work_date');
            $table->time('entry_time')->nullable();
            $table->time('lunch_out_time')->nullable();
            $table->time('lunch_return_time')->nullable();
            $table->time('exit_time')->nullable();
            $table->time('extra_start_time')->nullable();
            $table->time('extra_end_time')->nullable();
            $table->integer('normal_minutes')->default(0);
            $table->integer('extra_minutes')->default(0);
            $table->integer('total_minutes')->default(0);
            $table->text('notes')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();

            $table->unique(['user_id', 'work_date']);
            $table->index(['status', 'work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_points');
    }
};
