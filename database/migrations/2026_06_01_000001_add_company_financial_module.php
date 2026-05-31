<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('document')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'name']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('company_id')->constrained()->nullOnDelete();
            $table->decimal('budget', 12, 2)->nullable()->after('ends_at');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('budget');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->after('company_id')->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->after('client_id')->constrained()->nullOnDelete();
            $table->string('payment_status')->default('received')->after('invoice_type');
        });

        Schema::table('das_payments', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('annual_declarations', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('status')->default('pending');
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->date('paid_at')->nullable();
            $table->string('category')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'type', 'status']);
            $table->index(['company_id', 'due_date']);
        });

        $this->backfillCompanyIds();
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');

        Schema::table('annual_declarations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
        });

        Schema::table('das_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('project_id');
            $table->dropConstrainedForeignId('client_id');
            $table->dropConstrainedForeignId('company_id');
            $table->dropColumn('payment_status');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');
            $table->dropColumn(['budget', 'hourly_rate']);
        });

        Schema::dropIfExists('clients');
    }

    private function backfillCompanyIds(): void
    {
        foreach (['invoices', 'das_payments', 'annual_declarations'] as $table) {
            DB::statement("
                UPDATE {$table} AS t
                SET company_id = u.current_company_id
                FROM users AS u
                WHERE u.id = t.user_id
                  AND t.company_id IS NULL
                  AND u.current_company_id IS NOT NULL
            ");
        }
    }
};
