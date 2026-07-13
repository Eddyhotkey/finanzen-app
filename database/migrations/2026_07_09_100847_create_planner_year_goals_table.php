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
        Schema::create('planner_year_goals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('category_label');
            $table->string('title');
            $table->text('note')->nullable();
            $table->enum('status', ['offen', 'aktiv', 'erledigt'])->default('offen');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->unsignedSmallInteger('year');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planner_year_goals');
    }
};
