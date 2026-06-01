<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_leads', function (Blueprint $table) {
            $table->renameColumn('ip', 'ip_address');
        });

        Schema::table('site_leads', function (Blueprint $table) {
            $table->string('source')->nullable()->after('message');
            $table->boolean('privacy_consent')->nullable()->after('source');
            $table->string('privacy_policy_version', 32)->nullable()->after('privacy_consent');
            $table->timestamp('privacy_consented_at')->nullable()->after('privacy_policy_version');
        });
    }

    public function down(): void
    {
        Schema::table('site_leads', function (Blueprint $table) {
            $table->dropColumn([
                'source',
                'privacy_consent',
                'privacy_policy_version',
                'privacy_consented_at',
            ]);
        });

        Schema::table('site_leads', function (Blueprint $table) {
            $table->renameColumn('ip_address', 'ip');
        });
    }
};
