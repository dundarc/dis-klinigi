import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                                'resources/js/reports.js', // Bu satırı ekleyin
                'resources/js/calendar.js', // Bu satırı ekleyin
            ],
            refresh: true,
        }),
    ],
});