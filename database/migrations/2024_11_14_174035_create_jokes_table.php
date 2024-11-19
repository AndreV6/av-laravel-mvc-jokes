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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jokes');
    }
};
