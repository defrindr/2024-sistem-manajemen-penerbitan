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
            $table->foreignId('subthemeId')->references('id')->on('subtheme_recommendations');
            $table->foreignId('userId')->references('id')->on('users');
            $table->string('title');
            $table->string('draft')->nullable();
            $table->string('proofOfPayment')->nullable();
            $table->dateTime('dueDate')->nullable();
            $table->float('royalty')->nullable();
            $table->string('haki')->nullable();
            $table->string('ktp')->nullable();
            $table->enum('status', ['payment', 'pending', 'drafting', 'submit', 'review', 'accept', 'not_accept']);
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
