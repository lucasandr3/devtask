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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('numero');
            $table->string('serie')->default('1');
            $table->date('data_emissao');
            $table->decimal('valor', 10, 2);
            $table->text('descricao')->nullable();
            $table->string('arquivo')->nullable(); // caminho do arquivo PDF
            $table->timestamps();

            $table->index(['user_id', 'data_emissao']);
            $table->index('numero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
