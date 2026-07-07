<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planned_transactions', function (Blueprint $table) {
            $table->enum('repeat_type', ['none', 'monthly', 'yearly'])
                ->default('none')
                ->after('description');

            $table->unsignedTinyInteger('repeat_day')
                ->nullable()
                ->after('repeat_type');

            $table->date('repeat_until')
                ->nullable()
                ->after('repeat_day');

            $table->foreignId('parent_id')
                ->nullable()
                ->after('repeat_until')
                ->constrained('planned_transactions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('planned_transactions', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'repeat_type',
                'repeat_day',
                'repeat_until',
                'parent_id',
            ]);
        });
    }
};
