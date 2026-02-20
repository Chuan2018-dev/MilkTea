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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->string('image')->nullable();
            $table->enum('category', ['milk_tea', 'fruit_tea', 'smoothie', 'coffee', 'other'])->default('milk_tea');
            $table->boolean('is_active')->default(true);
            $table->json('available_sizes')->nullable();
            $table->json('available_sugar_levels')->nullable();
            $table->json('available_ice_levels')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
