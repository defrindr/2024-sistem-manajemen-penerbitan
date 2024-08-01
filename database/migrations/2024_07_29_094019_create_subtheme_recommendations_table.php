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
            // $table->text('description');
            $table->foreignId('reviewer1Id')->references('id')->on('users');
            $table->foreignId('reviewer2Id')->references('id')->on('users');
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
