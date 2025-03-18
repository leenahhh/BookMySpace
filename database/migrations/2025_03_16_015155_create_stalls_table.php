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
        Schema::create('stalls', function (Blueprint $table) {
            $table->id();
            $table->string('stallName');
            $table->text('stallDescription');
            $table->date('stallDate');
            $table->string('stallLocation');
            $table->time('startTime');
            $table->time('endTime');
            $table->string('contactEmail');
            $table->decimal('stallBookingCost', 8, 2);
            $table->boolean('isAvailable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stalls');
    }
};
