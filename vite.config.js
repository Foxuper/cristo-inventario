import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

const ENV = loadEnv('', process.cwd());

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: true,
        hmr: {
            host: ENV.VITE_HMR_HOST,
        },
    },
});
