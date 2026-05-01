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
        Schema::create('pull_requests', function (Blueprint $table) {
            $table->id();
            $table->string('repo');
            $table->integer('pr_number');
            $table->string('title');
            $table->string('url');
            $table->string('status');
            $table->date('work_date');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index(['user_id', 'work_date']);
            $table->index('repo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pull_requests');
    }
};
