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
            $table->string('lead_architects')->nullable()->after('category');
            $table->string('associate')->nullable()->after('lead_architects');
            $table->string('build_year')->nullable()->after('year');
            $table->string('photographer')->nullable()->after('build_year');
            $table->string('illustrations')->nullable()->after('photographer');
            $table->string('phone_number')->nullable()->after('city');
            $table->string('web_address')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'lead_architects',
                'associate',
                'build_year',
                'photographer',
                'illustrations',
                'phone_number',
                'web_address',
            ]);
        });
    }
};
