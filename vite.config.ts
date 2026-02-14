import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
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
    resolve: {
        alias: {
            '@components': path.resolve(__dirname, './resources/react/Components'),
            '@contexts': path.resolve(__dirname, './resources/react/Contexts'),
        }
    }
});
