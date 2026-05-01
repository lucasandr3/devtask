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
        Schema::create('annual_declarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->year('reference_year'); // ano de referência
            $table->decimal('total_revenue', 12, 2)->default(0); // receita total do ano
            $table->decimal('total_das_paid', 12, 2)->default(0); // total de DAS pago no ano
            $table->integer('total_invoices')->default(0); // quantidade de notas fiscais
            $table->timestamp('generated_at')->nullable(); // data de geração
            $table->string('pdf_file')->nullable(); // arquivo PDF gerado
            $table->timestamps();

            $table->unique(['user_id', 'reference_year']);
            $table->index(['user_id', 'reference_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_declarations');
    }
};
