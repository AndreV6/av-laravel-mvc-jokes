<?php

/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Fron-End Dev - ICT50220 (Advanced Programming)
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 20094240
 * Year/Semester: 2024/S1
 *
 * Migration to create the jokes table for storing user-submitted jokes
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create jokes table with category and author relationships
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('jokes', function (Blueprint $table) {
            $table->id();
            $table->text('joke');
            $table->foreignId('category_id')->default(1)->constrained('categories');
            $table->string('tags')->nullable();
            $table->foreignId('author_id')->default(1)->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });

    }

    /**
     * Remove the jokes table
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('jokes');
    }
};
