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
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'is_hero_story')) {
                $table->boolean('is_hero_story')->default(false)->after('is_spotlight');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_property_sell')) {
                $table->boolean('is_property_sell')->default(false)->after('has_bim');
            }
            if (!Schema::hasColumn('products', 'price')) {
                $table->string('price')->nullable()->after('is_property_sell');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'is_hero_story')) {
                $table->dropColumn('is_hero_story');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_property_sell')) {
                $table->dropColumn('is_property_sell');
            }
            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }
        });
    }
};
