<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main_data', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->longText('description_ar')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('description_lt')->nullable();
            $table->timestamps();
        });

        // DB::statement("SELECT * FROM pg_extension WHERE extname = 'pg_trgm'");
        // DB::statement("CREATE EXTENSION pg_trgm");
        DB::statement('CREATE INDEX fulltext_idx ON main_data USING GIN (description_ar gin_trgm_ops, description_en gin_trgm_ops, description_lt gin_trgm_ops)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_data');
    }
};
