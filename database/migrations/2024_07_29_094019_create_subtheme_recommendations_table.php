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
        Schema::create('subtheme_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('themeId')->references('id')->on('theme_recommendations');
            $table->string('name');
            $table->dateTime('dueDate');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_recommendations');
    }
};
