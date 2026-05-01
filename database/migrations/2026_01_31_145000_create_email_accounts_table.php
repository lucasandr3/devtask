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
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nome da conta (ex: "Gmail Pessoal", "Outlook Trabalho")
            $table->string('email'); // Endereço de email
            $table->enum('provider', ['gmail', 'outlook', 'yahoo', 'icloud', 'custom'])->default('custom');
            
            // Configurações SMTP (envio)
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->default(587);
            $table->string('smtp_username')->nullable();
            $table->text('smtp_password')->nullable(); // Criptografado
            $table->enum('smtp_encryption', ['tls', 'ssl', 'none'])->default('tls');
            
            // Configurações IMAP (recebimento)
            $table->string('imap_host')->nullable();
            $table->integer('imap_port')->default(993);
            $table->string('imap_username')->nullable();
            $table->text('imap_password')->nullable(); // Criptografado
            $table->enum('imap_encryption', ['tls', 'ssl', 'none'])->default('ssl');
            
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
