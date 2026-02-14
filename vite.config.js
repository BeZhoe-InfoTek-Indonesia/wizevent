import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

const host = 'bz-management.test';

// attempt to load Valet TLS certs if available, otherwise fall back to HTTP
const keyPath = `/Users/dwipurwanto/.config/valet/Certificates/${host}.key`;
const certPath = `/Users/dwipurwanto/.config/valet/Certificates/${host}.crt`;
let httpsOptions = false;
try {
    if (fs.existsSync(keyPath) && fs.existsSync(certPath)) {
        httpsOptions = {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certPath),
        };
    }
} catch (e) {
    // ignore and continue with httpsOptions = false
}

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
    // server: {
    //     host,
    //     hmr: { host },
    //     https: httpsOptions || false,
    // },
    server: {
        host,
        hmr: { host },
        https: httpsOptions || false,
        // Allow requests from tunnel domain
        cors: true,
        allowedHosts: ['.dwi-coding-aja.web.id']
    },
});
