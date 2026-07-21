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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('pickup_time', 100)->default('Secepatnya')->after('payment_method');
            $table->string('reschedule_status', 50)->nullable()->after('pickup_time');
            $table->text('reschedule_notes')->nullable()->after('reschedule_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pickup_time', 'reschedule_status', 'reschedule_notes']);
        });
    }
};
