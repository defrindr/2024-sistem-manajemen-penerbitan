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
        Schema::create('keuangan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keuanganId')->references('id')->on('keuangans');
            $table->foreignId('userId')->nullable()->references('id')->on('users');
            $table->float('percent');
            $table->string('role');
            $table->string('buktiTf')->nullable();
            $table->decimal('profit', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_detials');
    }
};
