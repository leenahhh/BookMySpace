<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('product_type');
            $table->text('desc');
            $table->text('socials')->nullable();
            $table->string('logo');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('rejected_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
