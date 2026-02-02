import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/react/App.tsx',
                'resources/scss/app.scss',
            ],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
});
