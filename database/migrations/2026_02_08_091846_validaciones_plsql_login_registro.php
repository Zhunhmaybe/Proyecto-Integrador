<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. FUNCIÓN PARA VALIDAR CONTRASEÑA ROBUSTA
        // CORRECCIÓN: Se eliminó el símbolo '%' del mensaje de error final para evitar conflicto de sintaxis.
        DB::unprepared("
            CREATE OR REPLACE FUNCTION validar_fuerza_password(password_texto TEXT)
            RETURNS BOOLEAN AS $$
            BEGIN
                -- Regla 1: Mínimo 10 caracteres
                IF length(password_texto) < 10 THEN
                    RAISE EXCEPTION 'PL/pgSQL: La contraseña debe tener al menos 10 caracteres.';
                END IF;

                -- Regla 2: Al menos una Mayúscula
                IF password_texto !~ '[A-Z]' THEN
                    RAISE EXCEPTION 'PL/pgSQL: La contraseña debe contener al menos una letra mayúscula.';
                END IF;

                -- Regla 3: Al menos una Minúscula
                IF password_texto !~ '[a-z]' THEN
                    RAISE EXCEPTION 'PL/pgSQL: La contraseña debe contener al menos una letra minúscula.';
                END IF;

                -- Regla 4: Al menos un Número
                IF password_texto !~ '[0-9]' THEN
                    RAISE EXCEPTION 'PL/pgSQL: La contraseña debe contener al menos un número.';
                END IF;

                -- Regla 5: Al menos un Carácter Especial
                -- NOTA: Se quitó el símbolo '%' de los ejemplos visuales para evitar error de parámetros en RAISE
                IF password_texto !~ '[^a-zA-Z0-9]' THEN
                    RAISE EXCEPTION 'PL/pgSQL: La contraseña debe contener al menos un carácter especial (Ej: @, #, $, &).';
                END IF;

                RETURN TRUE;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // 2. FUNCIÓN PARA VALIDAR EMAIL
        // Aquí SÍ usamos % porque estamos pasando NEW.email como parámetro al final
        DB::unprepared("
            CREATE OR REPLACE FUNCTION validar_formato_email_func()
            RETURNS TRIGGER AS $$
            BEGIN
                IF NEW.email !~ '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$' THEN
                    RAISE EXCEPTION 'PL/pgSQL: El correo electrónico (%) no tiene un formato válido.', NEW.email;
                END IF;
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // 3. TRIGGER PARA EMAIL
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_validar_email_usuarios ON usuarios;
            CREATE TRIGGER trg_validar_email_usuarios
            BEFORE INSERT OR UPDATE ON usuarios
            FOR EACH ROW EXECUTE FUNCTION validar_formato_email_func();
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_validar_email_usuarios ON usuarios");
        DB::unprepared("DROP FUNCTION IF EXISTS validar_formato_email_func");
        DB::unprepared("DROP FUNCTION IF EXISTS validar_fuerza_password");
    }
};