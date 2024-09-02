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
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->string('ktp')->nullable();
            $blueprint->string('ttd')->nullable();
            $blueprint->string('bank')->nullable();
            $blueprint->string('noRekening')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn('ktp');
            $blueprint->dropColumn('ttd');
            $blueprint->dropColumn('bank');
            $blueprint->dropColumn('noRekening');
        });
    }
};
