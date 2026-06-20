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
        Schema::create('passeios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dog_id')->constrained()->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('passeio_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('data');
            $table->time('hora');
            $table->integer('duracao');
            $table->string('local');
            $table->decimal('valor', 10, 2);
            $table->enum('status', [
                'pendente',
                'aceito',
                'recusado',
                'finalizado'
            ])->default('pendente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passeios');
    }
};
