<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lifetime referral attribution: once a customer places their first order
     * through a contributor's link, that contributor is locked onto the
     * customer's account so every future order keeps crediting them.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('referred_by')
                ->nullable()
                ->after('is_contributor')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('referred_at')->nullable()->after('referred_by');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referred_by');
            $table->dropColumn('referred_at');
        });
    }
};
