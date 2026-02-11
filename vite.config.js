import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/visitor.css',
                'resources/js/visitor.js',
                'resources/css/admin.css',
                'resources/js/admin.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        // host: '127.0.0.1',
        // port: 5173,
        // strictPort: true,
        host: 'event-management.test',
        port: 5173,
        hmr: {
            host: 'event-management.test',
        },
    },
});
