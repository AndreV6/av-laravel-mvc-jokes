<?php

/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Fron-End Dev - ICT50220 (Advanced Programming)
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 20094240
 * Year/Semester: 2024/S2
 *
 * Migration to add soft delete capability to jokes table
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add deleted_at column to jokes table
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('jokes', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Remove soft delete column from jokes table
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('jokes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
