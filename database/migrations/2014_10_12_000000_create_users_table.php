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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('tel', 20)->nullable();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->integer('rol')->default(3)->comment('1=Administrador, 2=Operador, 3=Usuario');
            $table->integer('estado')->default(1)->comment('1=Activo, 2=Inactivo');
            $table->integer('failed_attempts')->default(0);
            $table->integer('is_locked')->default(0);
            $table->string('lock_code', 10)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
