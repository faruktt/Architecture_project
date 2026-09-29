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
        Schema::table('articles', function (Blueprint $table) {
            $table->string('type')->default('article')->after('category'); // 'article' or 'news'
            $table->json('gallery')->nullable()->after('image');
            $table->boolean('has_audio')->default(true)->after('gallery');
            $table->boolean('has_video')->default(false)->after('has_audio');
            $table->string('badge_text')->nullable()->after('has_video');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['type', 'gallery', 'has_audio', 'has_video', 'badge_text']);
        });
    }
};
