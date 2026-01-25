import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/App.jsx'  // Mudança: App.jsx pro React
            ],
            refresh: true,
        }),
        tailwindcss(),
        react(),  // ADICIONADO pro React
    ],
});
