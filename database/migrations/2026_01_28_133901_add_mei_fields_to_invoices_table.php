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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('service_code')->nullable()->after('descricao'); // código de serviço
            $table->decimal('iss_value', 10, 2)->nullable()->after('service_code'); // valor do ISS
            $table->decimal('tax_amount', 10, 2)->nullable()->after('iss_value'); // valor total de impostos
            $table->string('invoice_type')->default('service')->after('tax_amount'); // service ou product
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['service_code', 'iss_value', 'tax_amount', 'invoice_type']);
        });
    }
};
