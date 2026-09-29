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
            $table->foreignId('author_id')->nullable()->constrained('authors')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('manufacturer')->default('General');
            $table->string('manufacturer_logo')->nullable();
            $table->string('category')->default('Software / Courses');
            $table->string('country_region')->default('Global');
            $table->boolean('has_bim')->default(true);
            $table->string('website_url')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->text('short_description')->nullable();
            $table->text('use_description')->nullable();
            $table->text('applications')->nullable();
            $table->text('characteristics')->nullable();
            $table->json('specifications')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->text('admin_notes')->nullable();
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
