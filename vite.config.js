import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
            // Sortie Laravel standard : public/build (manifest + assets)
            buildDirectory: 'build',
        }),
    ],
    build: {
        // Explicite pour les panneaux de déploiement (Hostinger, etc.)
        outDir: 'public/build',
        emptyOutDir: true,
    },
});
