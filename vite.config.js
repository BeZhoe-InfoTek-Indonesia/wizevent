import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

const host = 'event-management.test';

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
        host,
        hmr: { host },
        https: {
            key: fs.readFileSync(`/Users/dwipurwanto/.config/valet/Certificates/${host}.key`),
            cert: fs.readFileSync(`/Users/dwipurwanto/.config/valet/Certificates/${host}.crt`),
        },
    },
});
