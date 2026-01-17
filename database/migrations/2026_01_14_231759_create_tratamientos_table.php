<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tratamientos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('historia_id')
                ->constrained('historias_clinicas')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('usuarios')
                ->cascadeOnDelete();

            $table->text('diagnostico');
            $table->text('notas_procedimiento')->nullable();
            $table->decimal('costo', 10, 2)->default(0.00);
            $table->string('estado', 20)->default('finalizado');
            $table->jsonb('odontograma')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tratamientos');
    }
};
