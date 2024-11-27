<?php

/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Fron-End Dev - ICT50220 (Advanced Programming)
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 20094240
 * Year/Semester: 2024/S1
 *
 * Migration to create the categories table for joke categorization
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create categories table with user relationship
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->default('Unknown')->unique();
            $table->foreignId('user_id')->default(10)->constrained();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Remove the categories table
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
