/**
 * Vite Configuration File
 * 
 * This file configures the Vite build tool for the Laravel application.
 * It sets up the build process, plugins, and entry points for the frontend assets.
 */

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    // Configure plugins for the build process
    plugins: [
        // Laravel Vite plugin for seamless integration with Laravel
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // Tailwind CSS plugin for processing Tailwind styles
        tailwindcss(),
    ],
});
