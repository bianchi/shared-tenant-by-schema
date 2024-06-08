<?php

declare(strict_types=1);

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
        Schema::create('products', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->string('image_path')->nullable();
            $table->boolean('is_vegan')->default(false);
            $table->boolean('is_vegetarian')->default(false);
            $table->boolean('is_organic')->default(false);
            $table->boolean('is_sugar_free')->default(false);
            $table->boolean('is_lactose_free')->default(false);
            $table->timestamps();
        });

        Schema::create('products_per_size', static function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('size_id')->constrained('categories_available_sizes')->onDelete('cascade');
            $table->bigInteger('price');
            $table->unsignedInteger('weight_grams')->nullable();
            $table->unsignedSmallInteger('people_served')->nullable();
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
