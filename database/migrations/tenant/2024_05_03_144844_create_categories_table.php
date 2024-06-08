<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->boolean('is_pizza')->default(false);
            $table->boolean('has_choosable_sizes')->default(false);
            $table->boolean('has_choosable_doughs')->default(false);
            $table->boolean('has_choosable_crusts')->default(false);
            $table->timestamps();
        });

        Schema::create('categories_available_sizes', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name', 60); // Small, Medium, Big
            $table->unsignedSmallInteger('slices')->default(0)->nullable(); // mandatory for pizza
            $table->unsignedSmallInteger('max_toppings')->default(0)->nullable(); // mandatory for pizza
        });

        Schema::create('categories_available_doughs', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name', 60); // Traditional, Pan, Vegan
        });

        Schema::create('categories_available_crusts', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name', 60); // Tradicional, Catupiry, Cheedar, Chocolate
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('categories_available_sizes');
        Schema::dropIfExists('categories_available_doughs');
        Schema::dropIfExists('categories_available_crusts');
    }
};
