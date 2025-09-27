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
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            // $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->string('brand')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('stock')->default(0);
            $table->string('main_image')->nullable();
            $table->json('images')->nullable();
            // default must be one of the enum values; use 'published' instead of 'active'
            $table->enum('status', ['published', 'archived', 'block'])->default('published');
            $table->date('published_at')->nullable();
            $table->string('name_snapshot')->nullable();
            $table->softDeletes();
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
