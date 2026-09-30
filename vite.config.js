import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Plus Jakarta Sans', { weights: [400, 500, 600, 700, 800] }),
                bunny('DM Sans', { weights: [400, 500, 600, 700] }),
            ],
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: true,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
        watch: { ignored: ['**/storage/framework/views/**'] },
    },
});
