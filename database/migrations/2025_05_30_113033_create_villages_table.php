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
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('postal_code')->nullable();
            $table->foreignId('province_id')
                ->constrained('provinces')
                ->onDelete('cascade');
            $table->foreignId('city_id')
                ->constrained('cities')
                ->onDelete('cascade');
            $table->foreignId('district_id')
                ->constrained('districts')
                ->onDelete('cascade');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
