<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passeios', function (Blueprint $table) {
            $table->time('duracao')->change();
        });
    }

    public function down(): void
    {
        Schema::table('passeios', function (Blueprint $table) {
            $table->integer('duracao')->change();
        });
    }
};