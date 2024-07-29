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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('themeId')->references('id')->on('theme_recommendations');
            $table->foreignId('userId')->references('id')->on('users');
            $table->string('title');
            $table->string('draft');
            $table->float('royalty')->nullable();
            $table->enum('status', ['submit', 'review', 'accept', 'publish', 'not_accept']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebook');
    }
};
