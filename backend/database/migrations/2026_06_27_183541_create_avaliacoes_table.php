<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passeio_id')->constrained('passeios');
            $table->foreignId('tutor_id')->constrained('users');
            $table->foreignId('passeador_id')->nullable()->constrained('users');
            $table->unsignedTinyInteger('nota');
            $table->text('comentario')->nullable();
            $table->enum('tipo_avaliador', ['tutor', 'passeador']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};