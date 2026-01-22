import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/frontend/app.scss',
                'resources/sass/frontend-rtl/app.scss',
                'resources/sass/backend/app.scss',
                'resources/js/frontend/app.js',
                'resources/js/backend.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    // Create vendor chunk for node_modules
                    if (id.includes('node_modules')) {
                        // Separate vendor chunk for large libraries
                        if (id.includes('jquery') || id.includes('jquery-ui') || 
                            id.includes('bootstrap') || id.includes('popper.js') ||
                            id.includes('axios') || id.includes('sweetalert2')) {
                            return 'vendor';
                        }
                        // Other node_modules can go into a separate chunk or stay in vendor
                        return 'vendor';
                    }
                },
            },
        },
    },
});
