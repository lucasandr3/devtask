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
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_account_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('message_id')->nullable(); // ID único do email no servidor
            $table->string('folder')->default('INBOX'); // Pasta (INBOX, Sent, etc)
            
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->text('to_emails'); // JSON array
            $table->text('cc_emails')->nullable(); // JSON array
            $table->text('bcc_emails')->nullable(); // JSON array
            
            $table->string('subject');
            $table->text('body_text')->nullable();
            $table->text('body_html')->nullable();
            
            $table->boolean('is_read')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_draft')->default(false);
            $table->boolean('is_sent')->default(false);
            
            $table->text('attachments')->nullable(); // JSON array
            
            $table->timestamp('received_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['email_account_id', 'folder']);
            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
