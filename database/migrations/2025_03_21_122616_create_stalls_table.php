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
            $table->string('stall_name');
            $table->text('stall_description');
            $table->date('stall_date');
            $table->string('stall_location');
            $table->string('time_range');
            $table->string('contact_email');
            $table->decimal('stall_price', 10, 2); // Price based on location
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->string('receipt');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
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
