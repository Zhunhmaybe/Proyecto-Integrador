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
        Schema::create('historias_clinicas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')
                ->constrained('pacientes')
                ->cascadeOnDelete();

            $table->text('alergias')->nullable();
            $table->text('antecedentes')->nullable();
            $table->jsonb('datos_formulario')->nullable();

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
        Schema::dropIfExists('historias_clinicas');
    }
};
