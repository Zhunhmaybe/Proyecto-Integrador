import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [

                'resources/css/app.css',
                'resources/js/app.js',

                //Auth
                'resources/css/auth/forgot-password.css',
                'resources/css/auth/login.css',
                'resources/css/auth/register.css',
                'resources/css/auth/two-factor.css',
                'resources/css/auth/unlock.css',

                //Profile
                'resources/css/profile/two-factor.css',
                //Recepcionista
                'resources/css/recepcionista/edit.css',
                'resources/css/recepcionista/home.css',

                'resources/css/recepcionista/citas/create.css',
                
                'resources/css/recepcionista/paciente/citas.css',
                'resources/css/recepcionista/paciente/create.css',
                'resources/css/recepcionista/paciente/index.css',

                //Css general
                'resources/css/servicios.css',
                'resources/css/welcome.css',
                'resources/css/auditor/layout.css',
                'resources/css/auditor/dashboard.css',
                'resources/css/auditor/tables.css',
                'resources/css/contacto.css',
                'resources/css/home.css',
                'resources/css/inicial.css',
                'resources/css/login.css',

                //Admin
                'resources/css/admin/admin-panel.css',
                'resources/css/admin/edit.css',
                'resources/css/admin/two-factor.css',

                'resources/css/admin/usuarios/index.css',
                'resources/css/admin/usuarios/edit.css',

                'resources/css/admin/paciente/index.css',
                'resources/css/admin/paciente/create.css',
                'resources/css/admin/paciente/citas.css',

                'resources/css/admin/especialidades/index.css',
                'resources/css/admin/especialidades/edit.css',
                'resources/css/admin/especialidades/create.css',

                'resources/css/admin/Doctor/create.css',
                'resources/css/admin/Doctor/edit.css',
                'resources/css/admin/Doctor/index.css',

                'resources/css/admin/Citas/create.css',
                'resources/css/admin/Citas/edit-citas.css',

                //Auditor
                'resources/css/auditor/logs/index.css',

                'resources/css/auditor/tables/users.css',
                'resources/css/auditor/tables/citas.css',
                'resources/css/auditor/tables/pacientes.css',

                'resources/css/auditor/dashboard.css',
                'resources/css/auditor/home.css',
                'resources/css/auditor/layout.css',
                'resources/css/auditor/tables.css',

                //Doctor
                'resources/css/doctor/home.css',

                'resources/css/doctor/citas/create.css',

                'resources/css/doctor/historia_clinica/historia_clinica.css',

                'resources/css/doctor/paciente/citas.css',
                'resources/css/doctor/paciente/create.css',
                'resources/css/doctor/paciente/index.css',

                //Usuario
                'resources/css/usuario/home.css',

            ],
            refresh: true,
        }),
    ],
});
