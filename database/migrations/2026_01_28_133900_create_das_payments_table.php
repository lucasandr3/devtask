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
        Schema::create('das_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('reference_month'); // mês de referência
            $table->date('due_date'); // data de vencimento
            $table->date('payment_date')->nullable(); // data de pagamento
            $table->decimal('amount', 10, 2); // valor do DAS
            $table->string('status')->default('pending'); // pending, paid, overdue
            $table->string('receipt_file')->nullable(); // arquivo do comprovante
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'reference_month']);
            $table->index(['user_id', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('das_payments');
    }
};
