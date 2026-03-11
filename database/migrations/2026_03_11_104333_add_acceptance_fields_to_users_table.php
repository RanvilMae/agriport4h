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
        Schema::table('users', function (Blueprint $table) {
            // Status of the user account
            $table->boolean('is_accepted')->default(false)->after('role');

            // Audit trail: who approved this user and when
            $table->foreignId('accepted_by')->nullable()->constrained('users')->nullOnDelete()->after('is_accepted');
            $table->timestamp('accepted_at')->nullable()->after('accepted_by');

            // Optional: Reason if the account is rejected or deactivated
            $table->text('rejection_reason')->nullable()->after('accepted_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['accepted_by']);
            $table->dropColumn(['is_accepted', 'accepted_by', 'accepted_at', 'rejection_reason']);
        });
    }
};
