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
        Schema::table('planner_appointments', function (Blueprint $table) {
            $table->timestamp('notified_at')->nullable()->after('end_time');
        });

        Schema::table('planner_tasks', function (Blueprint $table) {
            $table->timestamp('notified_at')->nullable()->after('is_done');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planner_appointments', function (Blueprint $table) {
            $table->dropColumn('notified_at');
        });

        Schema::table('planner_tasks', function (Blueprint $table) {
            $table->dropColumn('notified_at');
        });
    }
};
