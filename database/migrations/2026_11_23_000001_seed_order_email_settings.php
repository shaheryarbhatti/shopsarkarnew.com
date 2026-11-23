<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Seed the order-email toggles as enabled, preserving the existing
     * behaviour (both emails sent) while making the state visible/editable
     * in Settings → Order Emails.
     */
    public function up(): void
    {
        $now = now();

        foreach (['email_customer_order', 'email_admin_order'] as $key) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => '1', 'group' => 'emails', 'type' => 'boolean', 'updated_at' => $now, 'created_at' => $now]
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['email_customer_order', 'email_admin_order'])->delete();
    }
};
