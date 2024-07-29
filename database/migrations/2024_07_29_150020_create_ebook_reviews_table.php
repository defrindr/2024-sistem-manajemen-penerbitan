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
        Schema::create('ebook_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ebookId')->references('id')->on('ebooks');
            $table->foreignId('reviewerId')->references('id')->on('users');
            $table->integer('acc')
                ->default(0)
                ->comment('1:acc;-1:reject');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebook_reviews');
    }
};
