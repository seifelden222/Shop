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
            // Brand relationship - just the column, no foreign key constraint
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('brand')->nullable(); // Keep brand name for backward compatibility
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('stock')->default(0);
            $table->string('main_image')->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['published', 'archived', 'block'])->default('published');
            $table->date('published_at')->nullable();
            $table->string('name_snapshot')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // Note: Foreign key constraint for brand_id will be added 
            // in a separate migration after brands table is created
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
