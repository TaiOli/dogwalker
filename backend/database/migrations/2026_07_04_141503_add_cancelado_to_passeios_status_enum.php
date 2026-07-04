<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        DB::statement("
            ALTER TABLE passeios
            MODIFY COLUMN status ENUM('pendente', 'aceito', 'recusado', 'finalizado', 'cancelado')
            NOT NULL DEFAULT 'pendente'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE passeios
            MODIFY COLUMN status ENUM('pendente', 'aceito', 'recusado', 'finalizado')
            NOT NULL DEFAULT 'pendente'
        ");
    }
};