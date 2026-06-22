<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passeios', function (Blueprint $table) {
            $table->renameColumn('passeio_id', 'passeador_id');
        });
    }

    public function down(): void
    {
        Schema::table('passeios', function (Blueprint $table) {
            $table->renameColumn('passeador_id', 'passeio_id');
        });
    }
};