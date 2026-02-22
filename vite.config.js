import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    const appUrl = env.APP_URL ?? 'http://localhost';
    const appHost = new URL(appUrl).hostname;
    const devServerUrl = env.VITE_DEV_SERVER_URL ?? `http://${appHost}:5173`;

    return {
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
                hotFile: 'public/hot',
                refresh: true,
            }),
        ],
        server: {
            host: true,
            port: 5173,
            strictPort: true,
            https: false,
            cors: true,
            origin: devServerUrl,
            hmr: {
                host: appHost,
            },
            allowedHosts: [appHost, 'localhost', '127.0.0.1', '.dwi-coding-aja.web.id', 'event-management.test'],
        },
    };
});
