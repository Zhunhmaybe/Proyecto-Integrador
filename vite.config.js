import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/auth/forgot-password.css',
                'resources/css/auth/login.css',
                'resources/css/auth/register.css',
                'resources/css/auth/two-factor.css',
                'resources/css/auth/unlock.css',
                'resources/css/contacto.css',
                'resources/css/home.css',
                'resources/css/inicial.css',
                'resources/css/login.css',
                'resources/css/profile/two-factor.css',
                'resources/css/recepcionista/citas/create.css',
                'resources/css/recepcionista/edit.css',
                'resources/css/recepcionista/paciente/citas.css',
                'resources/css/recepcionista/paciente/create.css',
                'resources/css/recepcionista/paciente/index.css',
                'resources/css/servicios.css',
                'resources/css/welcome.css',
                'resources/css/auditor/layout.css',
                'resources/css/auditor/dashboard.css',
                'resources/css/auditor/tables.css'
            ],
            refresh: true,
        }),
    ],
});
