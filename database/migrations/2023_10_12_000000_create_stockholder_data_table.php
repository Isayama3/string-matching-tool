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
        Schema::create('stockholder_data', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description_ar')->nullable();
            $table->string('description_ar_matching_percentage')->nullable();
            $table->string('description_en')->nullable();
            $table->string('description_en_matching_percentage')->nullable();
            $table->string('description_lt')->nullable();
            $table->string('description_lt_matching_percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockholder_data');
    }
};
